import './bootstrap';
import { createApp } from 'vue/dist/vue.esm-bundler.js';

import ServiceAccountsIndex from "./components/service_accounts/index.vue"
import ReadingsManualReadingSearch from "./components/readings/manual_reading_search.vue"
import ReadingsFullReport from "./components/readings/full_report.vue"
import AllApplications from "./components/service_connections/index.vue"
import AppliedRequests from "./components/service_connections/applied-requests.vue"
import ImagesGallery from "./components/service_connections/images-gallery.vue"
import CreateSelect from "./components/tickets/create-select.vue"

const app = createApp({});
app.component('service-accounts', ServiceAccountsIndex);
app.component('manual-reading-search', ReadingsManualReadingSearch);
app.component('readings-full-report', ReadingsFullReport);
app.component('all-applications', AllApplications);
app.component('images-gallery', ImagesGallery);
app.component('create-select', CreateSelect);
app.component('applied-requests', AppliedRequests);

app.mount("#app");