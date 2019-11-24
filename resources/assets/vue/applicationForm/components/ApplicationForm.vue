<template>
    <div class="card">
        <div class="card-header">
            <h3>
                Formulier rijen
                <span v-on:click="addRow()" class="float-right" style="cursor: pointer">
                    <i class="ion-plus"></i>
                </span>
            </h3>
        </div>
        <div class="card-body">
            <div v-for="(row, index) in applicationRows" :index="index">
                <div class="row">
                    <div class="col-md-5">
                        <label>Naam (NL)</label>
                        <input type="text"
                               class="form-control"
                               v-model="row.nameNL"
                               :name="'rows[' + index + '][nl_name]'"
                               required>
                    </div>
                    <div class="col-md-5">
                        <label>Naam (EN)</label>
                        <input type="text"
                               class="form-control"
                               v-model="row.nameEN"
                               :name="'rows[' + index + '][en_name]'"
                               required>
                    </div>
                    <div class="col-md-1">
                        <button type="button" class="btn btn-danger" v-on:click="deleteRow(index)">
                            <i class="ion-trash-a"></i>
                        </button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <label>Type</label>
                            <select class="form-control" :name="'rows[' + index + '][type]'" v-model="row.type" required>
                                <option v-for="(name, value) in FORM_TYPES" :value="value" :key="value">{{ name }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-5">
                        <div class="form-group">
                            <input type="checkbox" v-model="row.required" :name="'rows[' + index + '][required]'"> Verplicht
                        </div>
                    </div>
                </div>
                <input type="hidden" v-if="row.id !== undefined" v-model="row.id" :name="'rows[' + index + '][id]'">
            </div>
        </div>
    </div>
</template>

<script>
    import {FORM_TYPES} from '../constants';

    export default {
        name: "ApplicationForm",
        data(){
            return {
                applicationRows: [],
                FORM_TYPES
            }
        },
        props:['rows'],
        methods:{
            addRow(){
                this.applicationRows.push({
                    'nameNl' : "",
                    'nameEn' : "",
                    'type' : "",
                    'required' : false,
                    'id' : undefined
                });
            },
            deleteRow(index){
                this.applicationRows.splice(index, 1);
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

</style>