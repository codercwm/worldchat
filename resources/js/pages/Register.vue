<template>
    <div class="register">
        <div class="header"></div>
        <div class="content">
            <form action="" name="form1">
                <mu-text-field v-model="email" label="邮箱" label-float icon="account_circle" full-width></mu-text-field>
                <br/>
                <mu-text-field v-model="password" type="password" label="密码" label-float icon="locked" full-width></mu-text-field>
                <br/>
                <div class="btn-radius" @click="submit">注册</div>
            </form>
            <div @click="login" class="tip-user">
                我已有帐号
            </div>
        </div>
    </div>
</template>

<script type="text/ecmascript-6" scoped>
    import {mapState} from "vuex";
    import SvgModal from "../components/svg-modal";
    import Alert from "../components/Alert";
    import Toast from "../components/Toast";
    import ios from '../utils/ios';
    import socket from '../socket';

    export default {
        data() {
            return {
                email: "",
                password: ""
            };
        },
        methods: {
            async submit() {
                const email = this.email.trim();
                const password = this.password.trim();
                const avatar = '';
                if (email !== "" && password !== "") {
                    const data = {
                        email: email,
                        password: password,
                        avatar: avatar
                    };
                    const res = await this.$store.dispatch("registerSubmit", data);
                    if (res.status === "success") {
                        Toast({
                            content: res.data.msg,
                            timeout: 1000,
                            background: "#137055"
                        });
                        this.$store.commit("setUserInfo", {
                            type: "user_id",
                            value: res.data.data.id
                        });
                        this.$store.commit("setUserInfo", {
                            type: "nickname",
                            value: res.data.data.nickname
                        });
                        this.$store.commit("setUserInfo", {
                            type: "api_token",
                            value: res.data.data.api_token
                        });
                        this.$store.commit("setUserInfo", {
                            type: "avatar",
                            value: res.data.data.avatar
                        });
                        this.getSvgModal.$root.$options.clear();
                        this.$store.commit("setSvgModal", null);
                        this.$router.push({path: "/"});
                        location.reload();
                    } else {
                        await Alert({
                            content: res.data.msg
                        });
                    }
                } else {
                    Alert({
                        content: "邮箱或密码不能为空"
                    });
                }
            },
            login() {
                this.$router.push({path: "login"});
            }
        },
        mounted() {
            // 微信 回弹 bug
            ios();
            this.$store.commit("setTab", false);
            if (!this.svgmodal) {
                const svg = SvgModal();
                this.$store.commit("setSvgModal", svg);
            }
        },
        computed: {
            ...mapState(["svgmodal"]),
            getSvgModal() {
                return this.$store.state.svgmodal;
            }
        }
    };
</script>

<style lang="stylus" rel="stylesheet/stylus">
    .btn-radius {
        width: 100%;
        height: 40px;
        margin-top: 20px;
        border: 1px solid rgba(255, 255, 255, 0.38);
        background: rgba(255, 255, 255, 0.02);
        color: #fff;
        line-height: 40px;
        text-align: center;
        border-radius: 50px;
    }

    .header {
        height: 50px;
    }

    .content {

    .mu-text-field-input {
        color: #fff;
    }

    .mu-text-field.has-label .mu-text-field-label.float {
        color: rgba(255, 255, 255, 0.38);
    }

    .mu-text-field-label {
        color: #fff;
    }

    .mu-text-field-line {
        background-color: rgba(255, 255, 255, 0.38);
    }

    .mu-text-field-focus-line {
        background-color: #fff;
    }

    .tip-user {
        width: 100%;
        text-align: center;
        margin-top: 20px;
        color: #fff;
    }

    }

    .register {
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        background-image: url('//worldchat.test/img/bg.jpg');
        background-size: 100% 100%;
        background-position: center center;

    .mu-appbar {
        text-align: center;

    .mu-flat-button-label {
        font-size: 20px;
    }

    }

    .content {
        width: 80%;
        margin: 70px auto 20px;

    .mu-text-field {
        width: 100%;
    }

    }
    }
</style>
