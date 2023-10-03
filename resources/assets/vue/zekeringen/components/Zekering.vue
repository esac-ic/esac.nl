<template>
    <div class="card">
        <div class="card-body">
            <div class="row mb-3 justify-content-between align-items-center">
                <div class="col-auto">
                    <h5 class="card-title text-muted">#{{zekering.id}}</h5>
                </div>
                <div class="col-auto">
                    <button v-on:click="togleForm" class="btn btn-outline-primary" v-if="canAddSubzekering">
                        <span class="ion-plus" aria-hidden="true"></span>
                        Sub-zekering
                    </button>
                    <button v-on:click="deleteZekering" class="btn btn-danger" v-if="canDelete">
                        <span class="ion-trash-a" aria-hidden="true"></span>
                    </button>
                </div>
            </div>
            <p class="card-text">
                ...{{zekering.text}}
            </p>
            <sub-zekering v-for="subzekering in zekering.children"  :key="subzekering.id" :zekering="subzekering"/>
            <div class="form-row ml-4" v-if="showForm">
                <div class="col-6 form-group">
                    <input type="text" class="form-control" id="subZekering" v-model="subZekering" placeholder="...Sub-zekering">
                </div>
                <div class="col-auto">
                    <button class="btn btn-primary" v-on:click="addSubZekering">Voeg toe</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import zekeringApi from '../api/zekering';
    import subZekering from './SubZekering';
    import EventBus from '../event-bus';
    import * as constants from "../constants";

    export default {
        name: "Zekering",
        components: {subZekering},
        data(){
            return {
                showForm: false,
                subZekering: "",
                canDelete: false,
                canAddSubzekering: false
            }
        },
        props: [
            'zekering'
        ],
        methods: {
            deleteZekering(){
                zekeringApi.deleteZekering(this.zekering.id, this.zekeringDeleted);
            },
            zekeringDeleted(response) {
                EventBus.$emit(constants.EVENT_RELOAD_ZEKERINGEN);
            },
            togleForm(){
                this.showForm = !this.showForm;
            },
            addSubZekering() {
                zekeringApi.addSubZekering(this.subZekering,this.zekering.id,this.subZekeringAdded);
            },
            subZekeringAdded() {
                this.subZekering = "";
                this.showForm = false;
                EventBus.$emit(constants.EVENT_RELOAD_ZEKERINGEN);
            }
        },
        mounted(){
            this.canAddSubzekering = this.zekering.children.length < 4 && LOGDIN === "1";
            this.canDelete = ADMIN === "1";
        }
    }
</script>

<style scoped>

</style>