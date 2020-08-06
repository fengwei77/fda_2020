import $ from 'jquery';

try {
    window.$ = window.jQuery = require('jquery');
    require('bootstrap');
    require('bootstrap/dist/js/bootstrap.bundle.min');
    require('admin-lte');
    require('fastclick');
    require('icheck-bootstrap');
} catch (e) {}
