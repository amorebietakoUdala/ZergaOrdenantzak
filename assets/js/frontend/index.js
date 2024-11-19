import '../../css/frontend/index.css';

import $ from 'jquery';

$(document).ajaxStart(function(){
   $('#loading').show();
}).ajaxStop(function(){
   $('#loading').hide();
});

$(document).ready(function () {

   $('.btnsubmenu').on("click", function (e) {
       e.preventDefault();
       var index = $(this).data('divazpiatalak');
       var divSub = ".subAzpiatalak" + index;
       $(divSub).toggle( "slow" );
       if ( $(this).hasClass('glyphicon-minus')==true ) {
           $(this).removeClass('glyphicon-minus');
           $(this).addClass('glyphicon-plus')
       } else {
           $(this).removeClass('glyphicon-plus');
           $(this).addClass('glyphicon-minus')
       }

   });
});
