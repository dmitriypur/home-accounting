import './bootstrap';
import { createApp } from 'vue';
import { createRouter, createWebHistory } from 'vue-router';
import { createPinia } from 'pinia';
import App from './components/App.vue';
import routes from './routes';

const app = createApp(App);
const router = createRouter({
    history: createWebHistory(),
    routes
});
const pinia = createPinia();

app.use(router);
app.use(pinia);
app.mount('#app');