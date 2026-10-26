import http from "../../../js/http";
import * as constants from "../constants";

export default {
    getCategories(cb){
        http.get(constants.BASE_URL + "/agendaCategories")
            .then(response => {
                // JSON responses are automatically parsed.
                cb(response.data);
            })
            .catch(e => {
                console.error(e);
                cb([]);
            });
    },
    getAgenda(startDate,category,pageNumber,cb){
        let url = constants.BASE_URL + "/agenda";
           url += "?start=" + (pageNumber - 1) * constants.AGENDA_ITEMS_ON_PAGE;

        if(startDate !== ""){
            url += "&startDate=" + startDate;
        }
        if(category !== ""){
            url += "&category=" + category;
        }
        http.get(url)
            .then(response => {
                // JSON responses are automatically parsed.
                cb(response.data);
            })
            .catch(e => {
                console.error(e);
                cb([]);
            });
    }
}