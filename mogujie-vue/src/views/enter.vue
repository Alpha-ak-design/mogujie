<template>
    <div>
        <tabbar>
            <tabbar-item path="/home">
                <template #item-icon>
                    <img src="../assets/img/tabbar/home.svg" alt="">
                </template>
                <template #item-icon-active>
                    <img src="../assets/img/tabbar/home_active.svg" alt="">
                </template>
                <template #item-text>
                    <div>首页</div>
                </template>
            </tabbar-item>
            <tabbar-item path="/search">
                <template #item-icon>
                    <img src="../assets/img/tabbar/category.svg" alt="">
                </template>
                <template #item-icon-active>
                    <img src="../assets/img/tabbar/category_active.svg" alt="">
                </template>
                <template #item-text>
                    <div>搜索</div>
                </template>
            </tabbar-item>
            <tabbar-item path="/cart" active-color="yellow">
                <template #item-icon>
                    <img src="../assets/img/tabbar/shopcart.svg" alt="">
                </template>
                <template #item-icon-active>
                    <img src="../assets/img/tabbar/shopcart_active.svg" alt="">
                </template>
                <template #item-text>
                    <div>购物车</div>
                </template>
            </tabbar-item>
            <tabbar-item path="/profile">
                <template #item-icon>
                    <img src="../assets/img/tabbar/profile.svg" alt="">
                </template>
                <template #item-icon-active>
                    <img src="../assets/img/tabbar/profile_active.svg" alt="">
                </template>
                <template #item-text>
                    <div>我的</div>
                </template>
            </tabbar-item>
        </tabbar>
        <keep-alive>
            <router-view></router-view>
        </keep-alive>
    </div>
</template>

<script>
    import tabbar from "../components/common/tabbar/tabbar";
    import tabbarItem from "../components/common/tabbar/tabbarItem";
    export default {
        name: "enter",
        components:{
            tabbar,
            tabbarItem
        }
    }
</script>

<style scoped>
    @import "../assets/css/base.css";
</style>