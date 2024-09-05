import { createRouter, createWebHistory } from 'vue-router';
import Home from './components/Home.vue'; // Убедитесь, что компоненты правильно импортированы
import Login from './components/Login.vue';
import Register from './components/Register.vue';
import Contacts from './components/Contacts.vue';

const routes = [
  { path: '/', component: Home },
  { path: '/login', component: Login },
  { path: '/register', component: Register },
  { path: '/contacts', component: Contacts },
];

const router = createRouter({
  history: createWebHistory(),
  routes,
});

export default router;
