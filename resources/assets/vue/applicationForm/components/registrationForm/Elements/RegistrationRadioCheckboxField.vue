<template>
    <div>
        <div v-for="(option, index) in row.applicationFormRowOptions" class="form-check" :key="index">
            <input  class="form-check-input"
                    :id="row.id + '-' + index"
                    :required="row.required"
                    :type="getType()"
                    :name="row.id + '[]'"
                    :value="option.value">
            <label class="form-check-label" :for="row.id + '-' + index">{{getName(option)}}</label>
        </div>
    </div>
</template>

<script>
    import { FORM_TYPE_RADIO } from "../../../constants";

    export default {
        name: "RegistrationRadioCheckboxField",
        props: [
            'row',
        ],
        methods: {
            getName(option) {
                return option.name;
            },
            getType() {
                if (this.row.type === FORM_TYPE_RADIO) {
                    return 'radio';
                }

                return 'checkbox'
            }
        }
    }
</script>

<style scoped>

</style>