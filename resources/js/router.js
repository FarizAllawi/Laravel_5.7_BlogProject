import Vue from 'vue'
import Router from 'vue-router'

// Project Pages
import homePage from './components/pages/home'
import tags from './admin/pages/tags'
import category from './admin/pages/category'
import usecom from './vuex/usecom'


Vue.use(Router)

const routes = [
    // Projects Routes...
    { path : '/', component : homePage },
    { path : '/tags', component : tags},
    { path : '/category', component : category},

    // Learn Vuex
    { path : '/test-vuex', component : usecom},
    
]

export default new Router({
    mode: 'history',
    routes
})