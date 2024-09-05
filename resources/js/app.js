import { createApp } from 'vue';
import App from './App.vue';
import router from './router'; // Импортируйте роутер
import ElementPlus from 'element-plus'; // Подключение Element Plus
import 'element-plus/dist/index.css'; // Стили Element Plus

const app = createApp(App);
app.use(router); // Используйте роутер
app.use(ElementPlus); // Используйте Element Plus

app.mount('#app');
