import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import axios from 'axios'
import qs from 'qs'
import Mint from 'mint-ui'
import alert from './components/common/alert/alert'
import "../public/font/font_xn7gg6ug1r/iconfont.css"

Vue.use(Mint)
Vue.use(alert)
Vue.config.productionTip = false
axios.defaults.baseURL = "/api"
axios.interceptors.request.use(config=>{
    let token = window.localStorage.getItem("my-token");
    if(!token){
        config.headers['authorization'] = 'bearer ' + token
    }
    return config;
},err=>{
    console.log(err)
})
// 响应拦截
axios.interceptors.response.use(res => {
    return res
}, err => {
    console.log(err)
})
Vue.prototype.$http = axios
Vue.prototype.$qs = qs
Vue.prototype.$user_id = ()=>{
  return window.localStorage.getItem("my-token");
};
new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')
