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

var start = function(){
    document.getElementById('shade').style.display = 'none';

    // 窗口
    var game = document.getElementById('game');
    var windowHeight = game.clientHeight;
    var windowWidth = game.clientWidth;

    // 背景图
    var backgroundImg = document.getElementById('backgroundImg');

    var backgroundImgPosition = 0;
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
                this.player.style.display = 'none';
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
                window.location.reload();
            }
        }

    }

    //设置可以放大招的数量
    function setCanDazhao(num){
        canDazhao = canDazhao+num;
        document.getElementById('canDazhao').innerHTML = canDazhao;
    }

    //获取玩家
    var player1 = new main('player');
    
    //移动
    game.addEventListener("mousemove",function(){
            var oevent=window.event||arguments[0];
            var chufa=oevent.srcElement||oevent.target;
            var selfplanX=oevent.clientX-(player1.playerWidth/2);
            var selfplanY=oevent.clientY-(player1.playerHeight/2);
            player1.position(selfplanX,selfplanY)
        
    },true);
    
    
    document.onkeyup = function(ee)
    {
        var eve = ee || window.event;
        //发射大招
        if(eve.keyCode==70)
        {
            if(canDazhao>=canDazhaoIni){
                player1.doDazhao();
                setCanDazhao(-canDazhaoIni);
            }
        }
    }

    //发射大招
    document.getElementById('dazhaoButton').addEventListener("click",function(){
        if(canDazhao>=canDazhaoIni){
            player1.doDazhao();
            setCanDazhao(-canDazhaoIni);
        }
    },true);
    
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
                player1.collision(enemy);
                player2.collision(enemy);
                enemy.style.top = (enemy.offsetTop+enemySpeed)+'px';
                //如果已经飞出窗口了，就对它进行隐藏
                if(enemy.offsetTop>windowHeight)
                {
                    enemy.style.display = 'none';
                }
            }
        }
    },20);
    

    //接收服务器给的敌机id，出现敌机
    function showEnemy(i)
    {
        // alert(i);
        var enemy = document.getElementById('e'+i);
        var enemyHeight = enemy.height;

        var enemyLeft = windowWidth-enemy.width;

        if(enemy.style.display=='none')
        {   
            enemy.style.top = (0-enemyHeight)+'px'
            enemy.style.left = Math.ceil(Math.random()*10000%enemyLeft)+'px';
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



    //获取队友
    var player2 = new main('player2');


    /*模拟服务器返回*/

    //模拟服务器返回一个敌机id
    setInterval(function(){
        
        var i = Math.floor(Math.random()*100000%enemyNum);
        showEnemy(i);

        //开火
        player1.doFire();
        player2.doFire();
    },500);

    //模拟服务器返回的友机操作
    setInterval(function(){
        var key = Math.floor(Math.random()*100000%5);
        switch(key)
        {
            case 1:

            player2.position(Math.max(0,player2.player.offsetLeft-10),player2.player.offsetTop);
            break;
            case 2:

            player2.position(player2.player.offsetLeft,Math.max(0,player2.player.offsetTop-10));
            break;
            case 3:

            player2.position(Math.min(windowWidth,player2.player.offsetLeft+10),player2.player.offsetTop);
            break;
            case 4:
            
            player2.position(player2.player.offsetLeft,Math.min(windowHeight,player2.player.offsetTop+10));
            break;
            case 0:
            // player2.doDazhao();  
            break;
        }
    },100);

    //模拟服务器返回的队友放大招
    document.onkeydown = function(ee)
    {
        var eve = ee || window.event;
        if(eve.keyCode==82)
        {
            player2.doDazhao();

        }
    }
}