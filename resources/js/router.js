import Vue from 'vue'
import Router from 'vue-router'

// Project Pages
import homePage from './components/pages/home'
import tags from './admin/pages/tags'

Vue.use(Router)

const routes = [
    // Projects Routes...
    {
        path        : '/',
        component   : homePage
    },
    {
        path        : '/tags',
        component   : tags
    },
]

export default new Router({
    mode: 'history',
    routes
})