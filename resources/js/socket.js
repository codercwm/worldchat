// 通过 socket.io 客户端进行 WebSocket 通信
import io from 'socket.io-client';
import store from "./store";
import {PROTOCOL_HOST_NAME} from "../const/index";

let api_token = store.state.userInfo.api_token;
const socket = io(PROTOCOL_HOST_NAME+'?api_token='+api_token);
export default socket;
