<template>
    <section class="py-3">
        <div class="container">
            <Zekering v-for="zekering in paginatedZekeringen" :key="zekering.id" :zekering="zekering"/>

            <nav aria-label="Zekeringen pagination" v-if="totalPages > 1">
                <ul class="pagination justify-content-center">
                    <li class="page-item" :class="{disabled: currentPage === 1}">
                        <a class="page-link" href="#" aria-label="Previous" v-on:click.prevent="previousPage">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <li class="page-item" v-for="n in totalPages" :key="n" :class="{active: currentPage === n}">
                        <a class="page-link" href="#" v-on:click.prevent="currentPage = n">{{ n }}</a>
                    </li>
                    <li class="page-item" :class="{disabled: currentPage === totalPages}">
                        <a class="page-link" href="#" aria-label="Next" v-on:click.prevent="nextPage">
                            <span aria-hidden="true">&raquo;</span>
                        </a>
                    </li>
                </ul>
            </nav>
        </div>
    </section>
</template>

<script>
    import zekeringApi from '../api/zekering';
    import Zekering from './Zekering.vue';
    import EventBus from '../event-bus';
    import * as constants from "../constants";

    const PER_PAGE = 10;

    export default {
        name: "Zekeringen",
        components: {Zekering},
        data() {
            return {
                zekeringen: [],
                currentPage: 1,
            }
        },
        computed: {
            totalPages() {
                return Math.max(1, Math.ceil(this.zekeringen.length / PER_PAGE));
            },
            paginatedZekeringen() {
                const start = (this.currentPage - 1) * PER_PAGE;
                return this.zekeringen.slice(start, start + PER_PAGE);
            }
        },
        methods: {
            loadZekeringen: function (){
                zekeringApi.getZekeringen(this.setZekeringen);
            },
            setZekeringen: function(zekeringen){
                this.zekeringen = zekeringen.zekeringen;
                this.currentPage = 1;
            },
            nextPage: function() {
                if (this.currentPage < this.totalPages) {
                    this.currentPage++;
                }
            },
            previousPage: function() {
                if (this.currentPage > 1) {
                    this.currentPage--;
                }
            }
        },
        mounted(){
            this.loadZekeringen();
            EventBus.on(constants.EVENT_RELOAD_ZEKERINGEN, this.loadZekeringen);
        }
    }
</script>

<style scoped>

</style>
