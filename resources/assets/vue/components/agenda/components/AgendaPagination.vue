<template>
    <nav aria-label="Page navigation example">
        <ul class="pagination justify-content-center">
            <li class="page-item" :class="{disabled : previousPageDisabled}">
                <a class="page-link" href="#" aria-label="Previous" v-on:click="previousPage()">
                    <span aria-hidden="true">&laquo;</span>
                    <span class="sr-only">Previous</span>
                </a>
            </li>
            <li class="page-item" v-for="n in getAmountOfPages()" :class="{active : selectedPage == n}" v-on:click="pageSelected(n)" :key="n">
                <a class="page-link" href="#">{{n}}</a>
            </li>
            <li class="page-item" :class="{disabled : nextPageDisabled}" v-on:click="nextPage()">
                <a class="page-link" href="#" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                    <span class="sr-only">Next</span>
                </a>
            </li>
        </ul>
    </nav>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex';
    import {AGENDA_ITEMS_ON_PAGE} from '../constants';

    export default {
        name: "AgendaPagination",
        data(){
            return {
                previousPageDisabled: true,
                nextPageDisabled: false,
            }
        },
        computed: {
            ...mapGetters({
                agendaTotalItemCount : "agendaTotalItemCount",
                selectedPage : "selectedPage"
            })
        },
        methods: {
            ...mapActions([
                'setSelectedAgendaPage',
            ]),
            getAmountOfPages: function(){
                return Math.ceil(this.agendaTotalItemCount / AGENDA_ITEMS_ON_PAGE);
            },
            pageSelected: function(n){
                this.setSelectedAgendaPage(n);
            },
            nextPage: function(){
                if(Math.ceil(this.agendaTotalItemCount / AGENDA_ITEMS_ON_PAGE) !== this.selectedPage){
                    this.setSelectedAgendaPage(this.selectedPage + 1);
                }

                this.nextPageDisabled = Math.ceil(this.agendaTotalItemCount / AGENDA_ITEMS_ON_PAGE) === this.selectedPage;
                this.previousPageDisabled = this.selectedPage === 0;
            },
            previousPage: function(){
                if(this.selectedPage !== 0){
                    this.setSelectedAgendaPage(this.selectedPage - 1);
                }

                this.previousPageDisabled = this.selectedPage !== 0;
                this.nextPageDisabled = Math.ceil(this.agendaTotalItemCount / AGENDA_ITEMS_ON_PAGE) === this.selectedPage;
            }
        },
    }
</script>

<style scoped>

</style>