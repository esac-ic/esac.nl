<template>
    <div>
        <div v-for="row in rows" :key="row.id" class="form-group">
            <label :for="'question-' + row.id">{{ row.name }}<span v-if="row.required">*</span></label>

            <textarea v-if="row.type === 'textarea'" :id="'question-' + row.id" class="form-control" :name="row.id" :required="row.required"></textarea>

            <select v-else-if="row.type === 'select'" :id="'question-' + row.id" class="form-control" :name="row.id" :required="row.required">
                <option v-for="option in row.applicationFormRowOptions" :key="option.value" :value="option.value">{{ option.name }}</option>
            </select>

            <div v-else-if="row.type === 'radio' || row.type === 'checkboxes'" :id="'question-' + row.id">
                <div v-for="(option, index) in row.applicationFormRowOptions" :key="option.value" class="form-check">
                    <input :id="'question-' + row.id + '-' + index" class="form-check-input" :type="row.type === 'radio' ? 'radio' : 'checkbox'"
                           :name="row.id + '[]'" :value="option.value" :required="row.required">
                    <label class="form-check-label" :for="'question-' + row.id + '-' + index">{{ option.name }}</label>
                </div>
            </div>

            <!-- An unchecked box posts the hidden 0; a checked one posts "on" after it -->
            <template v-else-if="row.type === 'checkbox'">
                <input type="hidden" :name="row.id" value="0">
                <input :id="'question-' + row.id" type="checkbox" class="d-block" :name="row.id" :required="row.required">
            </template>

            <input v-else :id="'question-' + row.id" class="form-control" :type="row.type === 'number' ? 'number' : 'text'" :name="row.id" :required="row.required">
        </div>
        <span v-if="rows.some(row => row.required)" class="text-small text-muted">* Required question</span>
    </div>
</template>

<script>
    export default {
        name: 'RegistrationForm',
        props: {
            // The form's questions: { id, name, type, required, applicationFormRowOptions: [{ name, value }] }
            rows: Array,
        },
    };
</script>
