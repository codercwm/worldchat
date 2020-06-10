<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Room;
use App\Services\ResService;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index(Request $request){
        $list = Room::get();
        return ResService::data($list)->success();
    }
}
