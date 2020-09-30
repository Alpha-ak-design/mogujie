import Vue from 'vue'
import VueRouter from 'vue-router'

const enter = ()=>import("../views/enter")
const home = ()=>import("../views/home/home")
const search = ()=>import("../views/search/search")
const cart = ()=>import("../views/cart/cart")
const profile = ()=>import("../views/profile/profile")
const profileInfo = ()=>import("../views/profile/profileInfo")
const login = ()=>import("../views/start/login")
const searchItem = ()=> import("../views/search/searchItem")
const goods = ()=>import("../views/goods/goods")
const order =()=>import("../views/order/order")
Vue.use(VueRouter)
const routes = [
    {path:"/", redirect:"/enter",},
    {path:"/searchItem",component:searchItem,name:'searchItem',meta:{isLogin: true}},
    {path:"/goods/:id",component:goods,name:'goods'},
    {path:"/order",component:order,name:"order",meta:{isLogin:true}},
    {path:"/profileInfo",component:profileInfo,name:"profileInfo"},
    {
        path:"/enter",
        component:enter,
        children:[
            {path:"/",redirect: "/home",meta:{isLogin:true}},
            {path:"/home",component: home,meta:{isLogin:true}},
            { path: '/search', component: search, meta: { isLogin: true } },
            { path: '/cart', component: cart, meta: { isLogin: false } },
            { path: '/profile', component: profile, meta: { isLogin: false } }
        ]
    },
    {
        path:"/login",component:login,meta:{isLogin: true}
    }
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})
router.beforeEach((to,from,next)=>{
    if (to.meta.isLogin){
        next()
    } else{
        let token = localStorage.getItem("my-token");
        if (!token){
            next("/login")
        } else{
            next()
        }
    }
})

export default router
