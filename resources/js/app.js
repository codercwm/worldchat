/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import Vue from 'vue';
import App from './layout/App';  //app layout
import router from './router';   // router
import store from './store';     // store
import socket from './socket';
import {queryString} from './utils/queryString';
import MuseUI from 'muse-ui';    // muse-ui组件
Vue.use(MuseUI);

import vuePicturePreview from './components/photo-viewer';
Vue.use(vuePicturePreview);

Vue.config.productionTip = false;

const Notification = window.Notification;

const popNotice = function(msgInfo) {
    if (Notification.permission === "granted") {
        let content = '';
        if (msgInfo.img !== '') {
            content = '[图片]';
        } else {
            content = msgInfo.msg;
        }
        const notification = new Notification(`【${msgInfo.room_id}】 提示`, {
            body: content,
            icon: msgInfo.src
        });
        notification.onclick = function() {
            notification.close();
        };
    }
};

//监听后端emit过来的connect事件
socket.on('connect', async () => {
    console.log('websocket 已连接: ' + socket.connected);
    const room_id = queryString(window.location.href, 'room_id');
    const user_id = store.state.userInfo.user_id;
    const api_token = store.state.userInfo.api_token;
    if (room_id) {
        const obj = {
            email: user_id,
            avatar: store.state.userInfo.avatar,
            room_id: room_id,
            api_token: api_token
        };
        socket.emit('room', obj);

        //这里干嘛要这样？不知道，先不要
        /*if (store.state.isDiscount) {
            await store.commit('setRoomDetailInfos');
            await store.commit('setCurrent', 1);
            await store.commit('setDiscount', false);
            await store.commit('setTotal', 0);
            await store.dispatch('getAllMessHistory', {
                current: 1,
                room_id: room_id,
                api_token: api_token
            });
        }*/
    }
});

socket.on('disconnect', () => {
    console.log('websocket 已断开:' + socket.disconnected);
    store.commit('setDiscount', true);
});

socket.on('message', function (obj) {
    store.commit('addRoomDetailInfos', [obj]);
    if (Notification.permission === "granted") {
        popNotice(obj);
    } else if (Notification.permission !== "denied") {
        Notification.requestPermission(function (permission) {
            popNotice(obj);
        });
    }
});

document.addEventListener('touchstart', (e) => {
    if (e.target.className.indexOf('emoji') > -1 || e.target.parentNode.className.indexOf('emoji') > -1) {
        store.commit('setEmoji', true);
    } else {
        store.commit('setEmoji', false);
    }
});

document.addEventListener('click', (e) => {
    if (e.target.className.indexOf('emoji') > -1 || e.target.parentNode.className.indexOf('emoji') > -1) {
        store.commit('setEmoji', true);
    } else {
        store.commit('setEmoji', false);
    }
});

/* eslint-disable no-new */
new Vue({
    el: '#app',
    router,
    store,
    template: '<App/>',
    components: {App}
});


