<template>
    <div>
        <div class="container intro-container">
            <div class="row d-flex align-items-stretch">
                <div class="col-sm-8 d-flex flex-wrap">
                    <div class="card w-100">
                        <div class="card-body">
                        <button type="button" class="btn btn-outline-primary float-right" data-toggle="modal" data-target="#ExportModal">
                            <span class="ion-android-download"></span> {{exportText}}
                        </button>
                        <h2 class="card-title">{{ titleText }}</h2>
                         <p class="card-text" v-html="description"></p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 d-flex flex-wrap">
                    <div class="card w-100">
                        <div class="card-body">
                            <h4 class="card-title">{{filterText}}</h4>
                            <agenda-filters :lang="lang" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <section class="py-3">
            <div class="container">
                <div class="row d-flex align-items-stretch align-items-center">
                    <agenda-item v-for="agendaItem in agendaItems" :key="agendaItem.id" :agenda="agendaItem" :lang="lang" />
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
            
            //translate based on the language
            console.log(this.lang);
            if (this.lang == 'en') {
                this.titleText = 'Activities';
                this.exportText = 'Export';
                this.filterText = 'Filters';
            } else if (this.lang == 'nl') {
                this.titleText = 'Activiteiten';
                this.exportText = 'Exporteren';
                this.filterText = "Filters";
            } else {
                //currently the default language is dutch
                this.titleText = 'Activiteiten';
                this.exportText = 'Exporteren';
                this.filterText = "Filters";
            }
        },
        data() {
            return {
                description: DESCRIPTION,
                lang: LANG,
                titleText: 'Activiteiten',
                exportText: 'Exporteren',
                filterText: "Filters",
            }
        }
    }
</script>

<style scoped>

</style>