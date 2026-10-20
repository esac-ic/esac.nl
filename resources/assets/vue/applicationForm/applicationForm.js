import ApplicationForm from './components/ApplicationForm.vue';
import RegistrationForm from './components/registrationForm/RegistrationForm.vue';
import RegistrationInputField from './components/registrationForm/Elements/RegistrationInputField.vue';
import RegistrationTextAreaField from './components/registrationForm/Elements/RegistrationTextAreaField.vue';
import RegistrationSelectField from './components/registrationForm/Elements/RegistrationSelectField.vue';
import RegistrationRadioCheckboxField from './components/registrationForm/Elements/RegistrationRadioCheckboxField.vue';

export default {
    'application-form': ApplicationForm,
    'registration-form': RegistrationForm,
    'registration-input-field': RegistrationInputField,
    'registration-text-area-field': RegistrationTextAreaField,
    'registration-select-field': RegistrationSelectField,
    'registration-radio-checkbox-field': RegistrationRadioCheckboxField,
};
