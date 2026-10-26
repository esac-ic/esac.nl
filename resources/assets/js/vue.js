import { createApp } from 'vue';
import { createPinia } from 'pinia';

import agendaComponents from '../vue/agenda/agenda';
import zekeringenComponents from '../vue/zekeringen/zekeringen';
import applicationFormComponents from '../vue/applicationForm/applicationForm';
import AutoCompleteField from '../vue/components/AutoCompleteField.vue';

if (document.getElementById('app') !== null) {
    /**
     * Create vue app
     */
    const app = createApp({});

    app.use(createPinia());

    const components = {
        ...agendaComponents,
        ...zekeringenComponents,
        ...applicationFormComponents,
        'auto-complete-field': AutoCompleteField,
    };

    for (const [name, component] of Object.entries(components)) {
        app.component(name, component);
    }

    app.mount('#app');
}
