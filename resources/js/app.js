import './bootstrap';
import { createApp } from 'vue/dist/vue.esm-bundler.js';

import ServiceAccountsIndex from "./components/service_accounts/index.vue"
import ReadingsManualReadingSearch from "./components/readings/manual_reading_search.vue"
import AllApplications from "./components/service_connections/index.vue"
import ImagesGallery from "./components/service_connections/images-gallery.vue"

const app = createApp({});
app.component('service-accounts', ServiceAccountsIndex);
app.component('manual-reading-search', ReadingsManualReadingSearch);
app.component('all-applications', AllApplications);
app.component('images-gallery', ImagesGallery);

app.mount("#app");