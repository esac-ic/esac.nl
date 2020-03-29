<template>
    <div>
        <div v-for="(option, index) in row.applicationFormRowOptions" style="margin-bottom: 5px">
            <input :type="getType()" :name="row.id + '[]'" :value="option.value" :key="index" style="margin-right: 5px;">{{getName(option)}}
        </div>
    </div>
</template>

<script>
    import {FORM_TYPE_RADIO} from "../../../constants";

    export default {
        name: "RegistrationRadioCheckboxField",
        props: [
            'row',
            'lang'
        ],
        methods: {
            getName(option) {
                return this.lang === 'en' ? option.nameEN : option.nameNL;
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