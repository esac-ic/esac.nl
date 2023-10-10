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
        ],
        methods: {
            getName(option) {
                return option.name;
            }
        }
    }
</script>

<style scoped>

</style>