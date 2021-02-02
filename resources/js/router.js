import Vue from 'vue'
import Router from 'vue-router'
import usecom from './vuex/usecom'


// Project Pages
import homePage from './components/pages/home'
import tags from './admin/pages/tags'
import category from './admin/pages/category'
import adminUsers from './admin/pages/admin_users'
import login from './admin/pages/login'
import role from './admin/pages/role'

Vue.use(Router)

const routes = [
    // Projects Routes...
    { path : '/', component : homePage },
    { path : '/tags', component : tags},
    { path : '/category', component : category},
    { path : '/admin-users', component : adminUsers},
    { path : '/role', component : role},
    { path : '/login', component : login},



    // Learn Vuex
    { path : '/test-vuex', component : usecom },
    
]

export default new Router({
    mode: 'history',
    routes
})