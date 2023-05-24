<template>
    <section class="py-3">
        <div class="container">
            <Zekering v-for="zekering in paginated('zekeringenList')" :key="zekering.id" :zekering="zekering"/>

            <paginate
                    name="zekeringenList"
                    :list="zekeringen"
                    :per="10"
            >
            </paginate>
            <paginate-links for="zekeringenList" :async="true" :limit="2" :show-step-links="true" :classes="{
            'ul': ['pagination','justify-content-center'],
            'li': 'page-item',
            'a': 'page-link',
            }"></paginate-links>
        </div>
    </section>
</template>

<script>
    import zekeringApi from '../api/zekering';
    import Zekering from './Zekering';
    import EventBus from '../event-bus';
    import * as constants from "../constants";

    export default {
        name: "Zekeringen",
        components: {Zekering},
        data() {
            return {
                zekeringen: [],
                paginate: ['zekeringenList']
            }
        },
        methods: {
            loadZekeringen: function (){
                zekeringApi.getZekeringen(this.setZekeringen);
            },
            setZekeringen: function(zekeringen){
                this.zekeringen = zekeringen.zekeringen;
            }
        },
        mounted(){
            this.loadZekeringen();
             EventBus.$on(constants.EVENT_RELOAD_ZEKERINGEN, this.loadZekeringen);
        }
    }
</script>

<style scoped>

</style>