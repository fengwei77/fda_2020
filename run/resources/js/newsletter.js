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
    // require('./quill.js');
    // window.Quill = require('quill');
} catch (e) {
}
//
// import ImageCompress from 'quill-image-compress';
// Quill.register('modules/imageCompress', ImageCompress);

import $ from 'jquery';
import Sortable, {Swap} from 'sortablejs';

Sortable.mount(new Swap());
// global export
window.Sortable = Sortable;


const customElement = $("<div>", {
    "css": {
        "border": "4px dashed gold",
        "font-size": "40px",
        "text-align": "center",
        "padding": "10px"
    },
    "class": "your-custom-class",
    "text": "Custom!"
});
const get_token = document.head.querySelector('meta[name="csrf-token"]').content;


window.IsValidJSONString = function (str) {
    try {
        JSON.parse(str);
    } catch (e) {
        return false;
    }
    return true;
}
