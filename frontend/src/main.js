import { createApp } from 'vue';
import App from './App.vue';
import router from './router'; 
import { createPinia } from 'pinia';
import './assets/main.css' 

const link = document.createElement('link')
link.rel = 'stylesheet'
link.href = 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css'
document.head.appendChild(link)
const app = createApp(App);

const pinia = createPinia();

app.use(pinia);

app.use(router);

app.mount('#app');
