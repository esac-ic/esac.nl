//This file contains the api methode for managing ships
import axios from "axios/index";
import * as constants from "../constants";

export default {
    getZekeringen(cb){
        axios.get(constants.BASE_URL + "/zekeringen")
            .then(response => {
                // JSON responses are automatically parsed.
                cb(response.data);
            })
            .catch(e => {
                console.error(e);
                cb([]);
            });
    },
    deleteZekering(id){
        axios.delete(constants.BASE_URL + "/zekeringen/" + id);
    },
    addSubZekering(zekering,parent_id,cb) {
        axios.post(constants.BASE_URL + "/subzekering",{
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