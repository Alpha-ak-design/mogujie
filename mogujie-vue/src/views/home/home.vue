<template>
    <div class="home">
        <head-app></head-app>
        <!-- swiper -->
        <div class="swiper-container">
            <div class="swiper-wrapper">
                <div class="swiper-slide"><img src="https://s11.mogucdn.com/mlcdn/c45406/200301_75i5l2l5alcfl7b307fi38f1eja12_1125x390.jpg_750x9999.v1c7E.81.webp"
                                               alt=""></div>
                <div class="swiper-slide"><img src="https://s18.mogucdn.com/mlcdn/c45406/200301_5bb4g6f3k1f6edah363l3k3k3216a_1125x390.jpg_750x9999.v1c7E.81.webp"
                                               alt=""></div>
                <div class="swiper-slide"><img src="https://s17.mogucdn.com/mlcdn/c45406/200228_19ij1jg33a28cfgh7k22ajj5kg0b3_1125x390.jpg_750x9999.v1c7E.81.webp"
                                               alt=""></div>
            </div>
            <!-- Add Pagination -->
            <div class="swiper-pagination"></div>
        </div>
        <div class="full"></div>
        <div class="home-icon">
            <div v-for="(item,index) in iconMes" :key="index" @click='iconGoSearch(item.text)'>
                <img :src="item.img" />
                <p>{{item.text}}</p>
            </div>
        </div>
        <!--商品信息-->
        <div v-infinite-scroll="loadMore"
             infinite-scroll-disabled="loading"
             infinite-scroll-distance="1"
             :infinite-scroll-immediate-check="true">
            <div class="itemPicture">
                <div class="itemPic" v-for="(item,index) in goodsList" :key="index" @click="goGoodsItem(item.s_uid)">
                    <img :src="item.s_img_one">
                    <div class='text'>
                        <span class='span1'>推荐</span>
                        <span>{{item.s_msg}}</span>
                    </div>
                    <div class='bottom-text'>
                        <span class='price'>¥{{item.s_newPrice}}</span>
                        <span class='bottom-text-right'>{{item.s_collect}}
                        <span class='iconfont icon-collect'></span>
                    </span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import headApp from '../profile/head'
    import Swiper from 'swiper';
    import 'swiper/swiper-bundle.css'
    export default {
        name: "home",
        data(){
          return {
              iconMes:[
                  { text: '新人福利', img: 'https://s10.mogucdn.com/mlcdn/c45406/191025_5289fkljd9d0g8i3752e1425h5k5j_150x150.gif_640x640.v1cAC.40.webp' },
                  { text: '女装', img: 'https://s10.mogucdn.com/mlcdn/c45406/191021_38g134a547jibek9a9f1hk63gedea_135x135.jpg_640x640.v1cAC.40.webp' },
                  { text: '上衣', img: 'https://s10.mogucdn.com/mlcdn/c45406/191021_341k4a24f2g43k2f831a3308lfb3e_135x135.jpg_640x640.v1cAC.40.webp' },
                  { text: '裤子', img: 'https://s10.mogucdn.com/mlcdn/c45406/191021_2dcffll9ldb02411a8307hal95676_135x135.jpg_640x640.v1cAC.40.webp' },
                  { text: '裙子', img: 'https://s10.mogucdn.com/mlcdn/c45406/191021_65g8bh2jg5d62ae7j7ck332123b97_135x135.jpg_640x640.v1cAC.40.webp' },
                  { text: '套装', img: 'https://s10.mogucdn.com/mlcdn/c45406/191021_686fch2babajk5hg13f82abg9974b_135x135.jpg_640x640.v1cAC.40.webp' },
                  { text: '女鞋', img: 'https://s10.mogucdn.com/mlcdn/c45406/191023_6730bi06dh6hh7cf3e7h52k4b8gc5_135x135.jpg_640x640.v1cAC.40.webp' },
                  { text: '美妆', img: 'https://s10.mogucdn.com/mlcdn/c45406/190627_453h1450k9j52k5fl1l1d33c40j5a_150x150.jpg_640x640.v1cAC.40.webp' },
                  { text: '包包', img: 'https://s10.mogucdn.com/mlcdn/c45406/191021_3ghafa76lhk4hd09306h1g5h6li22_135x135.jpg_640x640.v1cAC.40.webp' },
                  { text: '母婴', img: 'https://s10.mogucdn.com/mlcdn/c45406/190907_72g51e5ihce09j8e9e5ee2l4ce3k5_135x135.jpg_640x640.v1cAC.40.webp' },
              ],
              query: 1,
              num: 4,
              goodsList:[]
          }
        },
        components:{
            Swiper,
            headApp,
        },
        methods:{
            loadMore () {
                this.loading = true
                // if (this.goodsList.length !== 13) {
                if (true) {
                    this.query++
                    this.getGoodsList(this.query, this.num)
                    this.loading = false
                }
            },
            goGoodsItem(id){
                this.$router.push('/goods/' + id)
            },
            iconGoSearch(text){
                this.$router.push({ name: 'searchItem', params: { searchText: text } })
            },
            async getGoodsList(query,num){
                const result = await this.$http({
                    method:"post",
                    url:"index/test_ela/getGoodsList",
                    headers:{
                        'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'
                    },
                    data:this.$qs.stringify({ query, num })
                });
                this.goodsList = [...this.goodsList, ...result.data]
            }
        },
        mounted(){
            new Swiper('.swiper-container',{
                spaceBetween: 30,
                centeredSlides: true,
                initialSlide: 0,
                observer: true,
                observerParents: true,
                loop:true,
                autoplay: {
                    delay: 1000,
                    disableOnInteraction: false
                },
                pagination: {
                    el: '.swiper-pagination',
                    clickable: true
                }
            });
            this.getGoodsList(this.query,this.num);
        }
    }
</script>

<style scoped>
    .full{
        height: 10px;
        width: 100%;
        background-color: #ffffff;
    }
    .home-icon{
        width: 100%;
        height:150px;
        display: flex;
        flex-wrap:wrap;
        justify-content: space-between;
        background-color: white;
    }
    .home-icon div {
        width: 71.5px;
        height: 85px;
        background: white;
    }
    .home-icon div img {
        width: 50px;
        height: 50px;
        margin-left: 10.75px;
    }
    .home-icon div p {
        text-align: center;
        font-size: 10px;
    }
    .itemPicture {
        display: flex;
        flex-wrap: wrap;
        margin-bottom: 60px;
    }
    .itemPic {
        width: 170px;
        height: 267px;
        background: white;
        margin-left: 12.5px;
        margin-top: 35.5px;
        position: relative;
        font-size: 13px;
    }
    .itemPic img {
        width: 100%;
        height: 210px;
    }
    .span1 {
        color: #9999;
        font-size: 12px;
        margin-right: 8px;
        margin-left: 5px;
    }
    /* 问题 */
    .text {
        white-space: pre;
        overflow: hidden;
        text-overflow: ellipsis;
        overflow-wrap: initial;
    }
    .itemPic .price {
        font-weight: 1000;
        font-size: 18px;
    }
    .bottom-text {
        margin-top: 15px;
        padding: 0 5px;
        display: flex;
        justify-content: space-between;
    }
    .bottom-text-right {
        color: #999999;
    }
    .icon-collect {
        margin-left: 3px;
    }
</style>