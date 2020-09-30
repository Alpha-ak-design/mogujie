<template>
    <div class="tab-bar-item" @click="itemClick">
        <slot v-if="!isActive" name="item-icon"></slot>
        <slot v-else name="item-icon-active"></slot>
        <div :style="activeStyle">
            <slot name="item-text"></slot>
        </div>
    </div>
</template>

<script>
    export default {
        name: "tabbarItem",
        computed:{
            activeStyle(){
                return this.isActive ? {color:"red"} : {}
            },
            isActive(){
                return this.$route.path.indexOf(this.path) !== -1;
            },
        },
        props:{
          path:String //路由
        },
        methods:{
            itemClick(){
              this.$router.replace(this.path);
            },
        }
    }
</script>

<style scoped>
    .tab-bar-item {
        flex: 1;
        text-align: center;
        height: 49px;
        font-size: 14px;
    }
    .tab-bar-item img {
        width: 24px;
        height: 24px;
        margin-top: 3px;
        vertical-align: middle;
        margin-bottom: 2px;
    }
</style>