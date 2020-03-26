<template>
    <div>
        <input class="form-control" v-model="value" :type="inputType" :required="row.required" :name="row.id">
        <input v-if="row.type === FORM_TYPE_CHECK_BOX && !value" type="hidden" value="0" :name="row.id">
    </div>
</template>

<script>
    import {FORM_TYPE_TEXT, FORM_TYPE_NUMBER, FORM_TYPE_CHECK_BOX} from "../../../constants";

    export default {
        name: "RegistrationInputField",
        props: [
            'row'
        ],
        data(){
            return {
                FORM_TYPE_CHECK_BOX,
                value: ""
            }
        },
        computed: {
            inputType(){
                switch (this.row.type) {
                    case FORM_TYPE_TEXT:
                       return "text";
                    case FORM_TYPE_NUMBER:
                       return "number";
                    case FORM_TYPE_CHECK_BOX:
                       return "checkbox";
                }

                console.error(this.row.type + ' is not supported');
            }
        },
    }
</script>

<style scoped>

</style>