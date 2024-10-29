import './bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import Swal from 'sweetalert2';

const app = createApp(App);

app.config.globalProperties.$swal = Swal;

app.use(router);
app.mount('#app');

