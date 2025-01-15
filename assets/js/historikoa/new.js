import '../../css/historikoa/new.css';

import $ from 'jquery';

import '../calendar/bootstrap-datepicker.min.js';
import '../calendar/bootstrap-datepicker.es.min.js';
import '../calendar/bootstrap-datepicker.eu.min.js';
import bootbox from 'bootbox';

$(document).ready(function () {
   $('.js-datepicker').datepicker({
      format: 'yyyy-mm-dd',
      todayBtn: "linked",
      language: "eu",
      autoclose: true,
      todayHighlight: true
  });

   $('.kargatzen').on('click', function () {
       var dialog = bootbox.dialog({
           message: '<p class="text-center">Itxaron apur bat PDF fitxategia sortzen den bitartean... </p>',
           closeButton: false
       });
   });

});
