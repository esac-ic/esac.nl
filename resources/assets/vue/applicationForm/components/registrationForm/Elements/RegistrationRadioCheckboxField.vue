<template>
    <div>
        <div v-for="(option, index) in row.applicationFormRowOptions" style="margin-bottom: 5px">
            <input  :required="row.required"
                    :type="getType()"
                    :name="row.id + '[]'"
                    :value="option.value"
                    :key="index"
                    style="margin-right: 5px;">
            {{getName(option)}}
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