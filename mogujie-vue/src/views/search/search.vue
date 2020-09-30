<template>
    <div>
        <!--搜索框-->
        <div class="search">
            <input type="text" placeholder="女装" v-model="searchText" ref="input">
            <span @click="search" @keyup.enter="search">搜索</span>
        </div>
        <!--搜索历史-->
        <div class="search-history">
            <div class="search-history-top">
                <span class="iconfont icon-sousuo"></span>
                <span>搜索历史</span>
                <span @click='deleteSerachText'>X</span>
            </div>
            <div class='search-history-title'>
                <div v-for="(item,index) in searchHistory"
                     :key='index'><span>{{item}}</span></div>
            </div>
        </div>
        <!--搜索推荐-->
        <div class='search-recommend'>
            <div class='search-recommend-top'>
                <span class='iconfont icon-collect'></span>
                <span>热门推荐</span>
            </div>
            <div class='search-recommend-title'>
                <div v-for='(item,index) in searchRecommend' :key='index' :class='index==currentIndex?"red":""' @click="choosenItem(index)">
                    <span>{{item}}</span>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "search",
        created(){
            this.getSearchHistory()
        },
        activated () {
            this.$refs.input.focus()
        },
        methods:{
            choosenItem(index){
                this.currentIndex = index;
                this.searchText = this.searchRecommend[index];
                this.search();
            },
            async deleteSerachText(){
                const result = await this.$http({
                    method:"post",
                    url:"index/test_ela/deleteSearchText",
                    headers:{
                        'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'
                    },
                    data: this.$qs.stringify({user_id:this.$user_id()})
                })
                this.getSearchHistory();
            },
            search(){
                this.addSearchText()
                this.getSearchHistory()
                this.$router.push({ name: 'searchItem', params: { searchText: this.searchText } })
            },
            async addSearchText(){
                const result = await this.$http({
                    method:"post",
                    url:"index/test_ela/addSearchText",
                    headers:{
                        'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'
                    },
                    data: this.$qs.stringify({ searchText: this.searchText ,user_id:this.$user_id()})
                })
            },
            async getSearchHistory(){
                const result = await this.$http({
                    method:"post",
                    url:"index/test_ela/getSearchText",
                    headers:{
                        'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'
                    },
                    data: this.$qs.stringify({ searchText: this.searchText ,user_id:this.$user_id()})
                })
                this.searchHistory = result.data.data
            }
        },
        data(){
          return {
              searchText:"",
              searchHistory:"",
              searchRecommend: ['男衣', '阿迪达斯', '安德玛', '耐克'],
              currentIndex:-1
          }
        },
    }
</script>

<style scoped>
    input {
        padding: 8px 8px;
        border: 1px solid #e5e5e5;
        border-radius: 5px;
        height: 9px;
        width: 300px;
        margin-left: 6px;
        margin-top: 10px;
        font-size: 12px;
    }
    input::-webkit-input-placeholder {
        opacity: 0.5;
    }
    .search {
        height: 45px;
        width: 100%;
        border-bottom: 1px solid #e5e5e5;
    }
    .search span {
        margin-left: 5px;
        font-size: 15px;
    }
    .search-history {
        width: 100%;
        height: 115px;
        border-bottom: 1px solid #e5e5e5;
        padding: 0 15px;
        box-sizing: border-box;
    }
    .search-history-top span {
        font-size: 15px;
    }
    .search-history-top span:nth-child(1) {
        font-size: 25px;
        position: relative;
        left: 0px;
        top: 4px;
    }
    .search-history-top span:nth-child(3) {
        margin-left: 240px;
    }

    .search-history-top {
        height: 30px;
        line-height: 28px;
        color: #999999;
    }
    .search-history-title {
        height: 85px;
        padding: 10px;
        box-sizing: border-box;
        padding-top: 15px;
        display: flex;
        flex-wrap: wrap;
        overflow: hidden;
    }
    .search-history-title div {
        margin-left: 8px;
        margin-bottom: 20px;
    }
    .search-history-title span {
        height: 25px;
        border-radius: 3px;
        border: 1px solid #e5e5e5;
        padding: 3px 5px;
        font-size: 15px;
    }
    .search-recommend {
        height: 120px;
    }
    .search-recommend-top {
        height: 30px;
        padding-left: 13px;
    }

    .search-recommend-top span {
        line-height: 30px;
        font-size: 15px;
        color: #999999;
    }
    .search-recommend-top span:nth-child(2) {
        margin-left: 6px;
    }

    .search-recommend-title {
        height: 85px;
        padding: 15px 25px;
        box-sizing: border-box;
        padding-top: 15px;
        display: flex;
        flex-wrap: wrap;
        overflow: hidden;
    }
    .search-recommend-title div {
        margin-left: 8px;
        margin-bottom: 20px;
    }
    .search-recommend-title span {
        height: 25px;
        border-radius: 3px;
        border: 1px solid #e5e5e5;
        padding: 3px 5px;
        font-size: 13px;
    }

    .red {
        color: red;
    }
    .green {
        color: green;
    }
</style>