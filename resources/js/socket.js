// 通过 socket.io 客户端进行 WebSocket 通信
import io from 'socket.io-client';
import store from "./store";
let api_token = store.state.userInfo.api_token;
const socket = io('http://worldchat.test?api_token='+api_token);
export default socket;
