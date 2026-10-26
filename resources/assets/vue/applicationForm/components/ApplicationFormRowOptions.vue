<template>
    <div class="col-12 px-4 pb-3">
        <div class="border-bottom mb-2">
            <h5 class="d-flex justify-content-between align-items-center">
                Options
                <button type="button" class="btn btn-sm btn-outline-primary mb-2" v-on:click="addRow()">
                    <PlusIcon class="icon" aria-hidden="true" /> Add option
                </button>
            </h5>
        </div>
        <div v-for="(row, index) in optionRows" :key="row.key" class="row mb-2">
            <div class="col-md-4">
                <label>Name</label>
                <input
                        type="text"
                        class="form-control"
                        v-model="row.name"
                        :name="'rows[' + applicationFormIndex + '][options][' + index + '][name]'">
            </div>
            <div class="col-md-3">
                <label>Value</label>
                <input
                        type="text"
                        class="form-control"
                        v-model="row.value"
                        :name="'rows[' + applicationFormIndex + '][options][' + index + '][value]'"
                        :placeholder="row.name">
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <div class="form-group mb-0">
                    <button type="button" class="btn btn-outline-danger text-nowrap" v-on:click="deleteRow(index)">
                        <TrashIcon class="icon" aria-hidden="true" /> Remove
                    </button>
                </div>
            </div>
            <input type="hidden" v-if="row.id !== undefined" v-model="row.id" :name="'rows[' + applicationFormIndex + '][options][' + index + '][id]'">
        </div>
    </div>
</template>

<script>
    import { PlusIcon, TrashIcon } from '@heroicons/vue/24/outline';

    let nextKey = 0;

    export default {
        name: 'ApplicationFormRowOptions',
        components: { PlusIcon, TrashIcon },
        props: {
            applicationFormIndex: Number,
            rows: Array,
        },
        data() {
            const options = (this.rows ?? []).map(option => ({ ...structuredClone(option), key: nextKey++ }));
            return { optionRows: options };
        },
        mounted() {
            if (this.optionRows.length === 0) {
                this.addRow();
            }
        },
        methods: {
            addRow() {
                this.optionRows.push({ key: nextKey++, name: '', value: '' });
            },
            deleteRow(index) {
                this.optionRows.splice(index, 1);
            },
        },
    };
</script>
