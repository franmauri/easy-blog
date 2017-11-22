import Vue from 'vue';


import VueRouter from 'vue-router';
import Users from './components/Users.vue';
import CreateUsers from './components/CreateUsers.vue';
import EditUsers from './components/EditUsers.vue';
import Posts from './components/Posts.vue';
import CreatePosts from './components/CreatePosts.vue';
import EditPosts from './components/EditPosts.vue';
import Single from './components/Single.vue';
import Blog from './components/Blog.vue';


const Bus = new Vue({

})

window.Bus = Bus

Vue.use(VueRouter)

const router = new VueRouter({
    mode: 'history',
    linkActiveClass: 'active',
    routes: [

        {
            path: '/',
            name: 'blog',
            component: Blog,

        },

        {
            path: '/single',
            name: 'single',
            component: Single,
            props: true,

        },

        {
            path: '/create-posts',
            name: 'create posts',
            component: CreatePosts,

        },
        {
            path: '/posts',
            name: 'posts',
            component: Posts,

        },
        {
            path: '/edit-posts/:id',
            name: 'edit posts',
            component: EditPosts,
            props: true,

        },

        {
            path: '/create-users',
            name: 'create users',
            component: CreateUsers,

        },
        {
            path: '/edit-users/:id',
            name: 'edit users',
            component: EditUsers,
            props: true,

        },

        {
            path: '/users',
            name: 'users',
            component: Users,

        },
//        
//        {
//            path: '/create-posts',
//            name: 'sreate posts',
//            component: CreatePosts,
//
//        },

    ]
})



export default router
