import { createApp } from 'vue';
import App from './pages/App.vue';
import router from './router';
import axios from 'axios';

axios.defaults.baseURL = 'http://localhost:8000';

import './assets/main.css';

const app = createApp(App);

app.use(router);

app.mount('#app');