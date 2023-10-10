<template>
    <div>
        <div v-for="(row, index) in applicationFormRows" class="form-group" :key="index">
            <label>{{getLabel(row)}}</label>
            <component :is="getComponentName(row)" :row="row"></component>
        </div>
    </div>
</template>

<script>
    import { FORM_TYPE_CHECK_BOX, FORM_TYPE_CHECK_BOXEN, FORM_TYPE_NUMBER, FORM_TYPE_RADIO, FORM_TYPE_SELECT, FORM_TYPE_TEXT, FORM_TYPE_TEXT_BOX } from "../../constants";

    export default {
        name: "RegistrationForm",
        props: [
            'rows',
        ],
        data(){
            return {
                applicationFormRows: [],
            }
        },
        methods:{
            getComponentName(row) {
                switch (row.type) {
                    case FORM_TYPE_TEXT:
                    case FORM_TYPE_NUMBER:
                    case FORM_TYPE_CHECK_BOX:
                        return "registration-input-field";
                    case FORM_TYPE_TEXT_BOX:
                        return "registration-text-area-field";
                    case FORM_TYPE_SELECT:
                        return "registration-select-field";
                    case FORM_TYPE_RADIO:
                    case FORM_TYPE_CHECK_BOXEN:
                        return "registration-radio-checkbox-field";
                }

                console.error(row.type + ' is not supported');
            },
            getLabel(row) {
                return row.name;
            }
        },
        mounted(){
            this.applicationFormRows = JSON.parse(this.rows);
        }
    }
</script>

<style scoped>

</style>