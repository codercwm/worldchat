import {setTimeout} from "timers";
<template>
    <div class="hello">
        <div>
            <mu-list>
                <mu-sub-header>群列表</mu-sub-header>
                <mu-list-item v-for="(room,index) in roomsList" :key="room.id" avatar button :ripple="false" @click="chatwindow(room.id,room.name)">
                    <mu-list-item-action>
                        <mu-avatar>
                            <img :src="house1">
                        </mu-avatar>
                    </mu-list-item-action>
                    <mu-list-item-title>{{room.name}}</mu-list-item-title>
                    <mu-list-item-action>
                        <span class="unread">{{unRead1}}</span>
                        <mu-icon value="chat_bubble"></mu-icon>
                    </mu-list-item-action>
                </mu-list-item>
                <!--<mu-list-item avatar button :ripple="false" @click="chatwindow('2')">
                    <mu-list-item-action>
                        <mu-avatar>
                            <img :src="house2">
                        </mu-avatar>
                    </mu-list-item-action>
                    <mu-list-item-title>聊天室2</mu-list-item-title>
                    <mu-list-item-action>
                        <span class="unread">{{unRead2}}</span>
                        <mu-icon value="chat_bubble"></mu-icon>
                    </mu-list-item-action>
                </mu-list-item>-->
            </mu-list>
            <mu-divider></mu-divider>
            <mu-list>
                <mu-sub-header>客服</mu-sub-header>
                <mu-list-item avatar button :ripple="false" @click="chatRobot()">
                    <mu-list-item-action>
                        <mu-avatar>
                            <img :src="robot">
                        </mu-avatar>
                    </mu-list-item-action>
                    <mu-list-item-title>机器人客服</mu-list-item-title>
                    <mu-list-item-action>
                        <mu-icon value="chat_bubble"></mu-icon>
                    </mu-list-item-action>
                </mu-list-item>
            </mu-list>
        </div>
    </div>
</template>

<script>
    import Confirm from "../components/Confirm";
    import InputConfirm from "../components/InputConfirm";
    import {mapState} from "vuex";
    import {ROBOT_IMG, HOUSE_IMG1, HOUSE_IMG2,HOST_NAME} from "../../const/index";
    import url from "../api/server";

    export default {
        data() {
            return {
                house1: HOUSE_IMG1,
                house2: HOUSE_IMG2,
                robot: ROBOT_IMG,
                roomsList:[],
                HOST_NAME:HOST_NAME,
            };
        },
        async mounted() {
            //获取聊天室列表
            const rooms_res = await url.getRoomsList();
            if(0==rooms_res.data.errno){
                this.roomsList = rooms_res.data.data;
            }

            this.$store.commit("setTab", true);

            // 只全局监听一次
            if (!this.isLogin) {//这个表示进行了登录操作才会触发
                // 登录了,发送进入信息。
                if (this.user_id) {
                    this.$store.commit("setLoginState", true);
                }
            }

        },
        methods: {
            async chatwindow(room_id,room_name) {
                if(1==room_id){
                    if (!this.user_id) {
                        const res = await InputConfirm({
                            title: "输入您的名字"
                        });
                        if (res.res === "submit") {
                            this.$store.commit("setUserInfo", {
                                type: "user_id",
                                value: (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1)
                            });
                            this.$store.commit("setUserInfo", {
                                type: "nickname",
                                value: res.content
                            });
                        }else{
                            return;
                        }

                    }
                    this.$store.commit("setTab", false);
                    this.$router.push({path: "/Pchat", query: {room_id: room_id,room_name:room_name}});
                    return;
                }else{
                    if (!this.api_token) {
                        const res = await Confirm({
                            title: "提示",
                            content: "您好，请先登录"
                        });
                        if (res === "submit") {
                            this.$router.push({path: "login"});
                        }
                        return;
                    }
                    this.$store.commit("setTab", false);
                    this.$router.push({path: "/chat", query: {room_id: room_id,room_name:room_name}});
                    return;
                }
            },
            async chatRobot() {
                this.$store.commit("setTab", false);
                this.$router.push({path: "/robot"});
            }
        },
        computed: {
            ...mapState({
                user_id: state => state.userInfo.user_id,
                avatar: state => state.userInfo.avatar,
                api_token: state => state.userInfo.api_token,
                nickname: state => state.userInfo.nickname,
                isLogin: state => state.isLogin,
                unRead1: state =>{
                    if(state.unRead.room1>0){
                        return state.unRead.room1;
                    }
                    return '';
                },
                unRead2: state =>{
                    if(state.unRead.room2>0){
                        return state.unRead.room2;
                    }
                    return '';
                },
            })
        }
    };
</script>

<!-- Add "scoped" attribute to limit CSS to this component only -->
<style lang="stylus" rel="stylesheet/stylus">
    .avatar {
        position: relative;

        .tip {
            position: absolute;
            right: -5px;
            top: -8px;
            padding: 0px 5px;
            border-radius: 10px;
            line-height: 20px;
            text-align: center;
            background: #ff2a2a;
            color: #fff;
            font-size: 12px;
        }

        .mu-avatar {

            img {
                border-radius: 5px;
            }

        }
    }
    .unread{
        position:absolute;
        top:10px;
        /*color:#eeeeee;*/
        text-align: center;
        width: 23px;
        font-size: 10px;
        color:darkred;
    }
</style>
