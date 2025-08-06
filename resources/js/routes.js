import Login from './components/auth/Login.vue';
import Register from './components/auth/Register.vue';
import Dashboard from './components/Dashboard.vue';
import Categories from './components/categories/Categories.vue';
import Expenses from './components/expenses/Expenses.vue';

export default [
    {
        path: '/',
        name: 'dashboard',
        component: Dashboard,
        meta: { requiresAuth: true }
    },
    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: { guest: true }
    },
    {
        path: '/register',
        name: 'register',
        component: Register,
        meta: { guest: true }
    },
    {
        path: '/categories',
        name: 'categories',
        component: Categories,
        meta: { requiresAuth: true }
    },
    {
        path: '/expenses',
        name: 'expenses',
        component: Expenses,
        meta: { requiresAuth: true }
    }
];