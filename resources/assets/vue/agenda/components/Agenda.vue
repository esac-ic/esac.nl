<template>
    <div>
        <div class="container intro-container">
            <div class="row d-flex align-items-stretch">
                <div class="col-sm-8 d-flex flex-wrap">
                    <div class="card w-100">
                        <div class="card-body">
                        <button type="button" class="btn btn-outline-primary float-right" data-toggle="modal" data-target="#export-calendar-modal">
                            <ArrowDownTrayIcon class="icon" aria-hidden="true" /> Export
                        </button>
                        <h2 class="card-title">Events</h2>
                         <p class="card-text" v-html="description"></p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 d-flex flex-wrap">
                    <div class="card w-100">
                        <div class="card-body">
                            <h4 class="card-title">Filters</h4>
                            <agenda-filters />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <section class="py-3">
            <div class="container">
                <div class="row d-flex align-items-stretch align-items-center">
                    <agenda-item v-for="agendaItem in agendaItems" :key="agendaItem.id" :agenda="agendaItem" />
                </div>
                <p v-if="loaded && !agendaItems.length" class="text-muted text-center">No events match these filters.</p>
                <agenda-pagination/>
            </div>
        </section>
    </div>
</template>

<script>
import { ArrowDownTrayIcon } from '@heroicons/vue/24/outline';
import { mapActions, mapState } from 'pinia';
import { useAgendaStore } from '../../store';
import AgendaFilters from './AgendaFilters.vue';
import AgendaItem from './AgendaItem.vue';
import AgendaPagination from './AgendaPagination.vue';
    
    export default {
        name: "Agenda",
        components: {
            AgendaFilters,
            AgendaItem,
            AgendaPagination,
            ArrowDownTrayIcon,
        },
        props: {
            // The events page's intro text, as HTML
            description: String,
        },
        computed: {
            ...mapState(useAgendaStore, ['agendaItems'])
        },
        methods: {
            ...mapActions(useAgendaStore, ['fetchAgendaItems']),
        },
        mounted () {
            this.fetchAgendaItems();
        },
        watch: {
            // The first fetch has answered, so an empty list means there are no events.
            agendaItems () {
                this.loaded = true;
            },
        },
        data() {
            return {
                loaded: false,
            }
        }
    }
</script>
