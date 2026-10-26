<template>
    <section class="py-3">
        <div class="container">
            <Zekering v-for="zekering in shown" :key="zekering.id" :zekering="zekering" :can-manage="canManage" @changed="load" />

            <nav v-if="pages > 1" class="d-flex justify-content-center align-items-center" aria-label="Zekeringen pages">
                <button type="button" class="btn btn-outline-primary" :disabled="page === 1" @click="page--">Newer</button>
                <span class="mx-3 text-muted">Page {{ page }} of {{ pages }}</span>
                <button type="button" class="btn btn-outline-primary" :disabled="page === pages" @click="page++">Older</button>
            </nav>
        </div>
    </section>
</template>

<script>
    import { request } from '../../../js/http';
    import Zekering from './Zekering.vue';

    const PER_PAGE = 10;

    export default {
        name: 'Zekeringen',
        components: { Zekering },
        props: {
            canManage: Boolean,
        },
        data() {
            return { zekeringen: [], page: 1 };
        },
        computed: {
            pages() {
                return Math.ceil(this.zekeringen.length / PER_PAGE);
            },
            shown() {
                return this.zekeringen.slice((this.page - 1) * PER_PAGE, this.page * PER_PAGE);
            },
        },
        mounted() {
            this.load();
        },
        methods: {
            async load() {
                this.zekeringen = (await request('GET', '/api/zekeringen')).zekeringen;
                // Stay on this page after a change, unless it no longer exists
                this.page = Math.max(1, Math.min(this.page, this.pages));
            },
        },
    };
</script>
