import $ from 'jquery';
try {
    require('./bootstrap');
    require('bootstrap');
    require('admin-lte');
    window.$ = window.jQuery = require('jquery');
    window.axios = require('axios');
} catch (e) {}
