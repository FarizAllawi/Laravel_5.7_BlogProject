<template>
    <div>
        <div class="container-fluid">
            <div class="_ladminOverview_table_recent _box_shadow _border_radious _mar_b30 _p20 col-md-4 ">
                <div class="login_header">
                    <h1 class="text-center">
                        Login
                    </h1>
                </div>
                 <div class="space">
                     <Input type="email" v-model="data.email" placeholder="e-mail"/>
                 </div>
                 <div class="space">
                     <Input type="password" v-model="data.password" placeholder="*****"/>
                 </div>
                 <div class="login_footer">
                     <Button type="primary" @click="login" :disabled="isLogin" :loading="isLogin">{{ isLogin ? 'Login...' : 'Login'}}</Button>
                 </div>
            </div>
        </div>
    </div>
</template>
<script>
export default {
    data() {
        return {
            data : {
                email : '',
                password : '',
            },
            isLogin : false,
        }
    },

    methods : {
        async login() {
            if (this.data.email.trim() == '') return this.e('Email is required')
            if (this.data.password.trim() == '') return this.e('Password is required')
            if (this.data.password.length < 6) return this.e('Incorect login details')
            this.isLogin = true
            const res = await this.callApi('post' , 'app/adminLogin' , this.data)

            if (res.status === 200) {
                this.i(res.data.msg)
                window.location = '/'
            } else {
                if (res.status === 422) {
                    // Info the error message
                    for (let i in res.data.errors)
                        this.i(res.data.errors[i][0])
                }
                else if (res.status === 401) {
                    this.e(res.data.msg)
                }
				else {
					this.swr();
				}
            }
            this.isLogin = false
        }
    }
}
</script>
<style scoped>
    ._ladminOverview_table_recent {
        height:auto;
        margin: 0 auto; 
        margin-top:250px;
        padding: 24px 24px 36px;

    }
    .login_header {
        margin-bottom:25px;
    }
    .space {
        margin:15px;
    }

    .login_footer {
        padding: 15px 15px 36px;
        text-align: right;
    }

    @media (min-width: 450px){
        ._ladminOverview_table_recent {
            padding: 48px 40px 36px;
        }
    }

    @media (min-width: 601px)
    {
        ._ladminOverview_table_recent{
            height: auto;
            min-height: 350px;
        }
    }

</style>