import Vue from 'vue'
import Router from 'vue-router'

// Import Pages File
import firstPage from './components/pages/myfirst_view_page'
import newRoutePage from './components/pages/new_route_page'
import hooks from './components/pages/basic/hooks.vue'


Vue.use(Router)

const routes = [
    {
        path        : '/mynew_vue_route',
        component   : firstPage
    },
    {
        path        : '/new-route',
        component   : newRoutePage
    },
    
    // Vue hooks
    {
        path: '/hooks',
        component: hooks
    }
]

export default new Router({
    mode: 'history',
    routes
})