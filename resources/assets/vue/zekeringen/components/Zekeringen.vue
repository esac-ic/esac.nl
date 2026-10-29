<template>
    <section class="py-3">
        <div class="container">
            <Zekering v-for="zekering in zekeringen" :key="zekering.id" :zekering="zekering" :can-manage="canManage" @changed="load(page)" />

            <nav v-if="pages > 1" class="d-flex justify-content-center align-items-center" aria-label="Zekeringen pages">
                <button type="button" class="btn btn-outline-primary" :disabled="page === 1" @click="load(page - 1)">Newer</button>
                <span class="mx-3 text-muted">Page {{ page }} of {{ pages }}</span>
                <button type="button" class="btn btn-outline-primary" :disabled="page === pages" @click="load(page + 1)">Older</button>
            </nav>
        </div>
    </section>
</template>

<script>
    import { request } from '../../../js/http';
    import Zekering from './Zekering.vue';

    export default {
        name: 'Zekeringen',
        components: { Zekering },
        props: {
            canManage: Boolean,
        },
        data() {
            return { zekeringen: [], page: 1, pages: 0 };
        },
        mounted() {
            this.load(1);
        },
        methods: {
            async load(page) {
                const result = await request('GET', '/api/zekeringen?' + new URLSearchParams({ page }));
                // A removal can empty the last page
                if (result.data.length === 0 && page > 1) {
                    return this.load(result.meta.last_page);
                }
                this.zekeringen = result.data;
                this.page = result.meta.current_page;
                this.pages = result.meta.last_page;
            },
        },
    };
</script>
