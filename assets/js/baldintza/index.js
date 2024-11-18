import '../../css/baldintza/index.css';

import $ from 'jquery';

const routes = require('../../../public/js/fos_js_routes.json');
import Routing from '../../../vendor/friendsofsymfony/jsrouting-bundle/Resources/public/js/router.min.js';

Routing.setRoutingData(routes);

import bootbox from 'bootbox';

$(document).ready(function () {

   $('.btnBaldintzaEzabatu').on("click", function(){
       var fEzabatu = $(this).parent('form');
       bootbox.confirm({
           title: "Ezabatu",
           message: "Ziur zaude ezabatu nahi duzula?",
           buttons: {
               cancel: {
                   label: '<i class="fa fa-times"></i> Cancel'
               },
               confirm: {
                   label: '<i class="fa fa-check"></i> Confirm'
               }
           },
           callback: function (result) {
               console.log(result);
               if ( result == true ) {
                   console.log('hemen');
                   $(fEzabatu).submit();
//                            document.getElementById(id).submit()
               }
           }
       });

   })

   $('.btnBaldintzaEditatu').on("click", function () {

       $('#nireloader').addClass('loading');

       var miid = $(this).data('id');
       var url = global.base + Routing.generate('baldintza_edit', {_locale: 'eu', id: miid});

       $('#modalBaldintzaEditBody').load(url, function () {
           $("#frmBaldintzaEdit").attr("action", url);
           $('#frmEdit').modal();
       });

       $('#frmEdit').on('shown.bs.modal', function (e) {
           $('#nireloader').removeClass('loading');
       });


   })

});
