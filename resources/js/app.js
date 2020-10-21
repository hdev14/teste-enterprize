require('./bootstrap');

window.Vue = require('vue');

Vue.component('poll-container', require('./components/PollContainer.vue').default);

const app = new Vue({
    el: "#app"
})
