import http from "../../../js/http";
import * as constants from "../constants";

export default {
    getZekeringen(cb){
        http.get(constants.BASE_URL + "/zekeringen")
            .then(response => {
                // JSON responses are automatically parsed.
                cb(response.data);
            })
            .catch(e => {
                console.error(e);
                cb([]);
            });
    },
    deleteZekering(id, cb){
        http.delete(constants.BASE_URL + "/zekeringen/" + id)
            .then(response => {
                cb(response);
            });
    },
    addSubZekering(zekering,parent_id,cb) {
        http.post(constants.BASE_URL + "/subzekering",{
                text : zekering,
                parent_id : parent_id
            })
            .then(response => {
                cb();
            })
            .catch(e => {
                console.error(e);
                cb([]);
            });
    }
}