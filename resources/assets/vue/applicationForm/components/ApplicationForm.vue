<template>
    <div class="card">
        <div class="card-header">
            <h3>
                Form rows
                <button type="button" class="btn btn-primary float-right" v-on:click="addRow()">
                    <PlusIcon class="icon" aria-hidden="true" /> Add row
                </button>
            </h3>
        </div>
        <div class="card-body">
            <div v-for="(row, index) in applicationRows" :key="row.key" class="py-2 border-bottom">
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label :for="'row-name-' + row.key">Question</label>
                            <input type="text"
                                :id="'row-name-' + row.key"
                                class="form-control"
                                v-model="row.name"
                                :name="'rows[' + index + '][name]'"
                                required>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label :for="'row-type-' + row.key">Type</label>
                            <select :id="'row-type-' + row.key" class="form-control" :name="'rows[' + index + '][type]'" v-model="row.type" required>
                                <option v-for="type in types" :key="type.value" :value="type.value">{{ type.label }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-8 col-md-2 d-flex align-items-md-end">
                        <div class="form-group form-check mb-3 mb-md-2 pb-md-1">
                            <input type="checkbox"
                                   :id="'row-required-' + row.key"
                                   v-model="row.required"
                                   :name="'rows[' + index + '][required]'"
                                   class="form-check-input">
                            <label class="form-check-label" :for="'row-required-' + row.key">Required</label>
                        </div>
                    </div>
                    <div class="col-4 col-md-2 d-flex align-items-md-end justify-content-end">
                        <div class="form-group">
                            <button type="button" class="btn btn-outline-danger text-nowrap" v-on:click="deleteRow(index)">
                                <TrashIcon class="icon" aria-hidden="true" /> Remove
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row" v-if="hasOptions(row)">
                    <application-form-row-options :rows="row.applicationFormRowOptions" :application-form-index="index"></application-form-row-options>
                </div>
                <input type="hidden" v-if="row.id !== undefined" v-model="row.id" :name="'rows[' + index + '][id]'">
            </div>
        </div>
    </div>
</template>

<script>
    import { PlusIcon, TrashIcon } from '@heroicons/vue/24/outline';
    import { confirmDialog } from '../../../js/confirm-dialog';
    import ApplicationFormRowOptions from './ApplicationFormRowOptions.vue';

    let nextKey = 0;

    export default {
        name: 'ApplicationForm',
        components: { ApplicationFormRowOptions, PlusIcon, TrashIcon },
        props: {
            rows: Array,
            // Every field type: { value, label, hasOptions }
            types: Array,
        },
        data() {
            // A stable key per row, so removing a row doesn't hand its options to the next row
            const rows = (this.rows ?? []).map(row => ({ ...structuredClone(row), key: nextKey++ }));
            return { applicationRows: rows };
        },
        mounted() {
            if (this.applicationRows.length === 0) {
                this.addRow();
            }
        },
        methods: {
            addRow() {
                this.applicationRows.push({ key: nextKey++, name: '', type: this.types[0].value, required: false, id: undefined });
            },
            async deleteRow(index) {
                const row = this.applicationRows[index];
                // A saved question takes its answers with it when the form is saved
                if (row.id !== undefined && !await confirmDialog({
                    title: 'Remove question',
                    message: `Remove the question "${row.name}"? Its answers are deleted when you save the form.`,
                    button: 'Remove',
                })) {
                    return;
                }
                this.applicationRows.splice(index, 1);
            },
            hasOptions(row) {
                return this.types.find(type => type.value === row.type)?.hasOptions;
            },
        },
    };
</script>
