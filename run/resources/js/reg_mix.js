import Vue from 'vue';
import Twzipcode from 'vue2-twzipcode';

let data = {
    username: 'luke',
    year: null,
    month: null,
    day: null,
    phone: '028745789',
    mobile: '0912356789',
    email: 'luke@cc.com',
    address:'東興路51號',
    agreeTerms: false,
    years: 100,
    months: [31,29,31,30,31,30,31,31,30,31,30,31],
}
let years = {}
let days = {}

let vm = new Vue({
    el:"#app",
    data:data,
    computed:{
        getDays(){
            if(this.month) {
                return this.months[this.month-1]
            }
        }
    },
    watch: {

    },
    methods: {
        result({ zipcode, county, district }) {
            console.log(zipcode, county, district);
        },
        submitHandler : function() {
            axios.post('https://tdf_2020.dev/register/save', {
                firstName: 'Fred',
                lastName: 'Flintstone'
            })
                .then(function (response) {
                    console.log(response);
                })
                .catch(function (error) {
                    console.log(error);
                });
        }
    },
    components: {
        Twzipcode
    },
})
