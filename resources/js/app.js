/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

import './bootstrap';
// import Vue from 'vue';
import Vue from 'vue/dist/vue.js';
// import routes from './routes.js';

/**
 * Next, we will create a fresh Vue application instance. You may then begin
 * registering components with the application instance so they are ready
 * to use in your application's views. An example is included for you.
 */
import ExampleComponent from './components/ExampleComponent.vue';
import App from './components/App.vue';
import BoardComponent from './components/BoardComponent.vue';
import RegisterComponent from './components/RegisterComponent.vue';
import LoginComponent from './components/LoginComponent.vue';

const NotFound = { template: '<p>Turn off for deployment purpose</p>' }
const Home = { template: '<board-component></board-component>' }
const Register = { template: '<register-component></register-component>' }
const Login = { template: '<login-component></login-component>' }
const About = { template: '<p>about page</p>' }

const routes = {
    '/home': Home,
    // '/register': Register,
    '/': Login,
    '/about': About
}
Vue.component('example-component', ExampleComponent);
Vue.component('board-component', BoardComponent);
Vue.component('register-component', RegisterComponent);
Vue.component('login-component', LoginComponent);

const app = new Vue({
    el: '#app',
    data: {
        currentRoute: window.location.pathname
    },
    components: { App },
    computed: {
        ViewComponent () {
            return routes[this.currentRoute] || NotFound
        },
    },
    render (h) { return h(this.ViewComponent) }
});


/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// Object.entries(import.meta.glob('./**/*.vue', { eager: true })).forEach(([path, definition]) => {
//     app.component(path.split('/').pop().replace(/\.\w+$/, ''), definition.default);
// });

/**
 * Finally, we will attach the application instance to a HTML element with
 * an "id" attribute of "app". This element is included with the "auth"
 * scaffolding. Otherwise, you will need to add an element yourself.
 */

// app.mount('#app');
