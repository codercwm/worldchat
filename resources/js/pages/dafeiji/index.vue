
    <style>
        body{
            width: 100%;
            height: 100%;
            margin: 0;
            padding: 0;
            overflow:hidden;
        }
        #game{
            width:100%;
            height:100%;
            position:absolute;
        }
        #backgroundImg{
            width:100%;
            position: absolute;
            bottom: 0;
        }
        .player{
            position:absolute;
            bottom:0;
            left: 50%;
            width:10%;
            height: 10%;
        }
        #shade{
            width:100%;
            height:100%;
            position:absolute;
            background: #ccc;
            z-index: 999999;
            opacity: 0.8;
        }
        #button{
            margin-top:20%;
            text-align: center;
        }
        .eshot{
            width: 1%;
            height: 2%;
        }
        .enemy{
            width:10%;
            height: 10%;
        }
        #dazhaoButton{
            position: absolute;
            bottom:0;
            right: 0;
            width: 30px;
            height: 45px;
            font-size:10px;
            text-align: center;
            background: #ccc;
            margin: 10px 10px;
        }

    </style>
<template>
    <div>
        <div id='shade' style="">
            <div v-show="showStart" id='button'>
                <button @click="start">开始游戏</button>
                <button @click="invite">邀请好友一起玩</button>
            </div>
        </div>
        <img id="backgroundImg" src="./images/bg2.jpg">

        <div id="game">
            <!-- 分数 -->
            <span style="right:0;position:absolute;font-size:30px;color:#eee;">0</span>
            <!-- 子弹 -->
            <img src="./images/eshot.png" id='eshot0' class='eshot' style="display:none;position:absolute;">
            <img src="./images/eshot.png" id='eshot1' class='eshot' style="display:none;position:absolute;">
            <img src="./images/eshot.png" id='eshot2' class='eshot' style="display:none;position:absolute;">
            <img src="./images/eshot.png" id='eshot3' class='eshot' style="display:none;position:absolute;">
            <img src="./images/eshot.png" id='eshot4' class='eshot' style="display:none;position:absolute;">
            <img src="./images/eshot.png" id='eshot5' class='eshot' style="display:none;position:absolute;">
            <img src="./images/eshot.png" id='eshot6' class='eshot' style="display:none;position:absolute;">
            <img src="./images/eshot.png" id='eshot7' class='eshot' style="display:none;position:absolute;">
            <img src="./images/eshot.png" id='eshot8' class='eshot' style="display:none;position:absolute;">
            <img src="./images/eshot.png" id='eshot9' class='eshot' style="display:none;position:absolute;">
            <img src="./images/eshot.png" id='eshot10' class='eshot' style="display:none;position:absolute;">
            <img src="./images/eshot.png" id='eshot11' class='eshot' style="display:none;position:absolute;">
            <img src="./images/eshot.png" id='eshot12' class='eshot' style="display:none;position:absolute;">
            <img src="./images/eshot.png" id='eshot13' class='eshot' style="display:none;position:absolute;">
            <img src="./images/eshot.png" id='eshot14' class='eshot' style="display:none;position:absolute;">
            <img src="./images/eshot.png" id='eshot15' class='eshot' style="display:none;position:absolute;">
            <img src="./images/eshot.png" id='eshot16' class='eshot' style="display:none;position:absolute;">
            <img src="./images/eshot.png" id='eshot17' class='eshot' style="display:none;position:absolute;">
            <img src="./images/eshot.png" id='eshot18' class='eshot' style="display:none;position:absolute;">
            <img src="./images/eshot.png" id='eshot19' class='eshot' style="display:none;position:absolute;">
            <img src="./images/eshot.png" id='eshot20' class='eshot' style="display:none;position:absolute;">

            <!-- 敌机 -->
            <img src="./images/e1.png" id="e0" class='enemy' style="display:none;position:absolute;">
            <img src="./images/e2.png" id="e1" class='enemy' style="display:none;position:absolute;">
            <img src="./images/e3.png" id="e2" class='enemy' style="display:none;position:absolute;">
            <img src="./images/e1.png" id="e3" class='enemy' style="display:none;position:absolute;">
            <img src="./images/e2.png" id="e4" class='enemy' style="display:none;position:absolute;">
            <img src="./images/e3.png" id="e5" class='enemy' style="display:none;position:absolute;">
            <img src="./images/e1.png" id="e6" class='enemy' style="display:none;position:absolute;">
            <img src="./images/e2.png" id="e7" class='enemy' style="display:none;position:absolute;">
            <img src="./images/e3.png" id="e8" class='enemy' style="display:none;position:absolute;">
            <img src="./images/e1.png" id="e9" class='enemy' style="display:none;position:absolute;">
            <img src="./images/e2.png" id="e10" class='enemy' style="display:none;position:absolute;">
            <img src="./images/e3.png" id="e11" class='enemy' style="display:none;position:absolute;">

            <!-- 玩家 -->
            <!--<img src="./images/player.png" id="player" class="player" />
            <img src="./images/player.png" id="player2" class="player" />-->
        </div>
        <div id='dazhaoButton'>
            <span>大招(F)</span>
            <span id='canDazhao'></span>/<span id="canDazhaoIni"></span>
        </div>
        <img src="./images/boom.gif" style="display: none;" />
    </div>
</template>
<script>

    import socket from "../../socket";
    import {queryString,queryStringChange} from "../../utils/queryString";
    import InputConfirm from "../../components/InputConfirm";
    import {mapState} from "vuex";
    import loading from '../../components/loading';
    import Alert from "../../components/Alert";
    import {PROTOCOL_HOST_NAME} from "../../../const/index";

    export default {
        data() {
            return {
                startFun:null,
                room_id:null,
                readyTimer:null,
                showStart:false,
            };
        },
        computed: {
            ...mapState({
                user_id: state => state.userInfo.user_id,
                avatar: state => state.userInfo.avatar,
                api_token: state => state.userInfo.api_token,
                nickname: state => state.userInfo.nickname,
            })
        },
        methods: {
            start(){
                this.startFun();
            },
            invite(){
                const url = PROTOCOL_HOST_NAME+'/#/dafeiji?room_id='+this.room_id;
                Alert({
                    title:'复制链接发送给好友，一起打飞机',
                    content: url
                });
            },
            roomOut(){
                const obj = {
                    user_id: this.user_id,
                    room_id: this.room_id,
                    api_token: this.api_token,
                    nickname: this.nickname,
                };
                socket.emit('roomout', obj);
            },
        },
        beforeDestroy() {
            window.removeEventListener('beforeunload', this.roomOut,true);
            this.roomOut();
        },
        async mounted(){
            window.addEventListener('beforeunload', this.roomOut,true);
            const room_id = queryString(window.location.href, 'room_id');
            if(room_id){
                this.room_id = room_id;
            }else{
                this.room_id = 'fr'+(((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1);
                window.history.pushState(null,null,queryStringChange(window.location.href, 'room_id',this.room_id));//向当前url添加参数
            }

            const vueThis = this;
            const playerArr = {};

            if (!this.user_id) {
                const res = await InputConfirm({
                    title: "输入您的名字"
                });
                if (res.res === "submit") {
                    this.$store.commit("setUserInfo", {
                        type: "user_id",
                        value: 'ui'+(((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1)
                    });
                    this.$store.commit("setUserInfo", {
                        type: "nickname",
                        value: res.content
                    });
                }else{
                    this.$router.push({path: '/'});
                }

            }

            loading.show();
            this.readyTimer = setInterval(async (data) => {
                if (document.readyState === 'complete') {
                    //进入房间
                    const obj = {
                        user_id: vueThis.user_id,
                        avatar: vueThis.avatar,
                        room_id: vueThis.room_id,
                        api_token: vueThis.api_token,
                        nickname: vueThis.nickname,
                    };
                    //对websocket服务器通道路由room发起请求
                    socket.emit('roomin', obj);
                    //监听服务端进入房间的返回消息
                    socket.on('roomin', function (obj) {
                        vueThis.$store.commit('setUsers', obj);
                        for( var user_id in obj){
                            if(!(user_id in playerArr)){
                                var player = document.createElement('img');
                                player.src = './images/player.png';
                                player.className = 'player';
                                player.id = 'player_id'+user_id;
                                game.appendChild(player);
                                playerArr[user_id] = new main(player.id);
                            }
                        }
                    });
                    //监听服务端退出房间的返回消息
                    socket.on('roomout', function (obj) {
                        vueThis.$store.commit('setUsers', obj);
                    });
                    loading.hide();
                    clearInterval(vueThis.readyTimer);
                    vueThis.readyTimer = null;
                    vueThis.showStart = true;

                }

            },5000);

            //存够10个可以放大招
            var canDazhaoIni = 10;
            var canDazhao = 0;
            document.getElementById('canDazhao').innerHTML = canDazhao;
            document.getElementById('canDazhaoIni').innerHTML = canDazhaoIni;
            //分数
            var fenshu = 0;
            //背景移动速度
            var bgSpeedIni = 5;
            var bgSpeed = bgSpeedIni;
            //敌机移动速度
            var enemySpeedIni = 5;
            var enemySpeed = enemySpeedIni;
            //炮弹移动速度
            var bulletSpeedIni = 10;
            var bulletSpeed = bulletSpeedIni;
            var bulletNum = document.getElementsByClassName('eshot').length;
            var enemyNum = document.getElementsByClassName('enemy').length;

            //发送位置的频聊，为了减少移动时对服务器的请求
            var moveSendStep = 0;
            var moveSendStepIni = 5;//移动5次之后才像服务器发送位置

            // 窗口
            var game = document.getElementById('game');
            var windowHeight = game.clientHeight;
            var windowWidth = game.clientWidth;

            // 背景图
            var backgroundImg = document.getElementById('backgroundImg');

            var backgroundImgPosition = 0;

            //大招进行中
            var doingDazhao = null;
            var dazhaoEshotRight = null;
            var dazhaoEshotLeft = null;
            var checkDazhaoEshot = null;
            var dazhaoEshotFashe = null;

            //战机操作类
            function main(playerId){
                this.player = document.getElementById(playerId);
                //战机的宽度
                this.playerWidth = this.player.width;
                //战机的长度
                this.playerHeight = this.player.height;

                //设置战机位置
                this.position = function(x,y){
                    this.player.style.left = x+'px';
                    this.player.style.top = y+'px';
                }

                //战机开火
                this.doFire = function(){
                    var x = this.player.offsetLeft+(this.playerWidth/2);
                    var y = this.player.offsetTop-(this.playerHeight/4);

                    //循环获取一颗空闲的子弹，把它放在飞机的前面
                    for(var i=0;i<bulletNum;i++)
                    {
                        var eshot = document.getElementById('eshot'+i);
                        if(eshot.style.display=='none')
                        {
                            eshot.style.top = y+'px';
                            eshot.style.left = x+'px';
                            eshot.style.display = 'block';
                            //获取到后即return
                            return;
                        }
                    }
                }

                //战机放大招
                this.doDazhao = function(){

                    //不能和队友同时放大招
                    if(null!=doingDazhao){
                        return;
                    }

                    var x = this.player.offsetLeft+(this.playerWidth/2);
                    var y = this.player.offsetTop-(this.playerHeight/4);

                    //放大招，时间暂定
                    bulletSpeed = 0;
                    enemySpeed = 0;
                    bgSpeed = 0;

                    //把颗炮弹全部召唤出来
                    var eshArr = new Array(bulletNum);

                    for(var i=0;i<bulletNum;i++)
                    {
                        var eshot = document.getElementById('eshot'+i);
                        eshArr[i] = eshot;
                        eshot.style.left = x+'px';
                        eshot.style.top = y+'px';
                        eshot.style.display = 'block';
                    }

                    var that = this;
                    //炮弹出现之后，一秒钟之后分散

                    doingDazhao = setTimeout(function(){

                        var dazhaoFashe1 = false;
                        var dazhaoFashe2 = false;

                        var forNum = parseInt(bulletNum/2);

                        //子弹向右边分散
                        dazhaoEshotRight = setInterval(function(){
                            var j = 1;
                            for(var i=1;i<=forNum;i++){
                                eshArr[i].style.left = eshArr[i].offsetLeft+j+'px';
                                j++;
                            }

                            if(eshArr[forNum].offsetLeft>(windowWidth-20))
                            {
                                clearInterval(dazhaoEshotRight);
                                dazhaoEshotRight = null;
                                dazhaoFashe1 = true;
                            }
                        },10);

                        //子弹向左边分散
                        dazhaoEshotLeft = setInterval(function(){
                            var j = 1;
                            for(var i=forNum+1;i<=bulletNum-1;i++){
                                eshArr[i].style.left = eshArr[i].offsetLeft-j+'px';
                                j++;
                            }

                            if(eshArr[bulletNum-1].offsetLeft<20)
                            {
                                clearInterval(dazhaoEshotLeft);
                                dazhaoEshotLeft = null;
                                dazhaoFashe2 = true;
                            }

                        },10);

                        //子弹去全部分散开之后才发射出去
                        checkDazhaoEshot = setInterval(function(){
                            if(dazhaoFashe1 && dazhaoFashe1 && (dazhaoEshotFashe==null)){

                                setTimeout(function(){
                                    dazhaoEshotFashe = setInterval(function(){
                                        for(var bb=0;bb<bulletNum;bb++)
                                        {
                                            var eshotD = document.getElementById('eshot'+bb);
                                            eshotD.style.top = eshotD.offsetTop-20+'px';
                                            checkHit(eshotD,true);
                                            if(eshotD.offsetTop<0)
                                            {
                                                eshotD.style.display = 'none';
                                                enemySpeed = enemySpeedIni;
                                                bulletSpeed = bulletSpeedIni;
                                                bgSpeed = bgSpeedIni;
                                                clearTimeout(doingDazhao);
                                                doingDazhao = null;
                                                clearInterval(checkDazhaoEshot);
                                                checkDazhaoEshot = null;
                                                clearInterval(dazhaoEshotFashe);
                                                dazhaoEshotFashe = null;
                                                dazhaoFashe1 = false;
                                                dazhaoFashe2 = false;
                                            }
                                        }
                                    },10);
                                },500);

                            }

                        },500);

                    },1000);
                }

                //传入一个敌机，检测被敌机撞
                this.collision = function(enemy){
                    //自己位置
                    var playerX = this.player.offsetLeft;
                    var playerY = this.player.offsetTop;

                    var playerWidth = this.player.width;
                    var playerHeight = this.player.height;

                    //敌机位置
                    var enemyX = enemy.offsetLeft;
                    var enemyY = enemy.offsetTop;

                    var enemyWidth = enemy.width;
                    var enemyHeight = enemy.height;

                    //碰撞检测
                    if(playerY<enemyY+enemyHeight && (playerX+playerWidth)>enemyX && playerX<(enemyX+enemyWidth)/* && playerY>enemyY-enemyHeight*/)
                    {
                        /*this.player.style.display = 'none';
                        enemy.style.display = 'none';

                        //爆炸
                        var blowUp = document.createElement('img');
                        blowUp.src = './images/boom.gif';
                        blowUp.style.position = 'absolute';
                        blowUp.style.top = (playerY)+'px';
                        blowUp.style.left = (playerX)+'px';
                        blowUp.width = playerWidth*2;
                        blowUp.height = playerHeight*2;
                        game.appendChild(blowUp);
                        alert('游戏结束，你的分数：'+fenshu);
                        window.location.reload();*/
                    }
                }

            }

            //设置可以放大招的数量
            function setCanDazhao(num){
                canDazhao = canDazhao+num;
                document.getElementById('canDazhao').innerHTML = canDazhao;
            }

            //获取玩家
            // playerArr[vueThis.user_id] = new main('player');
            var player = document.createElement('img');
            player.src = './images/player.png';
            player.className = 'player';
            player.id = 'player_id'+vueThis.user_id;
            game.appendChild(player);
            playerArr[vueThis.user_id] = new main(player.id);

            //接收服务器给的敌机id，出现敌机
            function showEnemy(i,enemy_x)
            {
                // alert(i);
                var enemy = document.getElementById('e'+i);
                var enemyHeight = enemy.height;

                if(enemy.style.display=='none')
                {
                    enemy.style.top = (0-enemyHeight)+'px'
                    enemy.style.left = enemy_x+'px';
                    enemy.style.display = 'block';
                }
            }

            //解决爆炸的敌机不消失问题
            //打中的敌机数组，把爆炸保存在数组中进行删除
            var blowUpEnemy = new Array(enemyNum);

            //检测是否击中
            function checkHit(eshot,big=false)
            {
                //子弹位置
                var eshotX = eshot.offsetLeft;
                var eshotY = eshot.offsetTop;

                for(var i=0;i<enemyNum;i++)
                {
                    var enemy = document.getElementById('e'+i);
                    if(enemy.style.display=='block')
                    {
                        //敌机位置
                        var enemyX = enemy.offsetLeft;
                        var enemyY = enemy.offsetTop;

                        var enemyHeight = enemy.height;
                        var enemyWidth = enemy.width;

                        //判断碰撞
                        if(eshotY<enemyY+enemyHeight && eshotX>enemyX && eshotX<enemyX+enemyWidth && eshotY>enemyY-enemyHeight)
                        {
                            var varname = (((1 + Math.random()) * 0x10000) | 0).toString(16).substring(1)+''+i;

                            blowUpEnemy[varname] = document.createElement('img');
                            blowUpEnemy[varname].src = './images/boom.gif';
                            blowUpEnemy[varname].width = enemy.width+50;
                            blowUpEnemy[varname].height = enemy.height+50;
                            blowUpEnemy[varname].style.position = 'absolute';
                            blowUpEnemy[varname].style.top = enemyY+'px';
                            blowUpEnemy[varname].style.left = enemyX-20+'px';
                            game.appendChild(blowUpEnemy[varname]);
                            setTimeout(function(){
                                for(var k in blowUpEnemy){
                                    game.removeChild(blowUpEnemy[k]);
                                    delete blowUpEnemy[k];
                                }
                            },500);

                            enemy.style.display = 'none';
                            //如果不是大招，一颗炮弹只能打一架飞机
                            if(!big){
                                eshot.style.display = 'none';
                            }

                            fenshu += 1;
                            setCanDazhao(1);
                            document.getElementsByTagName('span')[0].innerHTML = fenshu;
                            if(fenshu>3000)
                            {
                                alert('通关了，你的分数：'+fenshu);
                                window.location.reload();
                                //backgroundImg.style.src='./images/bg3.jpg)';
                                //fenshu = 0;
                            }


                        }
                    }
                }
            }

            socket.on('dafeiji', async (data) => {
                showEnemy(data.enemy_id,data.enemy_x);
                //开火
                for( var player_user_id in playerArr){
                    playerArr[player_user_id].doFire();
                }
            });

            //循环判断子弹和敌机，如果是显示状态的，就对其进行移动
            setInterval(function(){

                for(var i=0;i<bulletNum;i++)
                {
                    var eshot = document.getElementById('eshot'+i);
                    //循环所有子弹，如果识别到子弹是显示状态，就进行发射
                    if(eshot.style.display=='block')
                    {
                        checkHit(eshot);
                        eshot.style.top = eshot.offsetTop-bulletSpeed+'px';
                        if(eshot.offsetTop<0)
                        {
                            eshot.style.display = 'none';
                        }
                    }
                }

                //移动敌机
                for(var i=0;i<enemyNum;i++)
                {
                    var enemy = document.getElementById('e'+i);
                    //循环所有敌机，如果是显示状态，就进行移动
                    if(enemy.style.display=='block')
                    {
                        for( var player_user_id in playerArr) {
                            playerArr[player_user_id].collision(enemy);
                        }
                        enemy.style.top = (enemy.offsetTop+enemySpeed)+'px';
                        //如果已经飞出窗口了，就对它进行隐藏
                        if(enemy.offsetTop>windowHeight)
                        {
                            enemy.style.display = 'none';
                        }
                    }
                }
            },20);

            //监听队友移动
            socket.on('dafeiji_position', async (data) => {
                if(data.user_id!=vueThis.user_id){
                    playerArr[data.user_id].position(data.position_x,data.position_y);
                }
            });

            //监听队友大招
            socket.on('dafeiji_dazhao', async (data) => {
                if(data.user_id!=vueThis.user_id){
                    playerArr[data.user_id].doDazhao();
                }
            });

            function moveSend(selfplanX,selfplanY) {
                playerArr[vueThis.user_id].position(selfplanX,selfplanY)

                if(moveSendStep<moveSendStepIni){
                    moveSendStep+=1;
                    return;
                }else{
                    moveSendStep = 0;
                }
                //把战机位置告诉服务器
                const dafeijiPosition = {
                    user_id:vueThis.user_id,
                    position_x:selfplanX,
                    position_y:selfplanY,
                    room_id:vueThis.room_id,
                };
                socket.emit('dafeiji_position', dafeijiPosition);
            }

            this.startFun = function(){
                document.getElementById('shade').style.display = 'none';

                // 背景图片移动
                setInterval(function(){
                    backgroundImgPosition -= bgSpeed;
                    //图片是两张重复的图片合在一起，当滚动到中间位置的时候复位，实现连续滚动
                    if(backgroundImgPosition< (0-(backgroundImg.height/2)))
                    {
                        backgroundImgPosition=0;
                    }
                    backgroundImg.style.bottom=backgroundImgPosition+'px';
                },20);

                //移动
                game.addEventListener("mousemove",function(){

                    var oevent=window.event||arguments[0];
                    var selfplanX=oevent.clientX-(playerArr[vueThis.user_id].playerWidth/2);
                    var selfplanY=oevent.clientY-(playerArr[vueThis.user_id].playerHeight/2);

                    moveSend(selfplanX,selfplanY);
                },true);

                game.addEventListener("touchmove", function(e) {

                    if(e.touches.length > 1 || e.scale && e.scale !== 1) return;
                    var selfplanX=e.touches[0].pageX-(playerArr[vueThis.user_id].playerWidth/2);
                    var selfplanY=e.touches[0].pageY-(playerArr[vueThis.user_id].playerHeight/2);
                    moveSend(selfplanX,selfplanY);
                },true);

                document.onkeyup = function(ee)
                {
                    var eve = ee || window.event;
                    //发射大招
                    if(eve.keyCode==70)
                    {
                        if(canDazhao>=canDazhaoIni){
                            playerArr[vueThis.user_id].doDazhao();
                            setCanDazhao(-canDazhaoIni);

                            socket.emit('dafeiji_dazhao', {user_id:vueThis.user_id,room_id:vueThis.room_id});
                        }
                    }
                }

                //发射大招
                document.getElementById('dazhaoButton').addEventListener("click",function(){
                    if(canDazhao>=canDazhaoIni){
                        playerArr[vueThis.user_id].doDazhao();
                        setCanDazhao(-canDazhaoIni);
                        socket.emit('dafeiji_dazhao', {user_id:vueThis.user_id,room_id:vueThis.room_id});
                    }
                },true);

                //叫服务器返回一个敌机id
                setInterval(function(){
                    var enemy_id = Math.floor(Math.random()*100000%enemyNum);
                    var enemyLeft = windowWidth-document.getElementById('e1').width;
                    var enemy_x = Math.ceil(Math.random()*10000%enemyLeft);
                    const dafeijiObj = {
                        room_id: vueThis.room_id,
                        enemy_id:enemy_id,
                        enemy_x:enemy_x,
                    };
                    socket.emit('dafeiji', dafeijiObj);
                },1000);


            }//startFun结束

        }
    }
</script>
