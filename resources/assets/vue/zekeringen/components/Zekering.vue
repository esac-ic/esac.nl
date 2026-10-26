<template>
    <div class="card">
        <div class="card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title text-muted mb-0">#{{ zekering.id }}</h5>
                <div>
                    <button v-if="zekering.children.length < 4" type="button" class="btn btn-outline-primary" @click="replying = !replying">
                        <PlusIcon class="icon" aria-hidden="true" /> Sub-zekering
                    </button>
                    <button v-if="canManage" type="button" class="btn btn-danger ml-2" aria-label="Remove zekering" @click="remove">
                        <TrashIcon class="icon" aria-hidden="true" />
                    </button>
                </div>
            </div>
            <p class="card-text">...{{ zekering.text }}</p>
            <p v-for="child in zekering.children" :key="child.id" class="card-text ml-4">...{{ child.text }}</p>
            <form v-if="replying" class="form-row ml-4 mt-3" @submit.prevent="reply">
                <div class="col-8 col-md-6">
                    <input type="text" class="form-control" v-model="text" placeholder="...Sub-zekering" aria-label="Sub-zekering" maxlength="150" required>
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">Add</button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
    import { PlusIcon, TrashIcon } from '@heroicons/vue/24/outline';
    import { confirmDialog } from '../../../js/confirm-dialog';
    import { request } from '../../../js/http';

    export default {
        name: 'Zekering',
        components: { PlusIcon, TrashIcon },
        props: {
            zekering: Object,
            canManage: Boolean,
        },
        emits: ['changed'],
        data() {
            return { replying: false, text: '' };
        },
        methods: {
            async reply() {
                await request('POST', '/api/subzekering', { text: this.text, parent_id: this.zekering.id });
                this.text = '';
                this.replying = false;
                this.$emit('changed');
            },
            async remove() {
                if (await confirmDialog({ title: 'Remove zekering', message: 'Remove this zekering?', button: 'Remove' })) {
                    await request('DELETE', `/api/zekeringen/${this.zekering.id}`);
                    this.$emit('changed');
                }
            },
        },
    };
</script>
