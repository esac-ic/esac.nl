<template>
    <div class="row-option-box">
        <div class="title-box">
            <h5 class="d-flex justify-content-between align-items-center">
                Options
                <button type="button" class="btn btn-sm btn-outline-primary mb-2" v-on:click="addRow()">
                    <i class="ion-plus" aria-hidden="true"></i> Add option
                </button>
            </h5>
        </div>
        <div v-for="(row, index) in optionRows" :key="row.key" class="row" style="margin-bottom: 5px">
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
                        <i class="ion-trash-a" aria-hidden="true"></i> Remove
                    </button>
                </div>
            </div>
            <input type="hidden" v-if="row.id !== undefined" v-model="row.id" :name="'rows[' + applicationFormIndex + '][options][' + index + '][id]'">
        </div>
    </div>
</template>

<script>
    let nextKey = 0;

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
                    'key' : nextKey++,
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
                this.optionRows = JSON.parse(JSON.stringify(this.rows)).map(row => ({...row, key: nextKey++}));
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