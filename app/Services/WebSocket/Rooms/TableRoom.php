<?php

namespace App\Services\Websocket\Rooms;

use App\Services\LogService;
use Swoole\Table;

class TableRoom implements RoomContract
{
    /**
     * @var array
     */
    protected $config;

    /**
     * @var Table
     */
    protected $rooms;

    /**
     * @var Table
     */
    protected $fds;

    /**
     * TableRoom constructor.
     *
     * @param array $config
     */
    public function __construct(array $config)
    {
        $this->config = $config;
    }

    /**
     * Do some init stuffs before workers started.
     *
     * @return RoomContract
     */
    public function prepare(): RoomContract
    {
        $this->initRoomsTable();
        $this->initFdsTable();

        return $this;
    }

    /**
     * Add a socket fd to multiple rooms.
     *
     * @param int fd
     * @param array|string rooms
     */
    public function add(int $fd, $roomNames)
    {
        $rooms = $this->getRooms($fd);
        $roomNames = is_array($roomNames) ? $roomNames : [$roomNames];

        foreach ($roomNames as $room) {
            $fds = $this->getClients($room);
            //这里坑了我很久，获取到的fds居然是空的，导致用户加入房间时，获取不到其它用户的fd，无法对其它用户进行通知
            //LogService::write("room:{$room} 里面已经有的fds : ".json_encode($fds), 'room_in_out');

            if (in_array($fd, $fds)) {
                continue;
            }

            $fds[] = $fd;
            $rooms[] = $room;

            $this->setClients($room, $fds);
        }

        $this->setRooms($fd, $rooms);
    }

    /**
     * Delete a socket fd from multiple rooms.
     *
     * @param int fd
     * @param array|string rooms
     */
    public function delete(int $fd, $roomNames = [])
    {
        $allRooms = $this->getRooms($fd);
        $roomNames = is_array($roomNames) ? $roomNames : [$roomNames];
        $rooms = count($roomNames) ? $roomNames : $allRooms;

        $removeRooms = [];
        foreach ($rooms as $room) {
            $fds = $this->getClients($room);

            if (! in_array($fd, $fds)) {
                continue;
            }

            $this->setClients($room, array_values(array_diff($fds, [$fd])));
            $removeRooms[] = $room;
        }

        $this->setRooms($fd, array_values(array_diff($allRooms, $removeRooms)));
    }

    /**
     * Get all sockets by a room key.
     *
     * @param string room
     *
     * @return array
     */
    public function getClients(string $room)
    {
        return $this->getValue($room, RoomContract::ROOMS_KEY) ?? [];
    }

    /**
     * Get all rooms by a fd.
     *
     * @param int fd
     *
     * @return array
     */
    public function getRooms(int $fd)
    {
        return $this->getValue($fd, RoomContract::DESCRIPTORS_KEY) ?? [];
    }

    /**
     * @param string $room
     * @param array $fds
     *
     * @return TableRoom
     */
    protected function setClients(string $room, array $fds): TableRoom
    {
        $res = $this->setValue($room, $fds, RoomContract::ROOMS_KEY);
        //这里明明是已经加入去了的，获取时却获取不到
        /*LogService::write(
            "把fds ".json_encode($fds)."加入到 {$room} ,
            加入后room {$room} 有fds : ".
            json_encode($this->getValue($room, RoomContract::ROOMS_KEY)),
            'room_in_out');*/
        return $res;
    }

    /**
     * @param int $fd
     * @param array $rooms
     *
     * @return TableRoom
     */
    protected function setRooms(int $fd, array $rooms): TableRoom
    {
        //LogService::write("fd {$fd} 的rooms : ".json_encode($rooms), 'room_in_out');
        return $this->setValue($fd, $rooms, RoomContract::DESCRIPTORS_KEY);
    }

    /**
     * Init rooms table
     */
    protected function initRoomsTable(): void
    {
        $this->rooms = new Table($this->config['room_rows']);
        $this->rooms->column('value', Table::TYPE_STRING, $this->config['room_size']);
        $this->rooms->create();
    }

    /**
     * Init descriptors table
     */
    protected function initFdsTable()
    {
        $this->fds = new Table($this->config['client_rows']);
        $this->fds->column('value', Table::TYPE_STRING, $this->config['client_size']);
        $this->fds->create();
    }

    /**
     * Set value to table
     *
     * @param $key
     * @param array $value
     * @param string $table
     *
     * @return $this
     */
    public function setValue($key, array $value, string $table)
    {
        $this->checkTable($table);

        $this->$table->set($key, ['value' => json_encode($value)]);

        return $this;
    }

    /**
     * Get value from table
     *
     * @param string $key
     * @param string $table
     *
     * @return array|mixed
     */
    public function getValue(string $key, string $table)
    {
        $this->checkTable($table);

        $value = $this->$table->get($key);

        return $value ? json_decode($value['value'], true) : [];
    }

    /**
     * Check table for exists
     *
     * @param string $table
     */
    protected function checkTable(string $table)
    {
        if (! property_exists($this, $table) || ! $this->$table instanceof Table) {
            throw new \InvalidArgumentException("Invalid table name: `{$table}`.");
        }
    }
}
