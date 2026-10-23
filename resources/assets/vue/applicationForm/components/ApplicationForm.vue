<template>
    <div class="card">
        <div class="card-header">
            <h3>
                Form rows
                <button type="button" class="btn btn-primary float-right" v-on:click="addRow()">
                    <i class="ion-plus"></i> Add row
                </button>
            </h3>
        </div>
        <div class="card-body">
            <div v-for="(row, index) in applicationRows" :key="row.key" class="application-form-row">
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
                                <option v-for="(name, value) in FORM_TYPES" :value="value" :key="value">{{ name }}</option>
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
                                <i class="ion-trash-a" aria-hidden="true"></i> Remove
                            </button>
                        </div>
                    </div>
                </div>
                <div class="row" v-if="showRowOptions(row)">
                    <application-form-row-options :rows="row.applicationFormRowOptions" :application-form-index="index"></application-form-row-options>
                </div>
                <input type="hidden" v-if="row.id !== undefined" v-model="row.id" :name="'rows[' + index + '][id]'">
            </div>
        </div>
    </div>
</template>

<script>
    import { FORM_TYPE_TEXT, FORM_TYPES, ROW_OPTION_FORM_TYPE } from '../constants';
import ApplicationFormRowOptions from "./ApplicationFormRowOptions";

    let nextKey = 0;

    export default {
        name: "ApplicationForm",
        components: {ApplicationFormRowOptions},
        data(){
            return {
                applicationRows: [],
                FORM_TYPES,
                ROW_OPTION_FORM_TYPE
            }
        },
        props:['rows'],
        methods:{
            addRow(){
                this.applicationRows.push({
                    'key' : nextKey++,
                    'name' : "",
                    'type' : FORM_TYPE_TEXT,
                    'required' : false,
                    'id' : undefined
                });
            },
            async deleteRow(index){
                const row = this.applicationRows[index];
                // A saved question takes its answers with it when the form is saved.
                if (row.id !== undefined && !await window.confirmDialog({
                    title: 'Remove question',
                    message: `Remove the question "${row.name}"? Its answers are deleted when you save the form.`,
                    button: 'Remove',
                })) {
                    return;
                }
                this.applicationRows.splice(this.applicationRows.indexOf(row), 1);
            },
            showRowOptions(row) {
                return ROW_OPTION_FORM_TYPE.includes(row.type);
            }
        },
        mounted(){
            if(undefined === this.rows || this.rows.length === 0){
                this.addRow();
            } else {
                // A stable key per row, so removing a row doesn't hand its options to the next row.
                this.applicationRows = JSON.parse(JSON.stringify(this.rows)).map(row => ({...row, key: nextKey++}));
            }
        }
    }
</script>

<style scoped>
    .application-form-row {
        margin-bottom: 10px;
        padding: 5px;
        border-bottom: 1px solid #dee2e6;
    }
    .application-form-row:last-child {
        border-bottom: 0;
    }
</style>