try {
    window.$ = window.jQuery = require('jquery');
    window.swal = require('sweetalert2');
    window.toastr = require('toastr');
    require('./bootstrap');
    require('bootstrap-table');
    require('bootstrap4-toggle');
    require('@fancyapps/fancybox');
 } catch (e) {}

