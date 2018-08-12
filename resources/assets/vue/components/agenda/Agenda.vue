<template>
    <div>
        <div class="container intro-container">
            <div class="row d-flex align-items-stretch">
                <div class="col-sm-8 d-flex flex-wrap">
                    <div class="card w-100">
                        <div class="card-body">
                        <h2 class="card-title">Activiteiten</h2>
                         <p class="card-text" v-html="description"></p>
                        </div>
                    </div>     
                </div>
                <div class="col-sm-4 d-flex flex-wrap">
                    <div class="card w-100">
                        <div class="card-body">
                            <h4 class="card-title">Filters</h4>
                            <agenda-filters/>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <section class="py-3">
            <div class="container">
                <div class="row d-flex align-items-stretch align-items-center">
                    <agenda-item v-for="agendaItem in agendaItems" :key="agendaItem.id" :agenda="agendaItem"/>
                </div>
                <agenda-pagination/>
            </div>
        </section>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex';
    import AgendaFilters from './AgendaFilters';
    import AgendaItem from './AgendaItem';
    import AgendaPagination from './AgendaPagination';

    export default {
        name: "Agenda",
        components: {
            AgendaFilters,
            AgendaItem,
            AgendaPagination,
        },
        computed: {
            ...mapGetters({
                agendaItems : "agendaItems"
            })
        },
        methods: {
            ...mapActions([
                'fetchAgendaItems',
            ])
        },
        mounted () {
            this.fetchAgendaItems();
        },
        data() {
            return {
                description: DESCRIPTION
            }
        }
    }
</script>

<style scoped>

</style>