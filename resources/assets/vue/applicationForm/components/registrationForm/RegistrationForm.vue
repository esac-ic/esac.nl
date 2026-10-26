<template>
    <div>
        <div v-for="(row, index) in rows" class="form-group" :key="index">
            <label>{{getLabel(row)}}<span v-if="isRequired(row)">*</span></label>
            <component :is="getComponentName(row)" :row="row"></component>
        </div>
        <span v-if="hasMandatoryQuestions" class="text-small text-muted">* Required question</span>
    </div>
</template>

<script>
    import RegistrationInputField from "./Elements/RegistrationInputField.vue";
    import RegistrationRadioCheckboxField from "./Elements/RegistrationRadioCheckboxField.vue";
    import RegistrationSelectField from "./Elements/RegistrationSelectField.vue";
    import RegistrationTextAreaField from "./Elements/RegistrationTextAreaField.vue";
    import { FORM_TYPE_CHECK_BOX, FORM_TYPE_CHECK_BOXES, FORM_TYPE_NUMBER, FORM_TYPE_RADIO, FORM_TYPE_SELECT, FORM_TYPE_TEXT, FORM_TYPE_TEXT_BOX } from "../../constants";

    export default {
        name: "RegistrationForm",
        props: {
            rows: Array,
        },
        methods:{
            getComponentName(row) {
                switch (row.type) {
                    case FORM_TYPE_TEXT:
                    case FORM_TYPE_NUMBER:
                    case FORM_TYPE_CHECK_BOX:
                        return RegistrationInputField;
                    case FORM_TYPE_TEXT_BOX:
                        return RegistrationTextAreaField;
                    case FORM_TYPE_SELECT:
                        return RegistrationSelectField;
                    case FORM_TYPE_RADIO:
                    case FORM_TYPE_CHECK_BOXES:
                        return RegistrationRadioCheckboxField;
                }

                console.error(row.type + ' is not supported');
            },
            getLabel(row) {
                return row.name;
            },
            isRequired(row) {
                return row.required;
            }
        },
        computed: {
            hasMandatoryQuestions()
            {
                return this.rows.some(this.isRequired);
            },
        },
    }
</script>
