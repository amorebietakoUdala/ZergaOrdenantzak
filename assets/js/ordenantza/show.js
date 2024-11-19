import '../../css/ordenantza/show.css';

import $ from 'jquery';

$(function(){
   $(document).click(function (event) {
       var clickover = $(event.target);
       var _opened = $(".navbar-collapse").hasClass("navbar-collapse in");
       if (_opened === true && !clickover.hasClass("navbar-toggle") && !clickover.is('input')) {
           $("button.navbar-toggle").click();
       }
   });

   /**
    * Check a href for an anchor. If exists, and in document, scroll to it.
    * If href argument ommited, assumes context (this) is HTML Element,
    * which will be the case when invoked by jQuery after an event
    */
   function scroll_if_anchor(href) {
       href = typeof(href) == "string" ? href : $(this).attr("href");

       // You could easily calculate this dynamically if you prefer
       var fromTop = 75;

       // If our Href points to a valid, non-empty anchor, and is on the same page (e.g. #foo)
       // Legacy jQuery and IE7 may have issues: http://stackoverflow.com/q/1593174
       if(href.indexOf("#") == 0) {
           var $target = $(href);

           // Older browser without pushState might flicker here, as they momentarily
           // jump to the wrong position (IE < 10)
           if($target.length) {
               $('html, body').animate({ scrollTop: $target.offset().top - fromTop });
               if(history && "pushState" in history) {
                   history.pushState({}, document.title, window.location.pathname + href);
                   return false;
               }
           }
       }
   }

   // When our page loads, check to see if it contains and anchor
   scroll_if_anchor(window.location.hash);

   // Intercept all anchor clicks
   $("body").on("click", "a", scroll_if_anchor);
});



$(document).ajaxStart(function () {
   $('#loading').show();
}).ajaxStop(function () {
   $('#loading').hide();
});

$(document).ready(function () {

   $('.divAkzioak').on('mouseover', function () {
       $(this).next('.divTextua').addClass('gainean');
   }).on('mouseleave', function () {
       $(this).next('.divTextua').removeClass('gainean');
   });

   $('.btnAddOrdenantzaParrafoa').on('click', function () {

       var nireOrdena = parseInt($(this).data('ordena')) + 1;

       $('#ordenantzaparrafoa_ordena').val(nireOrdena);
       $('#divOrdenantzaparrafoBerria').modal();
   });


   $('#btnaldaketakezeztatu').on('click', function () {
       var that = $(this);
       bootbox.confirm({
           title: "Aldaketak ezeztatu",
           message: "Eragiketa honekin, momentuan egindako aldaketak deuseztatu eta aurretik gordeta daudenak kargatuko dira, ados?",
           buttons: {
               cancel: {
                   label: '<i class="fa fa-times"></i> Ezeztatu'
               },
               confirm: {
                   label: '<i class="fa fa-check"></i> Onartu'
               }
           },
           callback: function (result) {
               if (result === true) {
                   var miid = $(that).data('id');
                   window.location = Routing.generate('admin_ordenantza_ezeztatu', {
                       '_locale': 'eu',
                       'id': miid
                   });
                   ;
               }
           }
       });

   });

   $(function () {
      $('.btnKontzeptuaEdit').on("click", function (e) {
         e.preventDefault();
         $('#divKontzeptuaEdit').empty();
         var miid = $(this).data('miid');
         var ordenantzaid = $(this).data('ordenantzaid');
         var url = Routing.generate('admin_kontzeptua_edit', { id: miid, ordenantzaid: ordenantzaid });
         $('#divKontzeptuaEdit').load(url);
         $('#divKontzeptuaEdit').modal('toggle');
      });
   });

   $('#enable').click(function () {
       $('.editable').editable('toggleDisabled');
       $('.editable2').editable('toggleDisabled');
   });

   $('.btnAtalaparrafoBerria').on("click", function (e) {
       $('#divAtalaparrafoBerria').empty();

       var nireOrdena = parseInt($(this).data('ordena')) + 1;
       var miid = $(this).data('atalaid');
       var url = Routing.generate('admin_atalaparrafoa_new', {atalaid: miid});
       $('#divAtalaparrafoBerria').load(url, function(responseTxt, statusTxt, xhr){
           if(statusTxt == "success") {
               $('#atalaparrafoa_ordena').val(nireOrdena);
               $('#divAtalaparrafoBerria').modal('toggle');
           }
           if(statusTxt == "error"){
               bootbox.alert({
                   message: "Error: " + xhr.status + ": " + xhr.statusText,
                   className: 'bb-alternate-modal',
                       backdrop: true
               });
           }
       });

   });

   $('.btnAzpiatalaparrafoBerria').on("click", function (e) {
       $('#divAzpiatalaparrafoBerria').empty();
       var nireOrdena = parseInt($(this).data('ordena')) + 1;
       var miid = $(this).data('azpiatalaid');
       var url = Routing.generate('admin_azpiatalaparrafoa_new', {azpiatalaid: miid});

       $('#divAzpiatalaparrafoBerria').load(url, function(responseTxt, statusTxt, xhr){
           if(statusTxt == "success") {
               $('#azpiatalaparrafoa_ordena').val(nireOrdena);
               $('#divAzpiatalaparrafoBerria').modal('toggle');
           }
           if(statusTxt == "error"){
               bootbox.alert({
                   message: "Error: " + xhr.status + ": " + xhr.statusText,
                   className: 'bb-alternate-modal',
                   backdrop: true
               });
           }
       });
   });

   $('.btnAzpiatalaparrafoondorenBerria').on("click", function (e) {
       $('#divAzpiatalaparrafoondorenBerria').empty();
       var nireOrdena = parseInt($(this).data('ordena')) + 1;
       var miid = $(this).data('azpiatalaid');
       var url = Routing.generate('admin_azpiatalaparrafoaondoren_new', {azpiatalaid: miid});

       $('#divAzpiatalaparrafoondorenBerria').load(url, function(responseTxt, statusTxt, xhr){
           if(statusTxt == "success") {
               $('#appbundle_azpiatalaparrafoaondoren_ordena').val(nireOrdena);
               $('#divAzpiatalaparrafoondorenBerria').modal('toggle');
           }
           if(statusTxt == "error"){
               bootbox.alert({
                   message: "Error: " + xhr.status + ": " + xhr.statusText,
                   className: 'bb-alternate-modal',
                   backdrop: true
               });
           }
       });
   });

   $('.btnAzpiAtalBerria').on("click", function (e) {
       $('#divAzpiAtalBerria').empty();
       var miid = $(this).data('atalaid');
       var url = Routing.generate('admin_azpiatala_new', {atalaid: miid});
       $('#divAzpiAtalBerria').load(url);
       $('#divAzpiAtalBerria').modal('toggle');
   });


   $('.btnKontzeptuBerria').on("click", function (e) {
       document.location.hash = "kontzeptuaGehitu";
       $('#divKontzeptuBerria').empty();
       var miid = $(this).data('azpiatalaid');
       var url = Routing.generate('admin_kontzeptua_new', {azpiatalaid: miid});
       $('#divKontzeptuBerria').load(url, function(responseTxt, statusTxt, xhr){
           if(statusTxt == "success") {
               $('#divKontzeptuBerria').modal('toggle');
           }
           if(statusTxt == "error"){
               bootbox.alert({
                   message: "Error: " + xhr.status + ": " + xhr.statusText,
                   className: 'bb-alternate-modal',
                   backdrop: true
               });
           }
       });
   });

   $('.btnEzabatuAtala').on('click', function () {
       $('#divEzabatuAtala').empty();
       var miid = $(this).data('miid');
       var url = Routing.generate('admin_atala_ezabatu', {id: miid});
       $('#divEzabatuAtala').load(url);
       $('#divEzabatuAtala').modal('toggle');
   });

   $('.btnEzabatuAtalaParrafoa').on('click', function () {
       $('#divEzabatuAtalaParrafoa').empty();
       var miid = $(this).data('miid');
       var url = Routing.generate('admin_atalaparrafoa_ezabatu', {id: miid});
       $('#divEzabatuAtalaParrafoa').load(url, function () {
           $('#divEzabatuAtalaParrafoa').modal('toggle');
       });
   });

   $('#btnOrdenantzaEzabatu').on('click', function () {
       $('#divEzabatuOrdenantza').modal('toggle');
   });

   $('.btnEzabatuAzpiAtala').on('click', function () {
       $('#divEzabatuAzpiAtala').empty();
       var miid = $(this).data('miid');
       var url = Routing.generate('admin_azpiatala_ezabatu', {id: miid});
       $('#divEzabatuAzpiAtala').load(url);
       $('#divEzabatuAzpiAtala').modal('toggle');
   });

   $('.btnEzabatuAzpiAtalaParrafoa').on('click', function () {
       $('#divEzabatuAzpiAtalaParrafoa').empty();
       var miid = $(this).data('miid');
       var url = Routing.generate('admin_azpiatalaparrafoa_ezabatu', {id: miid});
       $('#divEzabatuAzpiAtalaParrafoa').load(url);
       $('#divEzabatuAzpiAtalaParrafoa').modal('toggle');
   });

   $('.btnEzabatuAzpiAtalaParrafoaondoren').on('click', function () {
       $('#divEzabatuAzpiAtalaParrafoaondoren').empty();
       var miid = $(this).data('miid');
       var url = Routing.generate('admin_azpiatalaparrafoaondoren_ezabatu', {id: miid});
       $('#divEzabatuAzpiAtalaParrafoaondoren').load(url);
       $('#divEzabatuAzpiAtalaParrafoaondoren').modal('toggle');
   });

   $('.btnEzabatuKontzeptua').on('click', function () {
       $('#divEzabatuKontzeptua').empty();
       var miid = $(this).data('miid');
       var url = Routing.generate('admin_kontzeptua_ezabatu', {id: miid});
       $('#divEzabatuKontzeptua').load(url);
       $('#divEzabatuKontzeptua').modal('toggle');
   });


   $.fn.editable.defaults.mode = 'inline';
   var isAdmin = $('#isAdmin').val();

   if (isAdmin === "0") {
       $('.editable').editable('toggleDisabled');
       $('.editable2').editable('toggleDisabled');
   } else {
       $('.divEditable').on('mouseover', function () {
           $(this).find('.btnEzabatu').removeClass('hide');
           $(this).find('.btnEzabatuAtalaParrafoa').removeClass('hide');
           $(this).find('.btnEzabatuAzpiAtalaParrafoa').removeClass('hide');
           $(this).find('.btnEzabatuAzpiAtalaParrafoaondoren').removeClass('hide');
           $(this).find('.btnSort').removeClass('hide');
       }).on('mouseleave', function () {
           $(this).find('.btnEzabatu').addClass('hide');
           $(this).find('.btnEzabatuAtalaParrafoa').addClass('hide');
           $(this).find('.btnEzabatuAzpiAtalaParrafoa').addClass('hide');
           $(this).find('.btnEzabatuAzpiAtalaParrafoaondoren').addClass('hide');
           $(this).find('.btnSort').addClass('hide');
       });

       $('.btnEzabatuAtala').on('mouseover', function () {
           $(this).next().addClass('gainean');
       }).on('mouseleave', function () {
           $(this).next().removeClass('gainean');
       });
       $('.btnEzabatuAzpiAtala').on('mouseover', function () {
           $(this).next().addClass('gainean');
       }).on('mouseleave', function () {
           $(this).next().removeClass('gainean');
       });

       $('.editable').editable();

       $('.editable2').on('mouseover', function () {
           $(this).addClass('gainean');
       }).on('mouseleave', function () {
           $(this).removeClass('gainean');
       }).editable({
           toggle: 'dblclick',
           title: 'Enter comments',
           showbuttons: 'top',
           onblur: 'ignore',
           inputclass: 'editable-buttons-ezker',
           wysihtml5: {
               "image": false,
               "font-styles": false, //Font styling, e.g. h1, h2, etc. Default true
               "emphasis": true, //Italics, bold, etc. Default true
               "lists": true, //(Un)ordered lists, e.g. Bullets, Numbers. Default true
               "html": false, //Button which allows you to edit the generated HTML. Default false
               "link": false, //Button to insert a link. Default true
           }
       });

       $('.editable-popup').on('mouseover', function () {
           $(this).addClass('gainean');
       }).on('mouseleave', function () {
           $(this).removeClass('gainean');
       }).editable({
           toggle: 'dblclick',
           mode: 'popup',
           title: 'Ordena',
           showbuttons: 'top',
           onblur: 'ignore',
           inputclass: 'editable-buttons-popup',
           buttons: '<button type="submit" class="btn btn-success editable-submit btn-mini"><i class="icon-ok icon-white"></i></button>' + '<button type="button" class="btn editable-cancel btn-mini"><i class="icon-remove"></i></button>',
           success: function () {
               location.reload();
           }
       });
   }
});
