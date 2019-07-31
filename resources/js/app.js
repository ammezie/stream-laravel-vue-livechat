/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');
import "../sass/chat.sass";

window.Vue = require('vue');
Vue.prototype.EventBus = new Vue();
window.axios = require('axios');
window.VueRouter=require('vue-router').default;

const Chat = Vue.component('chat', require('./components/Chat.vue').default);
const Admin = Vue.component('admin', require('./components/AdminChat.vue').default);

Vue.prototype.$baseurl = "https://localhost:8010";


window.router = new VueRouter({
  routes:[
      {
        path: '/',
        name: 'chat',
        component: Chat,
      },
       {
        path: '/admin',
        name: 'admin',
        component: Admin,
      }

  ],
})
const app = new Vue({
    router,
    el: '#app',
});
