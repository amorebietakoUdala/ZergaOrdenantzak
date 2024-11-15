/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import './css/app.css';

// start the Stimulus application
import './bootstrap';

import 'bootstrap';

import $ from 'jquery';

import './js/bootstrap-editable.js';
// No funciona igual con bootstrap de momento lo metemos directamente en la página
// import './js/3/wysiwig.js';
//import './js/3/bootstrap-wysihtml5-0.0.3.min.js';
//import './js/3/bootstrap3-wysihtml5.js';
import './js/calendar/bootstrap-datepicker.min.js';
import './js/calendar/bootstrap-datepicker.es.min.js';
import './js/calendar/bootstrap-datepicker.eu.min.js';
import './js/bootbox.min.js';

// Declare $ globally
// global.$ = $;
// global.jQuery = $;

// const routes = require('../public/bundles/fosjsrouting/js/fos_js_routes.json');
// import Routing from '../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';


var body = $('body');
body.scrollspy({
    'target': '#nav',
    'offset': 100 //this must match the window.scrollY below or you'll have a bad time mmkay
});

$(window).on("hashchange", function () {
    window.scrollTo(window.scrollX, window.scrollY - 100);
});

$(document).ready(function () {
//    Routing.setRoutingData(routes);
    $('.js-datepicker').datepicker({
        format: 'yyyy-mm-dd',
        todayBtn: "linked",
        language: "eu",
        autoclose: true,
        todayHighlight: true
    });
});

/* <script src="{{ asset('bundles/app/js/bootstrap-editable.js') }}"></script>

<script src="{{ asset('bundles/app/js/3/wysihtml5-0.3.0.min.js') }}"></script>
<script src="{{ asset('bundles/app/js/3/bootstrap-wysihtml5-0.0.3.min.js') }}"></script>
<script src="{{ asset('bundles/app/js/3/wysihtml5-0.0.3.js') }}"></script>
<script src="{{ asset('bundles/app/js/calendar/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ asset('bundles/app/js/calendar/bootstrap-datepicker.es.min.js') }}"></script>
<script src="{{ asset('bundles/app/js/calendar/bootstrap-datepicker.eu.min.js') }}"></script>
<script src="{{ asset('bundles/app/js/bootbox.min.js') }}"></script>


<script src="{{ asset('bundles/fosjsrouting/js/router.js') }}"></script>
<script src="{{ path('fos_js_routing_js', { callback: 'fos.Router.setData' }) }}"></script>

<script>
    var body = $('body');
    body.scrollspy({
        'target': '#nav',
        'offset': 100 //this must match the window.scrollY below or you'll have a bad time mmkay
    });

    $(window).on("hashchange", function () {
        window.scrollTo(window.scrollX, window.scrollY - 100);
    });

    $(document).ready(function () {
        $('.js-datepicker').datepicker({
            format: 'yyyy-mm-dd',
            todayBtn: "linked",
            language: "eu",
            autoclose: true,
            todayHighlight: true
        });
    });

</script> */
