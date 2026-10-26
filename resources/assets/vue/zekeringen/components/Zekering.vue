<template>
    <div class="card">
        <div class="card-body">
            <div class="row mb-3 justify-content-between align-items-center">
                <div class="col-auto">
                    <h5 class="card-title text-muted">#{{zekering.id}}</h5>
                </div>
                <div class="col-auto">
                    <button v-on:click="toggleForm" class="btn btn-outline-primary" v-if="zekering.children.length < 4">
                        <PlusIcon class="icon" aria-hidden="true" />
                        Sub-zekering
                    </button>
                    <button v-on:click="deleteZekering" class="btn btn-danger ml-2" v-if="canManage" aria-label="Remove zekering">
                        <TrashIcon class="icon" aria-hidden="true" />
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
                    <button class="btn btn-primary" v-on:click="addSubZekering">Add</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { PlusIcon, TrashIcon } from '@heroicons/vue/24/outline';
    import { confirmDialog } from '../../../js/confirm-dialog';
    import zekeringApi from '../api/zekering';
    import subZekering from './SubZekering.vue';

    export default {
        name: "Zekering",
        components: {subZekering, PlusIcon, TrashIcon},
        emits: ['changed'],
        data(){
            return {
                showForm: false,
                subZekering: "",
            }
        },
        props: {
            zekering: Object,
            canManage: Boolean,
        },
        methods: {
            async deleteZekering(){
                if (await confirmDialog({title: 'Remove zekering', message: 'Remove this zekering?', button: 'Remove'}))
                {
                    zekeringApi.deleteZekering(this.zekering.id, this.zekeringDeleted);
                }
            },
            zekeringDeleted() {
                this.$emit('changed');
            },
            toggleForm(){
                this.showForm = !this.showForm;
            },
            addSubZekering() {
                zekeringApi.addSubZekering(this.subZekering,this.zekering.id,this.subZekeringAdded);
            },
            subZekeringAdded() {
                this.subZekering = "";
                this.showForm = false;
                this.$emit('changed');
            }
        }
    }
</script>
