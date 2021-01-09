import Axios from "axios"
import { data } from "jquery";

export default {
    data() {
        return {

        }
    },

    methods: {
        async callApi(method, paramUrl , dataObj) {
            try{
                return await axios({
                    method : 'post',
                    url    : paramUrl,
                    data   : dataObj
                });
            }
            catch(e) {
                return e.response
            }
        },

        i (desc , title="Hey") {
            this.$Notice.info({
                title: title,
                desc: desc
            });
        },
        s (desc , title="Great!") {
            this.$Notice.success({
                ttitle: title,
                desc: desc
            });  
        },
        w (desc , title="Oops!") {
            this.$Notice.warning({
                title: title,
                desc: desc
            });
        },
        e (desc , title="Hey") {
            this.$Notice.error({
                title: title,
                desc: desc
            });
        },
        swr (desc="Something went wrong! Pleas Try again" , title="Oops!") {
            this.$Notice.error({
                title: title,
                desc: desc
            });
        }
    }
}