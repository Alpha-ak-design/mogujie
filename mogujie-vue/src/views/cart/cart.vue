<template>
    <div class="cart">
        <nav-bar>
            <div slot="left" @click="goBack">
                <span class='iconfont icon-tubiaozhizuo--' style=' font-size: 25px;'></span>
            </div>
            <div slot="center">购物车（{{isAllNum}}）</div>
        </nav-bar>
        <div class='cart-list-item' v-for='(item1,index1) in cartList' :key='index1'>
                <div class='cart-list-item-btn' @click="oneBtn(item1)">
                    <span class='iconfont icon-icon-test-copy' v-show='item1["isOk"]'></span>
                </div>
                <img :src="item1.img" alt="">
                <div class='cart-list-item-text'>{{item1.text}}</div>
                <span class='cart-list-item-dec'>颜色：{{item1.type+item1.size}}</span>
                <span class='cart-list-item-downPrice'>降价20元</span>
                <span class='cart-list-item-price'>${{item1.price}}</span>
                <div class='cart-list-item-number'>
                    <span @click='item1.num--'>-</span>
                    <span>{{item1.num}}</span>
                    <span @click='item1.num++'>+</span>
                </div>
            </div>
        <!-- 底部 -->
        <div class='bottom-nar'>
            <div class='cart-list-bottom-btn' @click='isAllBtn'>
                <span class='iconfont icon-icon-test-copy' v-show='isBigBtn'></span>
            </div>
            <div style='width:170px'>全选({{isConfirmNum}})</div>
            <div style="min-width: 60px;">￥{{isAllPrice}} </div>
            <div @click='goOrder'>结算</div>
        </div>
    </div>
</template>

<script>
    import navBar from "../../components/common/navbar/navbar"
    export default {
        name: "cart",
        components: {
            navBar
        },
        activated(){
            this.getCart()
        },
        data(){
          return {
              isAllNum:0,
              cartList:[],
              isShop:{

              },
              shop:[],
              isBigBtn:false,
          }
        },
        computed:{
            isAllPrice(){
                let total = 0;
                let b = this.cartList.filter((item)=>{return item.isOk== true}).map((item)=>{return item.price * item.num});
                if (b.length > 0){
                    total = b.reduce((p,n)=>{return p+n})
                }
                return total;
            },
            isConfirmNum(){
                let cart_list = this.cartList
                let all = 0;
                for(let i in cart_list){
                    if (cart_list[i]["isOk"]){
                        all++;
                    }
                }
                return all;
            }
        },
        methods:{
            goOrder(){
                let obj = [];
                obj = this.cartList.filter((item)=>{
                    return item.isOk == true;
                })
                this.$router.push({name: 'order', params: {obj}})
                // for (let i = 0; i < this.shop.length; i++) {
                //     this.cartList[this.shop[i]].forEach((item) => {
                //         if (item['isOk']) {
                //             obj.push(item)
                //         }
                //     })
                //     this.$router.push({name: 'order', params: {obj}})
                // }
            },
            isAllBtn(){
                this.isBigBtn = !this.isBigBtn
                this.cartList.forEach((item)=>{
                    return item.isOk = this.isBigBtn;
                })
            },
            oneBtn(item){
                item.isOk = !item.isOk
                this.changeBtn()
                // this.change1Btn()
                // this.change2btn()
            },
            changeBtn(){
                this.isBigBtn = this.isConfirmNum==this.cartList.length ? true : false
            },
            showBtn(item){
                console.log(item);
            },
            async getCart(){
                const result = await this.$http({
                    method:"post",
                    url:"index/test_ela/getCart",
                    headers:{
                        'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'
                    },
                    data:this.$qs.stringify({user_id:this.$user_id()})
                });
                this.cartList = result.data.data
                this.isAllNum = this.cartList.length
                this.cartList.forEach((item)=>{
                    return item.isOk = JSON.parse(item.isOk)
                })
            },
            goBack(){
                this.$router.push("/home");
            }
        }
    }
</script>

<style scoped>
    .cart {
        background: rgb(246, 246, 246);
        height: 100%;
        min-height: 667px;
    }
    .narBar {
        font-size: 20px;
        font-weight: 600;
        color: #999999;
        background: white;
    }
    .cart-listItem {
        box-sizing: border-box;
        margin: 15px;
        padding: 15px;
        background: white;
    }
    .cart-list-head {
        height: 24px;
        width: 100%;
        display: flex;
        font-weight: 600;
    }
    .cart-list-head-btn {
        width: 20px;
        height: 20px;
        border-radius: 100%;
        border: 1px solid #999999;
        margin-right: 10px;
        font-weight: 100;
    }
    .cart-list-head-btn span {
        font-size: 22px;
        color: #ff5777;
        position: relative;
        top: -2px;
        left: -1px;
    }
    .cart-list-item {
        height: 132px;
        width: 100%;
        position: relative;
    }
    .cart-list-item-btn {
        width: 20px;
        height: 20px;
        border-radius: 100%;
        border: 1px solid #999999;
        margin-right: 10px;
        position: absolute;
        top: 60px;
    }
    .cart-list-item-btn span {
        font-size: 22px;
        color: #ff5777;
        position: relative;
        top: -2px;
        left: -1px;
    }
    .cart-list-item img {
        width: 70px;
        height: 92px;
        position: absolute;
        left: 31px;
        top: 16px;
    }
    .cart-list-item-text {
        position: absolute;
        top: 19px;
        left: 113px;
        width: 200px;
        font-size: 13px;
    }
    .cart-list-item-dec {
        background: #f7f7f7;
        color: #ababab;
        position: absolute;
        top: 63px;
        left: 113px;
        font-size: 13px;
        padding: 1px;
    }
    .cart-list-item-downPrice {
        border: 1px solid #f46;
        color: #f46;
        font-size: 13px;
        position: absolute;
        top: 86px;
        left: 113px;
        padding: 1px 3px;
    }
    .cart-list-item-price {
        position: absolute;
        top: 110px;
        left: 113px;
        color: red;
        font-weight: 600;
    }
    .cart-list-item-number {
        display: inline-block;
        width: 90px;
        height: 20px;
        border-radius: 10px;
        background: #f7f7f7;
        color: #ababab;
        position: absolute;
        top: 105px;
        left: 220px;
    }
    .cart-list-item-number span {
        display: inline-block;
        width: 30px;
        text-align: center;
        font-size: 18px;
    }
    .cart-list-item-number span:nth-child(2) {
        color: black;
    }
    .bottom-nar {
        position: fixed;
        bottom: 0px;
        height: 50px;
        width: 100%;
        background: white;
        z-index: 10000;
        display: flex;
        padding: 10px 15px;
        box-sizing: border-box;
        line-height: 30px;
    }
    .cart-list-bottom-btn {
        width: 20px;
        height: 20px;
        border-radius: 100%;
        border: 1px solid #999999;
        margin-right: 10px;
        font-weight: 100;
        margin-top: 10px;
        margin-left: 10px;
        position: relative;
        top: -5px;
    }
    .cart-list-bottom-btn span {
        font-size: 22px;
        color: #ff5777;
        position: relative;
        top: -5px;
        left: -1px;
    }
    .bottom-nar div:nth-child(3) {
        color: red;
    }
    .bottom-nar div:nth-child(4) {
        box-sizing: content-box;
        padding: 0 8px;
        background: #ff5777;
        color: white;
        border-radius: 20px;
        margin-left: 10px;
    }
</style>