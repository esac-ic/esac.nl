<template>
    <div>
        <div class="form-group">
            <label>{{categoriesText}}</label>
            <select class="form-control" v-model="selectedCategorie" v-on:change="categorieChanged">
                <option value="">{{allCategoriesText}}</option>
                <option v-for="categorie in categories" :key="categorie.id" :value="categorie.id">{{categorie.name}}</option>
            </select>
        </div>
        <div class="form-group">
            <label>{{startDateText}}</label>
            <datepicker v-model="startDate" input-class="form-control" :format="dateFormat" v-on:input="startDateChanged"></datepicker>
        </div>
    </div>
</template>

<script>
    import { mapGetters, mapActions } from 'vuex';
    import Datepicker from 'vuejs-datepicker';

    export default {
        name: "AgendaFilters",
        props:[
            'lang'
        ],
        data(){
            return {
                selectedCategorie: "",
                startDate:  new Date(),
                allCategoriesText: 'Alles',
                categoriesText: 'Categorien',
                startDateText: 'Start datum'
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
            
            //set the language
            if (this.lang == 'en') {
                this.allCategoriesText = 'All';
                this.categoriesText = 'Categories';
                this.startDateText=  'Start date';
            } else if (this.lang == 'nl') {
                this.allCategoriesText = 'Alles';
                this.categoriesText = 'Categorien';
                this.startDateText=  'Start datum';
            } else {
                //currently the default language is dutch
                this.allCategoriesText = 'Alles';
                this.categoriesText = 'Categorien';
                this.startDateText=  'Start datum';
            }
        }
    }
</script>

<style scoped>

</style>