<template>
    <section class="py-3">
        <div class="container">
            <Zekering v-for="zekering in paginatedZekeringen" :key="zekering.id" :zekering="zekering"/>

            <nav aria-label="Zekeringen pagination" v-if="totalPages > 1">
                <ul class="pagination justify-content-center flex-wrap" :class="{'pagination-sm': isMobile}">
                    <li class="page-item" :class="{disabled: currentPage === 1}">
                        <a class="page-link" href="#" aria-label="Previous" v-on:click.prevent="previousPage">
                            <span aria-hidden="true">&laquo;</span>
                        </a>
                    </li>
                    <li class="page-item" v-for="(page, index) in visiblePages" :key="index"
                        :class="{active: currentPage === page, disabled: page === ELLIPSIS}">
                        <span class="page-link" v-if="page === ELLIPSIS">&hellip;</span>
                        <a class="page-link" href="#" v-else v-on:click.prevent="currentPage = page">{{ page }}</a>
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
    // Amount of pages shown on each side of the current page
    const PAGE_LIMIT = 2;
    const PAGE_LIMIT_MOBILE = 1;
    // Bootstrap's sm breakpoint
    const mobileQuery = window.matchMedia('(max-width: 575.98px)');
    const ELLIPSIS = '...';

    export default {
        name: "Zekeringen",
        components: {Zekering},
        data() {
            return {
                zekeringen: [],
                currentPage: 1,
                isMobile: mobileQuery.matches,
                ELLIPSIS,
            }
        },
        computed: {
            totalPages() {
                return Math.max(1, Math.ceil(this.zekeringen.length / PER_PAGE));
            },
            paginatedZekeringen() {
                const start = (this.currentPage - 1) * PER_PAGE;
                return this.zekeringen.slice(start, start + PER_PAGE);
            },
            visiblePages() {
                const limit = this.isMobile ? PAGE_LIMIT_MOBILE : PAGE_LIMIT;
                const first = Math.max(1, this.currentPage - limit);
                const last = Math.min(this.totalPages, this.currentPage + limit);
                const pages = [];

                if (first > 1) {
                    pages.push(1);
                }
                if (first > 2) {
                    pages.push(ELLIPSIS);
                }
                for (let n = first; n <= last; n++) {
                    pages.push(n);
                }
                if (last < this.totalPages - 1) {
                    pages.push(ELLIPSIS);
                }
                if (last < this.totalPages) {
                    pages.push(this.totalPages);
                }

                return pages;
            }
        },
        methods: {
            loadZekeringen: function (){
                zekeringApi.getZekeringen(this.setZekeringen);
            },
            setZekeringen: function(zekeringen){
                this.zekeringen = zekeringen.zekeringen;
                // Stay on the current page after a reload, unless it no longer exists (e.g. after a delete)
                this.currentPage = Math.min(this.currentPage, this.totalPages);
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
            },
            updateIsMobile: function(event) {
                this.isMobile = event.matches;
            }
        },
        mounted(){
            this.loadZekeringen();
            EventBus.on(constants.EVENT_RELOAD_ZEKERINGEN, this.loadZekeringen);
            mobileQuery.addEventListener('change', this.updateIsMobile);
        },
        beforeUnmount(){
            mobileQuery.removeEventListener('change', this.updateIsMobile);
        }
    }
</script>

<style scoped>

</style>
