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
            <input type="date" class="form-control" v-model="startDate" v-on:change="startDateChanged">
        </div>
    </div>
</template>

<script>
    import { mapActions, mapState } from 'pinia';
    import { useAgendaStore } from '../../store';

    function toIsoDate(date) {
        const pad = n => String(n).padStart(2, '0');
        return `${date.getFullYear()}-${pad(date.getMonth() + 1)}-${pad(date.getDate())}`;
    }

    // "YYYY-MM-DD" "d-m-Y"
    function isoDateToDMY(isoDate) {
        const [year, month, day] = isoDate.split('-');
        return `${day}-${month}-${year}`;
    }

    export default {
        name: "AgendaFilters",
        data(){
            return {
                selectedCategorie: "",
                startDate: toIsoDate(new Date()),
                allCategoriesText: 'All',
                categoriesText: 'Categories',
                startDateText: 'Start date'
            }
        },
        computed: {
            ...mapState(useAgendaStore, ['categories'])
        },
        methods: {
            ...mapActions(useAgendaStore, [
                'fetchAgendaCategories',
                'setSelectedCategory',
                'setStartDate',
            ]),
            categorieChanged(){
                this.setSelectedCategory(this.selectedCategorie);
            },
            startDateChanged(){
                this.setStartDate(isoDateToDMY(this.startDate));
            }
        },
        mounted () {
            this.fetchAgendaCategories();
            
            this.allCategoriesText = 'All';
            this.categoriesText = 'Categories';
            this.startDateText=  'Start date';
        }
    }
</script>

<style scoped>

</style>
