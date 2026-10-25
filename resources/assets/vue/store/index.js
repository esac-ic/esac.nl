import { defineStore } from 'pinia';
import agendaApi from '../agenda/api/agenda';

export const useAgendaStore = defineStore('agenda', {
    state: () => ({
        categories: [],
        agendaItems: [],
        agendaTotalItemCount: 0,
        selectedCategory: "",
        startDate: new Date().toLocaleDateString('nl', { year: 'numeric', month: 'numeric', day: 'numeric' }),
        selectedPage: 1,
    }),
    actions: {
        fetchAgendaItems() {
            agendaApi.getAgenda(
                this.startDate,
                this.selectedCategory,
                this.selectedPage,
                agendaItems => {
                    this.agendaItems = agendaItems.agendaItems;
                    this.agendaTotalItemCount = agendaItems.agendaItemCount;
                }
            );
        },
        fetchAgendaCategories() {
            agendaApi.getCategories(categories => {
                this.categories = categories;
            });
        },
        setSelectedCategory(selectedCategory) {
            this.selectedCategory = selectedCategory;
            this.fetchAgendaItems();
        },
        setStartDate(startDate) {
            this.startDate = startDate;
            this.fetchAgendaItems();
        },
        setSelectedAgendaPage(selectedPage) {
            this.selectedPage = selectedPage;
            this.fetchAgendaItems();
        },
    },
});
