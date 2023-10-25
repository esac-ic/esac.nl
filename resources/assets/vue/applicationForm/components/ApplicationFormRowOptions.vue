<template>
    <div class="row-option-box">
        <div class="title-box">
            <h3>
                Options
                <span v-on:click="addRow()" class="float-right" style="cursor: pointer">
                    <i class="ion-plus"></i>
                </span>
            </h3>
        </div>
        <div v-for="(row, index) in optionRows" :key="index" class="row" style="margin-bottom: 5px">
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
                        :name="'rows[' + applicationFormIndex + '][options][' + index + '][value]'">
            </div>
            <div class="col-md-1">
                <div class="form-group">
                    <label>Delete</label>
                    <button type="button" class="btn btn-danger form-control" v-on:click="deleteRow(index)">
                        <i class="ion-trash-a"></i>
                    </button>
                </div>
            </div>
            <input type="hidden" v-if="row.id !== undefined" v-model="row.id" :name="'rows[' + applicationFormIndex + '][options][' + index + '][id]'">
        </div>
    </div>
</template>

<script>
    export default {
        name: "ApplicationFormRowOptions",
        props: ['applicationFormIndex', 'rows'],
        data() {
            return {
                optionRows: [],
            }
        },
        methods: {
            addRow(){
                this.optionRows.push({
                    'name' : "",
                    'value': "",
                })
            },
            deleteRow(index){
                this.optionRows.splice(index, 1);
            },
        },
        mounted(){
            if(undefined === this.rows || this.rows.length === 0){
                this.addRow();
            } else {
                this.optionRows = JSON.parse(JSON.stringify(this.rows));
            }
        }
    }
</script>

<style scoped>
    .row-option-box{
        margin: 0 15px;
        padding: 15px;
        width: 100%;
    }
    .title-box {
        border-bottom: solid lightgray 1px;
        margin-bottom: 5px;
    }
</style>