import * as TYPES from '../mutation-types';
import agendaApi from '../../agenda/api/agenda';
// initial state
const state = {
    categories: [],
    agendaItems: [],
    agendaTotalItemCount: 0,
    selectedCategory: "",
    startDate: new Date().toLocaleDateString('nl',{ year: 'numeric', month: 'numeric', day: 'numeric' }),
    selectedPage: 1,
};

const getters = {
    categories: state => state.categories,
    agendaItems: state => state.agendaItems,
    agendaTotalItemCount: state => state.agendaTotalItemCount,
    selectedPage: state => state.selectedPage
};

const actions = {
    fetchAgendaItems: context => {
        console.log('load agenda items');
        agendaApi.getAgenda(
            context.state.startDate,
            context.state.selectedCategory,
            context.state.selectedPage,
            agendaItems => {
                context.commit(TYPES.SET_AGENDA_ITEMS,agendaItems.agendaItems);
                context.commit(TYPES.SET_AGENDA_TOTAL_COUNT,agendaItems.agendaItemCount);
            }
        );
    },
    fetchAgendaCategories: context => {
        agendaApi.getCategories(categories => {
            context.commit(TYPES.SET_AGENDA_CATEGORIES,categories);
        });
    },
    setSelectedCategory: (context,selectedCategory) => {
        context.commit(TYPES.SET_SELECTED_CATEGORY,selectedCategory);
        context.dispatch('fetchAgendaItems');
    },
    setStartDate: (context,startDate) => {
        context.commit(TYPES.SET_AGENDA_START_DATE,startDate);
        context.dispatch('fetchAgendaItems');
    },
    setSelectedAgendaPage: (context,selectedPage) => {
        context.commit(TYPES.SET_SELECTED_AGENDA_PAGE,selectedPage);
        context.dispatch('fetchAgendaItems');
    }
};

const mutations = {
    [TYPES.SET_AGENDA_CATEGORIES] (state,categories) {
        state.categories = categories;
    },
    [TYPES.SET_AGENDA_ITEMS] (state,agendaItems) {
        state.agendaItems = agendaItems;
    },
    [TYPES.SET_SELECTED_CATEGORY] (state,selectedCategory) {
        state.selectedCategory = selectedCategory;
    },
    [TYPES.SET_AGENDA_START_DATE] (state,startDate) {
        state.startDate = startDate;
    },
    [TYPES.SET_AGENDA_TOTAL_COUNT] (state,agendaTotalItemCount) {
        state.agendaTotalItemCount = agendaTotalItemCount;
    },
    [TYPES.SET_SELECTED_AGENDA_PAGE] (state,selectedPage) {
        state.selectedPage = selectedPage;
    },
};

export default {
    state,
    getters,
    actions,
    mutations,
}