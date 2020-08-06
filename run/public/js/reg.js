Vue.use(vuelidate)

// let data = {
//     username: null,
//     gender: null,
//     year: null,
//     month: null,
//     day: null,
//     phone: '028745789',
//     mobile: '0912356789',
//     email: 'luke@cc.com',
//     county: null,
//     district: null,
//     address: '東興路51號',
//     agreeTerms: 0,
//     years: 100,
//     months: [31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31],
// }

let data = {
    username: 'tset',
    gender: 'male',
    year: '1977',
    month: '1',
    day: '22',
    phone: '028745789',
    mobile: '0912356789',
    email: 'luke@cc.com',
    county: null,
    district: null,
    address: '東興路51號',
    agreeTerms: 0,
    years: 100,
    months: [31, 29, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31],
}

// vuelidate
Vue.use(window.vuelidate.default);

let required = validators.required;
let email = validators.email;
let minLength = validators.minLength;
let numeric = validators.numeric;
let cellPhoneReg = "(09[1-9][0-9](-)?\d{6})";
let cellPhoneDesc = "需為09開頭共10位數，例：0987654321";
let officePhoneReg = "(0[2-8][1-9]{0,2}-[2-9][0-9]{4,7}\s*((#\s*)[0-9]{0,6})?)";
let officePhoneDesc = "需包含區碼：區碼-電話號碼 #分機，例：02-87654321 #123";

let years = {}
let days = {}
let counties = []
let districts = []
let axiosConfig = {
    headers: {
        'Content-Type': 'application/json;charset=UTF-8',
        "Access-Control-Allow-Origin": "*",
    }
};
let vm = new Vue({
    el: "#app",
    data: data,
    computed: {
        formCheck: function () {
            // isLoading().loading();
            // isLoading().remove();

            if (!this.$v.$invalid) return 'submit'
            return 'button'
        },
        getDays: function () {
            if (this.month) {
                return this.months[this.month - 1]
            }
        },
        getCounty: function () {
            for (let prop in zipcode) {
                counties.push(prop);
            }
            return counties;
        },
        getDistrict: function () {
            for (let sub_prop in zipcode[this.county]) {
                districts.push(sub_prop);
            }
            return districts;
        },


    },
    watch: {},
    methods: {
        validateAlert:function(){
            if (this.$v.$invalid.agreeTerms){
                alert('資料格式有誤');
            }
            if (this.$v.agreeTerms.$invalid){
                alert('請勾選同意');
            }
        },
        submitHandler: function () {

            axios.post('http://www.tfdacos.com.tw/run/api/register/save', {
                username: this.username,
                gender: this.gender,
                birthday: this.year + '/' + this.month + '/' + this.day,
                phone: this.phone,
                mobile: this.mobile,
                email: this.email,
                county: this.county ,
                district: this.district ,
                address: this.address

            }, axiosConfig)
                .then(function (response) {
                    console.log(response);
                })
                .catch(function (error) {
                    console.log(error);
                });
        },
        resetDistrict: function () {
            counties = []
            districts = []
            this.district = null;
        },
        formValidation: function () {

        },
        status: function (validation) {
            return {
                error: validation.$error,
                dirty: validation.$dirty
            }
        }
    },
    validations: {
        username: {
            required
        },
        year: {
            required
        },
        month: {
            required
        },
        day: {
            required
        },
        gender: {
            required
        },
        phone: {
            numeric,
            minLength: minLength(5)
        },
        mobile: {
            numeric,
            required,
            minLength: minLength(5)
        },
        email: {
            required
        },
        address: {
            required
        },
        agreeTerms: {
            checked: value => value === true
        }
    }
})
