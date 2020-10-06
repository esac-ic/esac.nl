<template>
    <textarea class="form-control" :required="row.required" :name="row.id"></textarea>
</template>

<script>
    export default {
        name: "RegistrationTextAreaField",
        props: [
            'row'
        ],
    }
</script>

<style scoped>

</style>