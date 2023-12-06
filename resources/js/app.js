import { createApp, h } from 'vue'
import 'vuetify/styles'
import { createVuetify } from 'vuetify'
import * as directives from 'vuetify/directives'
import { Model } from 'vue-api-query'
import Alpine from 'alpinejs';
import axios from 'axios'
import 'material-design-icons-iconfont/dist/material-design-icons.css'
import { aliases, mdi } from 'vuetify/iconsets/mdi'
import * as components from 'vuetify/components'
import * as labsComponents from 'vuetify/labs/components'

const vuetify = createVuetify({
    components: {
        ...components,
        ...labsComponents,
    },
    directives,
    icons: {
        defaultSet: 'mdi',
        aliases,
        sets: {
            mdi,
        },
    },
});

require('./bootstrap');
Model.$http = axios
window.Alpine = Alpine;

Alpine.start();

const app = createApp({})

// Component Registration
app.component('user-index', require('./Pages/Users/Index.vue').default);
app.component('feedback', require('./Pages/Feedbacks/Index.vue').default);

// Role
app.component('roles-index', require('./Pages/Roles/Index.vue').default);

app.use(vuetify).mount('#app');
