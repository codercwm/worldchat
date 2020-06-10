/**
 * Created by Administrator on 2017/4/17.
 */
import Vue from 'vue';
import Vuex from 'vuex';
import url from '../api/server';
import {
    setItem,
    getItem
} from '../utils/localStorage';
import {
    ROBOT_NAME,
    ROBOT_URL
} from '../const';
import dateFormat from "../utils/date";

Vue.use(Vuex);

const store = new Vuex.Store({
    state: {
        userInfo: {
            avatar: getItem('avatar'),
            user_id: getItem('user_id'),
            api_token: getItem('api_token')
        },
        isDiscount: false,
        isLogin: false,
        // 存放房间信息，为了方便以后做多房间
        roomdetail: {
            id: '',
            users: {},
            infos: [],//保存消息列表
            current: 1,
            total: 0
        },
        // 存放机器人开场白
        robotmsg: [{
                username: ROBOT_NAME,
                avatar: ROBOT_URL,
                msg: '你好，我是机器人，有什么想知道的可以问我，但我也不一定知道。',
                time:dateFormat(new Date(), "yyyy-MM-dd HH:mm:ss"),
            }
        ],
        unRead: {
            room1: 0,
            room2: 0
        },
        // svg
        svgmodal: null,
        // 是否启动tab
        istab: false,

        emojiShow: false,
    },
    getters: {
        getTotal: state => state.roomdetail.total,
        getCurrent: state => state.roomdetail.current,
        getUsers: state => state.roomdetail.users,
        getInfos: state => state.roomdetail.infos,
        getRobotMsg: state => state.robotmsg,
        getEmoji: state => state.emojiShow,
    },
    mutations: {
        setTotal(state, value) {
            state.roomdetail.total = value;
        },
        setDiscount(state, value) {
            state.isDiscount = value;
        },
        setCurrent(state, value) {
            state.roomdetail.current = value;
        },
        //设置未读消息数
        setUnread(state, value) {
            for (let i in value) {
                state.unRead[i] = +value[i];
            }
        },
        setLoginState(state, value) {
            state.isLogin = value;
        },
        setUserInfo(state, data) {
            const {
                type,
                value
            } = data;
            setItem(type, value);
            state.userInfo[type] = value;
        },
        setEmoji(state, data) {
            state.emojiShow = data;
        },
        setTab(state, data) {
            state.istab = data;
        },
        setSvgModal(state, data) {
            state.svgmodal = data;
        },
        addRoomDetailInfos(state, data) {
            state.roomdetail.infos.push(...data);
        },
        addRoomDefatilInfosHis(state, data) {
            //state.roomdetail.infos是用来保存消息列表的
            const list = state.roomdetail.infos;
            //这里把传入来的消息列表合并起来
            state.roomdetail.infos = data.concat(list);

        },
        setRoomDetailInfos(state) {
            state.roomdetail.infos = [];
        },
        setUsers(state, data) {
            state.roomdetail.users = data;
        },
        setRobotMsg(state, data) {
            state.robotmsg.push(data);
        }
    },
    actions: {
        async uploadAvatar({
            commit
        }, data) {
            const res = await url.postUploadAvatar(data);
            return res.data;
        },
        async uploadImg({
            commit
        }, data) {
            const res = await url.postUploadFile(data);
            if (res) {
                if (res.data.errno === 0) {
                    console.log('上传成功');
                }
            }
        },
        async registerSubmit({
            commit
        }, data) {
            const res = await url.RegisterUser(data);
            if (res.data.errno === 0) {
                return {
                    status: 'success',
                    data: res.data
                };
            }
            return {
                status: 'fail',
                data: res.data
            };
        },
        async loginSubmit({
            commit
        }, data) {
            const res = await url.loginUser(data);
            if (res.data.errno === 0) {
                return {
                    status: 'success',
                    data: res.data
                };
            }
            return {
                status: 'fail',
                data: res.data
            };
        },
        async getAllMessHistory({
            state,
            commit
        }, data) {
            const res = await url.RoomHistoryAll(data);
            if (res.data.errno === 0) {
                //这里把list传入去，list是历史消息列表
                commit('addRoomDefatilInfosHis', res.data.data.list);
                if (!state.roomdetail.total) {
                    commit('setTotal', res.data.data.message_total);
                }
            }
        },
        async getRobatMess({
            commit
        }, data) {
            const nickname = ROBOT_NAME;
            const avatar = ROBOT_URL;
            const res = await url.getRobotMessage(data);

            if (res) {
                const robotdata = res.data.data;
                let msg = '';
                // 分类信息
                if (robotdata.code === 100000) {
                    msg = robotdata.text;
                } else if (robotdata.code === 200000) {
                    msg = robotdata.text + robotdata.url;
                } else {
                    msg = '暂不支持此类对话';
                }
                let time = dateFormat(new Date(), "yyyy-MM-dd HH:mm:ss");
                commit('setRobotMsg', {
                    msg,
                    nickname,
                    avatar,
                    time
                })
            }
        }
    }
});
export default store;
