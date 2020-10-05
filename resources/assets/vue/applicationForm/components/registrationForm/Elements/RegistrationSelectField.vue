<template>
    <select :required="row.required" :name="row.id" class="form-control">
        <option v-for="(option, index) in row.applicationFormRowOptions" :value="option.value" :key="index">
            {{getName(option)}}
        </option>
    </select>
</template>

<script>
    export default {
        name: "RegistrationSelectField",
        props: [
            'row',
            'lang'
        ],
        methods: {
            getName(option) {
                return this.lang === 'en' ? option.nameEN : option.nameNL;
            }
        }
    }
</script>

<style scoped>

</style>