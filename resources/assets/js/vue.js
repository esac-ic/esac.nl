import { createApp } from 'vue';

import Agenda from '../vue/agenda/components/Agenda.vue';
import ApplicationForm from '../vue/applicationForm/components/ApplicationForm.vue';
import RegistrationForm from '../vue/applicationForm/components/registrationForm/RegistrationForm.vue';
import Zekeringen from '../vue/zekeringen/components/Zekeringen.vue';

// The components a page can place, e.g. <div data-vue="agenda" data-props="{&quot;description&quot;: ...}"></div>
const components = {
    'agenda': Agenda,
    'application-form': ApplicationForm,
    'registration-form': RegistrationForm,
    'zekeringen': Zekeringen,
};

document.querySelectorAll('[data-vue]').forEach(element => {
    createApp(components[element.dataset.vue], JSON.parse(element.dataset.props ?? '{}')).mount(element);
});
