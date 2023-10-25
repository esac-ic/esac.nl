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
            <div v-for="(row, index) in applicationRows" :key="index" class="application-form-row">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Name</label>
                            <input type="text"
                                class="form-control"
                                v-model="row.name"
                                :name="'rows[' + index + '][name]'"
                                required>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Type</label>
                            <select class="form-control" :name="'rows[' + index + '][type]'" v-model="row.type" required>
                                <option v-for="(name, value) in FORM_TYPES" :value="value" :key="value">{{ name }}</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label>Mandatory</label>
                            <input type="checkbox"
                                   v-model="row.required"
                                   :name="'rows[' + index + '][required]'"
                                   class="form-control">
                        </div>
                    </div>
                    <div class="col-md-1">
                        <div class="form-group">
                            <label>Delete</label>
                            <button type="button" class="btn btn-danger form-control" v-on:click="deleteRow(index)">
                                <i class="ion-trash-a"></i>
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
    import { FORM_TYPES, ROW_OPTION_FORM_TYPE } from '../constants';
import ApplicationFormRowOptions from "./ApplicationFormRowOptions";

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
                    'name' : "",
                    'type' : "",
                    'required' : false,
                    'id' : undefined
                });
            },
            deleteRow(index){
                this.applicationRows.splice(index, 1);
            },
            showRowOptions(row) {
                return ROW_OPTION_FORM_TYPE.includes(row.type);
            }
        },
        mounted(){
            if(undefined === this.rows || this.rows.length === 0){
                this.addRow();
            } else {
                this.applicationRows = JSON.parse(JSON.stringify(this.rows));
            }
        }
    }
</script>

<style scoped>
    .application-form-row {
        margin-bottom: 10px;
        padding: 5px;
        border-bottom: solid lightgray;
    }
</style>