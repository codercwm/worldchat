<template>
    <div class="login">
        <div class="header"></div>
        <div class="content">
            <form action="" name="form2">
                <mu-text-field v-model="email" label="账号" label-float icon="account_circle" full-width></mu-text-field>
                <br/>
                <mu-text-field v-model="password" type="password" label="密码" label-float icon="locked" full-width></mu-text-field>
                <br/>
                <div class="btn-radius" @click="submit">登录</div>
            </form>
            <div @click="register" class="tip-user">注册帐号</div>
        </div>
    </div>
</template>

<script type="text/ecmascript-6">
    import SvgModal from "../components/svg-modal";
    import Alert from "../components/Alert";
    import Toast from "../components/Toast";
    import ios from '../utils/ios';

    export default {
        data() {
            return {
                loading: "",
                email: "",
                password: ""
            };
        },
        methods: {
            async submit() {
                const email = this.email.trim();
                const password = this.password.trim();
                if (email !== "" && password !== "") {
                    const data = {
                        email: email,
                        password: password
                    };
                    const res = await this.$store.dispatch("loginSubmit", data);
                    if (res.status === "success") {
                        Toast({
                            content: res.data.msg,
                            timeout: 1000
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
                        Alert({
                            content: res.data.msg
                        });
                    }
                    document.form2.reset();
                } else {
                    Alert({
                        content: "邮箱或密码不能为空"
                    });
                }
            },
            register() {
                this.$router.push({path: "register"});
            }
        },
        mounted() {
            // 微信 回弹 bug
            ios();
            this.$store.commit("setTab", false);
            if (!this.getSvgModal) {
                const svg = SvgModal();
                this.$store.commit("setSvgModal", svg);
            }
            //      Loading.show()
        },
        computed: {
            getSvgModal() {
                return this.$store.state.svgmodal;
            }
        }
    };
</script>
<style lang="scss" scoped>
    @import '../../const/index.scss';

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

    .login {
        position: absolute;
        left: 0;
        right: 0;
        top: 0;
        bottom: 0;
        background: $BACKGROUND_COLOR;
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

            .tip-user {
                width: 100%;
                text-align: center;
                margin-top: 20px;
                color: #fff;
            }

            .mu-text-field {
                width: 100%;
            }

            .mu-raised-button {
                min-width: 80px;
                display: block;
                width: 100%;
                margin: 0 auto;
                transition: all 0.375s;

                &.loading {
                    width: 80px;
                    height: 80px;
                    border-radius: 50%;
                }
            }
        }
    }
</style>
