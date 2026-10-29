<template>
    <div>
        <div class="container intro-container">
            <div class="row">
                <div class="col-sm-8 d-flex">
                    <div class="card w-100">
                        <div class="card-body">
                            <button type="button" class="btn btn-outline-primary float-right" data-toggle="modal" data-target="#export-calendar-modal">
                                <ArrowDownTrayIcon class="icon" aria-hidden="true" /> Export
                            </button>
                            <h2 class="card-title">Events</h2>
                            <div class="card-text" v-html="description"></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 d-flex">
                    <div class="card w-100">
                        <div class="card-body">
                            <h4 class="card-title">Filters</h4>
                            <div class="form-group">
                                <label for="event-category">Category</label>
                                <select id="event-category" class="form-control" v-model="category" @change="load(1)">
                                    <option value="">All</option>
                                    <option v-for="option in categories" :key="option.id" :value="option.id">{{ option.name }}</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="event-from">From</label>
                                <input id="event-from" type="date" class="form-control" v-model="from" @change="load(1)">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <section class="py-3">
            <div class="container">
                <div class="row">
                    <AgendaItem v-for="event in events" :key="event.id" :event="event" />
                </div>
                <p v-if="loaded && !events.length" class="text-muted text-center">No events match these filters.</p>
                <nav v-if="pages > 1" aria-label="Event pages">
                    <ul class="pagination justify-content-center">
                        <li v-for="n in pages" :key="n" class="page-item" :class="{ active: n === page }">
                            <button type="button" class="page-link" @click="load(n)">{{ n }}</button>
                        </li>
                    </ul>
                </nav>
            </div>
        </section>
    </div>
</template>

<script>
    import { ArrowDownTrayIcon } from '@heroicons/vue/24/outline';
    import { request } from '../../../js/http';
    import AgendaItem from './AgendaItem.vue';

    // Today as the date input writes it, YYYY-MM-DD
    const today = () => {
        const date = new Date();
        return [date.getFullYear(), date.getMonth() + 1, date.getDate()].map(n => String(n).padStart(2, '0')).join('-');
    };

    export default {
        name: 'Agenda',
        components: { AgendaItem, ArrowDownTrayIcon },
        props: {
            // The events page's intro, as HTML
            description: String,
            categories: Array,
        },
        data() {
            return { events: [], page: 1, pages: 0, category: '', from: today(), loaded: false };
        },
        mounted() {
            this.load(1);
        },
        methods: {
            async load(page) {
                const result = await request('GET', '/api/events?' + new URLSearchParams({ page, from: this.from, category: this.category }));
                this.events = result.data;
                this.page = result.meta.current_page;
                this.pages = result.meta.last_page;
                this.loaded = true;
            },
        },
    };
</script>
