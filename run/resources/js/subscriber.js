try {
    window.toastr = require('toastr');
    window.moment = require('moment');
    window.flatpickr = require("flatpickr");
    window.flatpickr_tw = require("flatpickr/dist/l10n/zh-tw.js").default.zh_tw;
    window.inputSpinner = require("bootstrap-input-spinner");
    window.LoadingOverlay = require("gasparesganga-jquery-loading-overlay");
    window.swal = require('sweetalert2');
    require('moment-timezone');
    require('./bootstrap');
    require('bootstrap4-toggle');
    require('@fancyapps/fancybox');
} catch (e) {}

$("input[type='number']").inputSpinner();
