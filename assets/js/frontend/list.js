import '../../css/frontend/list.css';

import $ from 'jquery';

$(document).ready(function () {
   var locale = $('html').attr('lang');
   const appElement = document.getElementById('app');
   const apiUrl = appElement.dataset.apiUrl;

   $('[data-toggle="tooltip"]').tooltip()
   var items = [];
   var url = apiUrl + "/ordenantzakbykodea/48340?_format=json";

   var bootstrap_enabled = (typeof $().modal == 'function');
   $('body').on('click', 'a.btntaula', function () {

      var miid = $(this).data('id');
      var url = apiUrl +  "/zerga/" + miid + "?_format=json";
      var nModal = $('#nireModal');
      $('#modalContent').empty();
      $.getJSON(url, function (data) {
         $('#modalTitle').html(locale == 'es' ? data.izenburuaes_prod : data.izenburuaeu_prod);
         $('#modalContent').html("<p id='parrafoaurretik'></p><table id='kostuTaula' class='table table-bordered table-hover'></table><p id='parrafoondoren'></p>");

         var pAurretik = data.parrafoak;
         if (pAurretik.length > 0) {
            $.each(pAurretik, function (i, item) {
               $('#parrafoaurretik').append(locale == 'es' ? item.testuaes_prod : item.testuaeu_prod);
            });
         }

         var kontzeptuak = data.kontzeptuak;
         $.each(kontzeptuak, function (i, item) {
            var baldintza = item.baldintza;

            if (!baldintza) {
               var $tr = $('<tr>').append($('<td>').text(locale == 'es' ? item.kontzeptuaes_prod : item.kontzeptuaeu_prod), $('<td>').text(item.kopurua_prod), $('<td>').text(item.unitatea_prod)).appendTo('#kostuTaula');
            } else {
               var $tr = $('<tr>').append($('<td>').text(locale == 'es' ? item.kontzeptuaes_prod : item.kontzeptuaeu_prod + '(' + (
                  locale == 'es' ? baldintza.baldintzaes : baldintza.baldintzaeu
               ) + ')'), $('<td>').text(item.kopurua_prod), $('<td>').text(item.unitatea_prod + '(' + baldintza.baldintzaes + ')')).appendTo('#kostuTaula');
            }
         });
         var pOndoren = data.parrafoakondoren;
         if (pOndoren.length > 0) {
            $.each(pOndoren, function (i, item) {
               $('#parrafoondoren').append(locale == 'es' ? item.testuaes_prod : item.testuaeu_prod);
            });
         }

      });

      $('#nireModal').modal()
   });

   $.getJSON(url, function (data) {
      console.log(data);
      $.each(data, function (i, ordenantza) {

         items.push('<li"><a data-toggle="tooltip" data-container="body" data-placement="top" title="Haz click aquí para ver el texto de la ordenanza" target="_blank"  href="/zzoo/ordenantza/' + ordenantza.id + '">' + ordenantza.kodea_prod + ' - ' + (
            locale == 'es' ? ordenantza.izenburuaes_prod : ordenantza.izenburuaeu_prod
         ) + '</a></li>');
         var atalak = ordenantza.atalak;
         $.each(atalak, function (i1, atala) {
            if (atala.izenburuaes_prod !== "") {
               if (('kodea_prod' in atala) && ('izenburuaes_prod' in atala)) {
                  items.push('<li><ul class="nirul"><li class="nireli"><a target="_blank"  href="/zzoo/ordenantza/' + ordenantza.id + '#' + ordenantza.id + '-' + atala.id + '">' + atala.kodea_prod + ' - ' + (
                     locale == 'es' ? atala.izenburuaes_prod : atala.izenburuaeu_prod
                  ) + '</a></li></ul></li>');
               }
            }

            var azpiatalak = atala.azpiatalak;
            $.each(azpiatalak, function (i2, azpiatala) {

               items.push('<li><ul class="barneul" style="padding-left: 20px !important;">' + '   <li class="aupahi" style="list-style:none;">' + '       <ul class="besteul" >' + '           <li class="barnebane">' + '               <a class="btntaula"   data-toggle="tooltip" data-container="body"  data-placement="top" title="Haz click aqui para ver la tabla de costes" data-id="' + azpiatala.id + '" href="javascript:void(0);">' + azpiatala.kodea_prod + ' ' + (
                  locale == 'es' ? azpiatala.izenburuaes_prod : azpiatala.izenburuaeu_prod
               ) + '</a>' + '           </li>' + '       </ul>' + '   </li>' + '</ul></li>');
            });

         });

      });
      $('#ulzzoo').append(items.join(''));
      $('[data-toggle="tooltip"]').tooltip();
   });

});

