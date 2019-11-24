<template>
    <div>
        <div class="form-group">
            <label>Categoriën</label>
            <select class="form-control" v-model="selectedCategorie" v-on:change="categorieChanged">
                <option value="">Alles</option>
                <option v-for="categorie in categories" :key="categorie.id" :value="categorie.id">{{categorie.name}}</option>
            </select>
        </div>
        <div class="form-group">
            <label>Start datum</label>
            <datepicker v-model="startDate" input-class="form-control" :format="dateFormat" v-on:input="startDateChanged"></datepicker>
        </div>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex';
    import Datepicker from 'vuejs-datepicker';

    export default {
        name: "AgendaFilters",
        data(){
            return {
                selectedCategorie: "",
                startDate:  new Date(),
            }
        },
        components: {
            Datepicker
        },
        computed: {
            ...mapGetters({
                categories : "categories"
            })
        },
        methods: {
            ...mapActions([
                'fetchAgendaCategories',
                'setSelectedCategory',
                'setStartDate',
            ]),
            dateFormat(date) {
                return (new Date(date)).toLocaleDateString('nl',{ year: 'numeric', month: 'numeric', day: 'numeric' });
            },
            categorieChanged(){
                this.setSelectedCategory(this.selectedCategorie);
            },
            startDateChanged(){
                this.setStartDate((new Date(this.startDate)).toLocaleDateString('nl',{ year: 'numeric', month: 'numeric', day: 'numeric' }));
            }
        },
        mounted () {
            this.fetchAgendaCategories();
        }
    }
</script>

<style scoped>

</style>