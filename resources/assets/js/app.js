
/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */
import router from './router';
import {Config} from './config/app';
import State from './state/store';
import {mapGetters} from 'vuex';


require('./bootstrap');
let Vue = require('vue');

window.axios.defaults.baseURL = Config.baseUrl


/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */



const app = new Vue({

    store: State,
    computed: mapGetters({
        getUser: 'getUser',
        getGUILanguage: 'getGUILanguage'
    }),
    mounted() {
        axios.get('user').then(({data}) => {
            console.log(data);
            if (data.status === 200) {

                State.dispatch('updateUser', data.data);
                console.log('getUser');
                console.log(this.getUser);

            } else {

        }
        }).catch(error => {
            if (error.response.status === 401) {
                this.$auth.destroyToken()
                localStorage.removeItem()
            }
        })
    },

    el: '#app',
    router,
    render: h => h(require('./App.vue'))
});
