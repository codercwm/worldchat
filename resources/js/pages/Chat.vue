<template>
    <div>
        <div class="container">
            <mu-dialog width="360" :open.sync="openSimple">
                <div class="all-chat">
                    <div slot="title">在线人员</div>
                    <div v-for="(obj,index) in getUsers" class="online" :key="index">
                        <img :src="obj.avatar" alt="">
                    </div>
                </div>
                <mu-button slot="actions" flat color="primary" @click="closeSimpleDialog">关闭</mu-button>
            </mu-dialog>
            <div class="title">
                <mu-appbar title="Title">
                    <mu-button icon slot="left" @click="goback">
                        <mu-icon value="chevron_left"></mu-icon>
                    </mu-button>
                    <div class="center">
                        {{room_name}}({{Object.keys(getUsers).length}})
                    </div>
                    <mu-button icon slot="right" @click="openSimpleDialog">
                        <mu-icon value="people"></mu-icon>
                    </mu-button>
                </mu-appbar>
            </div>
            <!-- <div class="notice" v-if="noticeList.length > 0" :class="[noticeBar ? 'notice-hidden' : '']">
              <div class="notice-container">
                <div class="notice-li" v-for="(item, key) in noticeList" :key="key">
                  <a :href="item.href">{{key + 1}}. {{item.title}}</a>
                </div>
              </div>
              <div class="notice-tool-bar" @click="handleNotice">
                {{noticeBar ? '显示通知' : '关闭通知'}}
              </div>
            </div> -->
            <div class="chat-inner">
                <div class="chat-container">
                    <div v-if="getInfos.length === 0" class="chat-no-people">暂无消息,赶紧来占个沙发～</div>
                    <!--这个是loding转圈圈<div class="chat-loading">
                        <div class="lds-css ng-scope">
                            <div class="lds-rolling">
                                <div></div>
                            </div>
                        </div>
                    </div>-->
                    <!-- <div v-if="getInfos.length > 0" class="chat-top">到顶啦~</div> -->
                    <Message v-for="obj in getInfos" :key="obj._id" :is-self="obj.user_id === user_id" :nickname="obj.nickname" :avatar="obj.avatar" :msg="obj.msg" :img="obj.img" :mytime="obj.time" :container="container"></Message>
                    <div class="clear"></div>
                </div>
            </div>
            <div class="bottom">
                <div class="functions">
                    <div class="fun-li" @click="imgupload">
                        <i class="icon iconfont icon-camera"></i>
                    </div>
                    <div class="fun-li emoji">
                        <i class="icon iconfont icon-emoji"></i>
                        <div class="emoji-content" v-show="getEmoji">
                            <div class="emoji-tabs">
                                <div class="emoji-container" ref="emoji">
                                    <div class="emoji-block" :style="{width: Math.ceil(emoji.people.length / 5) * 48 + 'px'}">
                                        <span v-for="(item, index) in emoji.people" :key="index">{{item}}</span>
                                    </div>
                                    <div class="emoji-block" :style="{width: Math.ceil(emoji.nature.length / 5) * 48 + 'px'}">
                                        <span v-for="(item, index) in emoji.nature" :key="index">{{item}}</span>
                                    </div>
                                    <div class="emoji-block" :style="{width: Math.ceil(emoji.items.length / 5) * 48 + 'px'}">
                                        <span v-for="(item, index) in emoji.items" :key="index">{{item}}</span>
                                    </div>
                                    <div class="emoji-block" :style="{width: Math.ceil(emoji.place.length / 5) * 48 + 'px'}">
                                        <span v-for="(item, index) in emoji.place" :key="index">{{item}}</span>
                                    </div>
                                    <div class="emoji-block" :style="{width: Math.ceil(emoji.single.length / 5) * 48 + 'px'}">
                                        <span v-for="(item, index) in emoji.single" :key="index">{{item}}</span>
                                    </div>
                                </div>
                                <div class="tab">
                                    <!-- <a href="#hot"><span>热门</span></a>
                                    <a href="#people"><span>人物</span></a> -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--<div class="fun-li" @click="handleTips">
                        <i class="icon iconfont icon-zanshang"></i>
                    </div>-->
                    <div class="fun-li" @click="handleGithub">
                        <i class="icon iconfont icon-wenti"></i>
                    </div>
                </div>
                <div class="chat">
                    <div class="input" @keyup.enter="sendmessage">
                        <input id="msg_input" maxlength="100" type="text" v-model="chatValue">
                    </div>
                    <mu-button class="demo-raised-button" primary @click="sendmessage">发送</mu-button>
                </div>
                <input id="inputFile" name='inputFile' type='file' multiple='mutiple' accept="image/*;capture=camera" style="display: none" @change="fileup">
            </div>
        </div>
    </div>
</template>

<script type="text/ecmascript-6" scoped>
    import {mapGetters, mapState} from 'vuex';
    import {inHTMLData} from 'xss-filters-es6';
    import emoji from '../utils/emoji';
    import {setItem, getItem} from '../utils/localStorage';
    import {queryString} from '../utils/queryString';
    import {queryStringZh} from '../utils/queryString';
    import Message from '../components/Message';
    import loading from '../components/loading';
    import Alert from '../components/Alert';
    import debounce from 'lodash/debounce';
    import url from '../api/server';
    import {setTimeout} from 'timers';
    import ios from '../utils/ios';
    import socket from '../socket';
    import {HOST_NAME} from "../../const/index";

    export default {
        data() {
            const notice = getItem('notice') || {};
            const {noticeBar, noticeVersion} = notice;
            return {
                room_id: '',
                room_name: '聊天',
                container: {},
                chatValue: '',
                emoji: emoji,
                current: 1,
                openSimple: false,
                noticeBar: !!noticeBar,
                noticeList: [],
                noticeVersion: noticeVersion || '20181222',
                HOST_NAME:HOST_NAME,
            };
        },
        async created() {
            const room_id = queryString(window.location.href, 'room_id');
            this.room_name = queryStringZh(window.location.href, 'room_name');
            this.room_id = room_id;
            if (!room_id) {
                this.$router.push({path: '/'});
            }
            if (!this.user_id) {
                // 防止未登录
                this.$router.push({path: '/login'});
            }
            const data = {
                api_token: this.api_token
            };
            const res = await url.getNotice(data);
            // this.noticeList = [{"title":"欢迎您的到来，你可以自由发言"},{"href":"JavaScript:;"}];
            this.noticeList = res.data.noticeList;
            if (res.data.version !== res.data.version) {
                this.noticeBar = false;
            }
            this.noticeVersion = res.data.version;
        },
        async mounted() {
            loading.show();

            //进入页面获取历史消息
            setTimeout(async () => {

                // 微信 回弹 bug
                ios();
                this.container = document.querySelector('.chat-inner');
                // socket内部，this指针指向问题
                const that = this;
                //初始化房间信息，这里是清空了房间信息
                await this.$store.commit('setRoomDetailInfos');
                //初始化消息数量
                await this.$store.commit('setTotal', 0);

                const data = {
                    current: +this.current,//当前页，用于分页
                    room_id: this.room_id,//房间id
                    api_token: this.api_token
                };

                //进入页面首次获取历史聊天记录
                await this.$store.dispatch('getAllMessHistory', data);
                loading.hide();

                //滚动时获取聊天记录翻页
                this.container.addEventListener('scroll', debounce(async (e) => {

                    //获取当前的高度
                    const current_height1 = this.container.scrollHeight;
                    //e.target.scrollTop表示动了多少
                    if (e.target.scrollTop >= 0 && e.target.scrollTop < 50) {
                        this.$store.commit('setCurrent', +this.getCurrent + 1);
                        const data = {
                            current: +this.getCurrent,
                            room_id: this.room_id,
                            api_token: this.api_token
                        };
                        loading.show();
                        await this.$store.dispatch('getAllMessHistory', data);
                        loading.hide();
                        //获取完数据后的新高度
                        const current_height2 = this.container.scrollHeight;
                        //把页面滚动回刚才浏览到的位置
                        this.container.scrollTop = current_height2-current_height1-100;
                    }
                }, 50));


                // Emoji 表情图标点击后的处理
                this.$refs.emoji.addEventListener('click', function (e) {
                    var target = e.target || e.srcElement;
                    if (!!target && target.tagName.toLowerCase() === 'span') {
                        that.chatValue = that.chatValue + target.innerHTML;
                    }
                    e.stopPropagation();
                });

                const obj = {
                    user_id: this.user_id,
                    avatar: this.avatar,
                    room_id: this.room_id,
                    api_token: this.api_token
                };
                //对websocket服务器通道路由room发起请求
                socket.emit('room', obj);
                //监听服务端进入房间的返回消息
                socket.on('room', function (obj) {
                    that.$store.commit('setUsers', obj);
                });
                //监听服务端退出房间的返回消息
                socket.on('roomout', function (obj) {
                    that.$store.commit('setUsers', obj);
                });
            }, 1000);



        },
        methods: {
            handleNotice() {
                this.noticeBar = !this.noticeBar;
                setItem('notice', {
                    noticeBar: this.noticeBar,
                    noticeVersion: this.noticeVersion
                });
            },
            openSimpleDialog() {
                this.openSimple = true;
            },
            closeSimpleDialog() {
                this.openSimple = false;
            },
            handleGithub() {
                Alert({
                    content: 'https://github.com/codercwm/worldchat'
                });
            },
            handleTips() {
                Alert({
                    title: '请我喝杯咖啡',
                    html: '<div><img style="width: 200px" src="//'+this.HOST_NAME+'/img/hoster.jpg" /></div>'
                });
            },
            goback() {
                const obj = {
                    user_id: this.user_id,
                    room_id: this.room_id,
                    api_token: this.api_token,
                };
                socket.emit('roomout', obj);
                this.$router.goBack();
                this.$store.commit('setTab', true);
                this.$store.commit('setCurrent', 0);
            },
            setLog() {
                // 版本更新日志
            },
            fileup() {
                const that = this;
                const file1 = document.getElementById('inputFile').files[0];
                if (file1) {
                    const formdata = new window.FormData();
                    formdata.append('file', file1);
                    formdata.append('api_token', that.api_token);
                    formdata.append('room_id', that.room_id);
                    this.$store.dispatch('uploadImg', formdata);
                    const fr = new window.FileReader();
                    fr.onload = function () {
                        const obj = {
                            user_id: that.user_id,
                            avatar: that.avatar,
                            img: fr.result,
                            msg: '',
                            room_id: that.room_id,
                            time: new Date(),
                            api_token: that.api_token
                        };
                        socket.emit('message', obj);
                    };
                    fr.readAsDataURL(file1);
                } else {
                    console.log('必须有文件');
                }
            },
            imgupload() {
                const file = document.getElementById('inputFile');
                file.click();
            },
            sendmessage() {
                // 判断发送信息是否为空
                if (this.chatValue.trim() !== '') {
                    if (this.chatValue.length > 240) {
                        Alert({
                            content: '字数不能超过100'
                        });
                        document.getElementById("msg_input").blur();
                        return;
                    }
                    const msg = inHTMLData(this.chatValue); // 防止xss

                    const obj = {
                        user_id: this.user_id,
                        avatar: this.avatar,
                        img: '',
                        msg,
                        room_id: this.room_id,
                        time: new Date(),
                        api_token: this.api_token
                    };
                    // 传递消息信息
                    socket.emit('message', obj);
                    this.chatValue = '';
                } else {
                    Alert({
                        content: '内容不能为空'
                    });
                    document.getElementById("msg_input").blur();
                }
            }
        },
        computed: {
            ...mapGetters([
                'getEmoji',
                'getInfos',
                'getUsers',
                'getCurrent'
            ]),
            ...mapState([
                'isbind'
            ]),
            ...mapState({
                user_id: state => state.userInfo.user_id,
                avatar: state => state.userInfo.avatar,
                api_token: state => state.userInfo.api_token,
            })
        },
        components: {
            Message
        }
    };
</script>
<style lang="scss" scoped>
    @import "./Chat";
</style>
