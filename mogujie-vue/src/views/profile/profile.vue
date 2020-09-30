<template>
    <div>
        <head-app></head-app>
        <div class="headImgAround"></div>
        <!--用户信息-->
        <div class="headImg">
            <div class="img">
                <img :src="userImg" @click="goProfileInfo"/>
            </div>
            <div class="name">{{userName}}</div>
            <div class="boxMember"></div>
        </div>
        <!--应用区-->
        <div class="application">
            <div>
                <img src="https://s10.mogucdn.com/mlcdn/c45406/190815_7a9da7lh4a96lb1fl6b4gb854f3ji_75x75.png" alt="">
                <p>主页</p>
            </div>
            <div><img src="https://s10.mogucdn.com/mlcdn/c45406/190815_6f41b17399aha0flla6chjb0lhk47_75x75.png"
                      alt="">
                <p>消息</p>
            </div>
            <div><img src="https://s10.mogucdn.com/mlcdn/c45406/190815_31g4a5ebc8bj2f85cj4li8df89aab_75x75.png"
                      alt="">
                <p>收藏</p>
            </div>
            <div><img src="https://s10.mogucdn.com/mlcdn/c45406/190815_0k9k5gh362a1kbk87hha8ff1bek69_75x75.png"
                      alt="">
                <p>关注</p>
            </div>
        </div>
        <!--购物车-->
        <div class='cart'>
            <div class='cart-left'>
                <div>购物车</div>
            </div>
            <div class='cart-right'>
                <div>卡卷红包<span class="iconfont icon-qianjin"></span></div>
                <div>补贴保障<span class="iconfont icon-qianjin"></span></div>
                <div class='r1'>我的钱包<span class="iconfont icon-qianjin"></span></div>
            </div>
        </div>
        <!--订单-->
        <div class="order">
            <div class="order-top">
                <div>我的订单</div>
                <span class='iconfont icon-qianjin'></span>
            </div>
            <div class="order-bottom">
                <div v-for="(item,index) in orderInfo" @click="goProfileOrder(index)">
                    <img :src="item.img" alt="" />
                    <p>{{item.text}}</p>
                    <div class="numIcon" v-show="item.num">{{item.num}}</div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import headApp from "./head"
    export default {
        name: "profile",
        components:{
            headApp
        },
        created(){
            this.getUserInfo();
            this.getUserOrder()
        },
        data(){
            return {
                userName: '',
                userImg: '',
                orderInfo:[]
            }
        },
        methods:{
            async getUserOrder(){
                const result = await this.$http({
                    method:"post",
                    url:"index/test_ela/getUserOrder",
                    headers:{
                        'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'
                    },
                    data:this.$qs.stringify({user_id:this.$user_id()})
                });
                console.log(result)
            },
            async getUserInfo(){
                const result = await this.$http({
                    method:"post",
                    url:"index/test_ela/getUserInfo",
                    headers:{
                        'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'
                    },
                    data:this.$qs.stringify({user_id:this.$user_id()})
                });
                let user = result.data.data;
                this.userName = user.userName
                this.userImg = user.userImg
            },
            goProfileInfo(){
                this.$router.push("/profileInfo")
            },
        }
    }
</script>

<style scoped>
    .profile {
        background: rgb(246, 246, 246);
    }
    .headImgAround {
        height: 70px;
        background: rgb(255, 68, 102);
        border-bottom-left-radius: 50px;
        border-bottom-right-radius: 50px;
    }
    .headImg {
        width: 335px;
        height: 120px;
        margin-left: 20px;
        background: white;
        position: relative;
        top: -20px;
        border-radius: 10px;
    }
    .img {
        position: absolute;
        background: white;
        width: 60px;
        height: 60px;
        border-radius: 100%;
        top: -30px;
        left: 138px;
        overflow: hidden;
        color: black;
    }
    .img img {
        width: 55px;
        height: 55px;
        border-radius: 100%;
        margin-left: 2.5px;
        margin-top: 2.5px;
    }
    .name {
        position: absolute;
        left: 50%;
        top: 50%;
        transform: translate(-50%, -140%);
    }
    .boxMember {
        position: absolute;
        width: 100%;
        bottom: 0;
        height: 54px;
        background-image: url("https://s10.mogucdn.com/mlcdn/c45406/191010_2il1l3i91gkd5c461j93e5ehfcgi5_1404x216.png");
        background-size: 335px 54px;
        border-bottom-left-radius: 10px;
        border-bottom-right-radius: 10px;
    }
    .application {
        width: 335px;
        height: 69px;
        margin-left: 20px;
        background: white;
        border-radius: 10px;
        display: flex;
    }
    .application div {
        flex: 1;
        padding: 10px 25px;
    }
    .application div img {
        width: 25px;
        height: 25px;
    }
    .application div p {
        margin: 0;
        padding: 0;
        color: #5e5e5e;
        font-size: 12px;
    }
    .cart {
        display: flex;
        width: 335px;
        height: 120px;
        margin-left: 20px;
        margin-top: 15px;
        justify-content: space-between;
    }
    .cart-left {
        width: 160px;
        height: 120px;
        background: white;
        border-radius: 10px;
        font-size: 12px;
        background-image: url("https://s10.mogucdn.com/mlcdn/c45406/190815_3637hh8ac09e50j7f07i7eealck4i_513x360.png");
        background-size: 140px 120px;
    }
    .cart-left div {
        padding-left: 10px;
        font-size: 15px;
        padding-top: 10px;
    }
    .cart-right {
        width: 160px;
        height: 120px;
        background: white;
        border-radius: 10px;
        display: flex;
        flex-direction: column;
    }
    .cart-right div {
        flex: 1;
        line-height: 40px;
        font-size: 12px;
        padding-left: 5px;
        border-bottom: 1px solid #e5e5e5;
    }
    .cart-right div span {
        margin-left: 85px;
        color: #5e5e5e;
        font-size: 5px;
    }
    .r1 {
        border-bottom: none !important;
    }
    .order {
        width: 335px;
        height: 100px;
        margin-left: 20px;
        margin-top: 25px;
        background: white;
        border-radius: 10px;
    }
    .order-top {
        height: 40px;
        width: 100%;
        border-bottom: 1px solid #e5e5e5;
        line-height: 40px;
        font-size: 14px;
        padding-left: 10px;
        display: flex;
    }
    .order-top span {
        margin-left: 235px;
    }
    .order-bottom {
        height: 60px;
        width: 100%;
        display: flex;
    }
    .order-bottom div {
        flex: 1;
        padding-top: 10px;
        padding-left: 8px;
        position: relative;
    }
    .order-bottom div img {
        width: 25px;
        height: 25px;
        margin-left: 10px;
    }
    .order-bottom div p {
        margin: 0;
        padding: 0;
        color: #5e5e5e;
        font-size: 12px;
        margin-left: 5px;
    }
    .finalBtn {
        width: 335px;
        height: 40px;
        margin-left: 20px;
        margin-top: 15px;
        border-radius: 10px;
    }
    .finalBtn {
        width: 335px;
        height: 40px;
        background: rgb(255, 68, 102);
        margin-top: 7px;
        color: white;
        line-height: 40px;
        text-align: center;
    }
    .numIcon {
        padding: 0 !important;
        top: -45px;
        right: -27px;
        width: 15px;
        height: 15px;
        border-radius: 100%;
        font-size: 10px;
        line-height: 15px;
        text-align: center;
        background: red;
        color: white;
        position: absolute;
    }
</style>