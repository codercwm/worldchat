<template>
    <div class="container">
        <div class="title">
            <mu-appbar title="Title">
                <mu-button icon slot="left" @click="goback">
                    <mu-icon value="chevron_left"></mu-icon>
                </mu-button>
                <div class="center"></div>
                <mu-button icon slot="right">
                    <mu-icon value="expand_more"></mu-icon>
                </mu-button>
            </mu-appbar>
        </div>
        <div class="chat-inner">
            <div v-for="(obj, index) in getRobotMsg" :key="index" class="">
                <Message :is-self="obj.nickname === hoster" :nickname="obj.nickname" :avatar="obj.avatar" :msg="obj.msg" :img="obj.img" :mytime="obj.time"></Message>
            </div>
        </div>
        <div class="con-input">
            <div class="input" @keyup.enter="sendmessage">
                <input id="msg_input" maxlength="100" type="text" v-model="chatValue">
            </div>
            <mu-button class="demo-raised-button" primary @click="sendmessage">发送</mu-button>
        </div>
    </div>

</template>

<script type="text/ecmascript-6">
    import {mapGetters, mapState} from "vuex";
    import Alert from '../components/Alert';
    import Message from "../components/Message";
    import {HOSTER_IMG, HOSTER_NAME} from "../../const/index";
    import dateFormat from "../utils/date";
    import {inHTMLData} from "xss-filters-es6";

    export default {
        data() {
            return {
                chatValue: '',
            };
        },
        mounted() {
            // this.$store.commit('setTab', true);
        },
        methods: {
            goback() {
                this.$router.goBack();
                this.$store.commit("setTab", true);
            },
            sendmessage() {
                if (this.chatValue.trim() !== '') {
                    if (this.chatValue.length > 240) {
                        Alert({
                            content: '字数不能超过100'
                        });
                        document.getElementById("msg_input").blur();
                        return;
                    }
                    const msg = inHTMLData(this.chatValue); // 防止xss
                    const user_id = this.user_id;
                    const api_token = this.api_token;
                    const data = {
                        msg,
                        user_id,
                        api_token
                    };

                    this.$store.commit("setRobotMsg", {
                        msg: msg,
                        nickname: this.hoster,
                        avatar: this.hosterImg,
                        time: dateFormat(new Date(), "yyyy-MM-dd HH:mm:ss"),
                    });
                    this.$store.dispatch("getRobatMess", data);
                    this.chatValue = '';
                }else{
                    Alert({
                        content: '内容不能为空'
                    });
                    document.getElementById("msg_input").blur();
                }
            }
        },
        computed: {
            ...mapGetters(["getRobotMsg"]),
            ...mapState({
                user_id: state => state.userInfo.user_id,
                avatar: state => state.userInfo.avatar,
                api_token: state => state.userInfo.api_token,
                nickname: state => state.userInfo.nickname,
            }),
            hoster() {
                //如果登录了就获取登录着名称，未登录就获取默认名称
                if (this.nickname) {
                    return this.nickname;
                }
                return HOSTER_NAME;
            },
            hosterImg() {
                //如果登录了就获取登录者头像，未登录就获取默认头像
                if (this.avatar) {
                    return this.avatar;
                }
                return HOSTER_IMG;
            },
        },
        components: {
            Message
        }
    };
</script>

<style lang="stylus" rel="stylesheet/stylus" scoped>
    .container {
        width: 100%;
        height: 100%;
        overflow: hidden;
        background: #f1f5f8;
        margin-left: 0 !important;
        margin-right: 0 !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
        max-width: none !important;

    .title {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        z-index: 1;

    .center {
        -webkit-box-flex: 1;
        -webkit-flex: 1;
        -ms-flex: 1;
        flex: 1;
        padding-left: 8px;
        padding-right: 8px;
        white-space: nowrap;
        text-overflow: ellipsis;
        overflow: hidden;
        font-size: 20px;
        font-weight: 400;
        line-height: 56px;
        text-align: center;
    }

    }

    .chat-inner {
        position: absolute;
        width: 100%;
        overflow-y: scroll;
        overflow-x: hidden;
        top: 80px;
        bottom: 80px;
    }

    .con-input {
        width: 100%;
        position: fixed;
        height: 50px;
        bottom: 0px;
        display: flex;

    .input {
        flex: 1;
        background: #ddd;
        padding: 4px;

    input {
        width: 100%;
        height: 42px;
        box-sizing: border-box;
        border: 1px solid #ddd;
        color: #333333;
        font-size: 18px;
        padding-left: 5px;
    }

    .mu-text-field {
        width: 100%;
    }

    }

    .demo-raised-button {
        height: 50px;
    }

    }
    }
</style>
