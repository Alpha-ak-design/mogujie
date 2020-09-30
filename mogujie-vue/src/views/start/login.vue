<template>
    <div>
        <div class="loginTitle">账号登录</div>
        <div class="input">
            <input type="text" v-model="account.name" placeholder="账号名称" />
        </div>
        <div class="input">
            <input type="password" v-model="account.password" placeholder="密码" />
        </div>
        <div class='button' @click="login">登录</div>
        <div class='goRegister' @click='goRegister'>点击注册</div>
    </div>
</template>

<script>
    export default {
        name: "login",
        data(){
            return {
                account:{
                    name:'',
                    password:''
                }
            }
        },
        methods:{
            async login(){
                const result = await this.$http({
                    method: 'post',
                    url: 'index/test_ela/login',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded;charset=utf-8'
                    },
                    data: this.$qs.stringify(this.account)
                })
                if (result.data.success) {
                    this.$alert.success('登陆成功', 1000)
                    this.$router.push('/enter')
                    let token = result.data.token
                    localStorage.setItem('my-token', token)
                }else{
                    this.$alert.error('登陆失败', 1000)
                }
            },
            goRegister(){

            },
        }
    }
</script>

<style scoped>
    input {
        outline-style: none;
        padding: 8px 0px;
        font-size: 20px;
        border: 0;
        border-bottom: 1px solid #eaeaea;
        width: 325px;
        margin-left: 25px;
    }
    .input {
        margin: 20px auto;
    }
    input::-webkit-input-placeholder {
        padding-left: 30px;
        opacity: 0.5;
    }
    .loginTitle {
        height: 44px;
        color: #5e5e5e;
        font: 400 18px Arial, sans-serif;
        text-align: center;
        border-bottom: 1px solid #eaeaea;
        line-height: 44px;
    }
    .button {
        width: 325px;
        height: 50px;
        margin-left: 25px;
        margin-top: 40px;
        line-height: 50px;
        text-align: center;
        background: #ff5777;
        border-radius: 25px;
        color: white;
    }
    .goRegister {
        margin-left: 290px;
        margin-top: 10px;
        font-size: 5px;
        color: #5e5e5e;
    }
</style>