// 通过 socket.io 客户端进行 WebSocket 通信
import io from 'socket.io-client';
const socket = io('http://worldchat.test', {
    path: '/ws',
    transports: ['websocket']
});
export default socket;
