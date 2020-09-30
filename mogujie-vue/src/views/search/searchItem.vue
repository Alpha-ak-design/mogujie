<template>
    <div class="search-item">
        <nav-bar class="narBar">
            <div slot="left" >
                <span class="iconfont icon-tubiaozhizuo--" @click="goBack"></span>
            </div>
            <div slot="center">
                <input type="text" v-model="searchText" @focus="goSearch()"/>
            </div>
        </nav-bar>
        <!--头部-->
        <div class="header">
            <div v-for="(item, index) in headers" :key="index" @click="getIndex(index)" :class='index === headerIndex ? "red" : ""'>
                <span>{{item}}</span>
            </div>
        </div>
        <!--价格区间-->
        <div class="price-select" v-show="headerIndex==3">
            <div>
                <span @click='sortPrice(0)'>由低到高</span><span @click='sortPrice(1)'>由高到低</span>
            </div>
            <div>
                <span>区间（元）</span>
                <input type="text" v-model="searchPrice.low"  />-
                <input type="text" v-model="searchPrice.high" />
                <span class="btn" @click="sortPrice(3)">确定</span>
            </div>
        </div>
        <!--商品-->
        <div class="itemPicture">
            <div class="itemPic" v-for='(item,index) in trueListItem' :key = "index" @click="goGoodDetail(item.s_uid)">
                 <img :src=item.s_img_one />
                <div class='text'><span class='span1'>推荐</span><span>{{item['s_msg']}}</span></div>
                <div class='bottom-text'><span class='price'>${{item['s_newPrice']}}</span>
                    <span class='bottom-text-right'>{{item['s_collect']}}<span class='iconfont icon-collect'></span></span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import navBar from "../../components/common/navbar/navbar"
    export default {
        name: "searchItem",
        components:{
            navBar
        },
        data(){
          return {
              searchText:"",
              // 商品信息
              listItem: [],
              trueListItem: [],
              headerIndex:0,
              headers: ['综合', '销量', '新品', '价格'],
              searchPrice:{
                  low:"",
                  high:""
              }
          }
        },
        methods:{
            goGoodDetail(id){
                this.$router.push("/goods/"+id);
            },
            sortPrice(index){
                if (index == 0){
                    let arr = this.listItem.sort((a,b)=>{
                        if (a.s_newPrice > b.s_newPrice){
                            return 1
                        } else {
                            return -1
                        }
                    })
                    this.trueListItem = arr;
                }
                if (index == 1){
                    let arr = this.listItem.sort(function (a, b) {
                        if (a['s_newPrice'] > b['s_newPrice']) {
                            return -1
                        } else {
                            return 1
                        }
                    })
                    this.trueListItem = arr
                }
                if (index == 3){
                    let low = Number(this.searchPrice.low === '' ? 0 : this.searchPrice.low)
                    let high = Number(this.searchPrice.high === '' ? 9999 : this.searchPrice.high)

                    let arr = this.listItem.filter(function (x) {
                        return (high > x['s_newPrice']) && (x['s_newPrice'] > low)
                    })
                    this.trueListItem = arr
                }
            },
            // 获取点击信息
            getIndex (index) {
                this.headerIndex = index
            },
            goBack(){
                this.$router.go(-1);
                // this.$router.push("/home")
                this.searchText=""
                this.listItem=[]
                this.trueListItem=[]
            },
            goSearch(){
                this.$router.push("/search")
            },
            async getGoodsList(){
                const result = await this.$http({
                    method:"post",
                    url:"index/test_ela/searchGoods",
                    header:{
                        'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'
                    },
                    data:this.$qs.stringify({s_msg:this.searchText})
                });
                this.listItem = result.data.data
                this.trueListItem = this.listItem
            },
        },
        created(){
            this.searchText = this.$route.params.searchText;
            this.getGoodsList();
        },
        activated(){
            this.searchText = this.$route.params.searchText;
            this.getGoodsList();
        },
        mounted(){
        },
    }
</script>

<style scoped>
    .search-item {
        height: 100%;
        min-height: 667px;
        background: #e7e7e7;
    }
    .narBar {
        background: white;
    }
    .narBar span {
        font-size: 23px;
        margin-left: 5px;
    }
    input {
        width: 300px;
        height: 26px;
        border: 1px solid #999999;
        border-radius: 6px;
        padding: 0 8px;
        box-sizing: border-box;
        font-size: 13px;
    }
    .header {
        display: flex;
        background: white;
        width: 100%;
        overflow: hidden;
    }
    .header div {
        flex: 1;
        height: 39px;
        font-size: 13px;
    }
    .header div span {
        position: relative;
        display: inline-block;
        width: 100%;
        top: 14.5px;
        text-align: center;
        border-left: 1px solid #e5e5e5;
    }
    .price-select {
        height: 70px;
        width: 100%;
        background: white;
        border-top: 1px solid #e9e9e9;
        padding-top: 10px;
    }
    .price-select div:nth-child(1) {
        display: flex;
        justify-content: space-around;
    }
    .price-select div:nth-child(1) span {
        width: 100px;
        height: 25px;
        display: inline-block;
        background: #e9e9e9;
        font-size: 15px;
        text-align: center;
        line-height: 25px;
    }
    .price-select div:nth-child(2) {
        font-size: 13px;
        padding: 10px;
    }
    .price-select div:nth-child(2) input {
        width: 85px;
        height: 30px;
        border: 1px solid #e9e9e9;
        margin-left: 3px;
        margin-right: 3px;
    }
    .price-select div:nth-child(2) .btn {
        width: 80px;
        display: inline-block;
        background: #ff5b76;
        color: white;
        height: 30px;
        text-align: center;
        line-height: 30px;
        margin-left: 5px;
    }
    .itemPicture {
        display: flex;
        flex-wrap: wrap;
    }
    .itemPic {
        width: 173px;
        height: 267px;
        background: white;
        margin-left: 11.5px;
        margin-top: 7.5px;
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
    .red {
        color: red;
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