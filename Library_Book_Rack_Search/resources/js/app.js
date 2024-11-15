import './bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'vue-select/dist/vue-select.css';

import { createApp } from 'vue';
import App from './App.vue';
import router from './router';
import Swal from 'sweetalert2';
import vSelect from 'vue-select';
import moment from 'moment';

const app = createApp(App);

app.config.globalProperties.$swal = Swal;
app.config.globalProperties.$moment = moment;

app.component('v-select', vSelect);
app.use(router);
app.mount('#app');

