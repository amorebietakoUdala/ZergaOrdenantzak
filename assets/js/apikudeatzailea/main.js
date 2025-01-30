import '../../css/apikudeatzailea/main.css';

import $ from 'jquery';

import Clipboard from '../clipboard.min.js';
import '../prism.js'

document.write('<style type="text/css">div.cp_oculta{display: none;}</style>');
function MostrarOcultar(capa,enlace)
{
    if (document.getElementById)
    {
        var aux = document.getElementById(capa).style;
        aux.display = aux.display? "":"block";
    }
}

const appElement = document.getElementById('app');
const param = appElement.dataset.param;
const udala = appElement.dataset.udala;
const locale = appElement.dataset.locale;
const kopiatu = appElement.dataset.kopiatu;
const kopiatua = appElement.dataset.kopiatua;
const kopiatzeko = appElement.dataset.kopiatzeko;

$(function () {
   //kargatu ordenantzak API bidez
    $('#nireloader').addClass('loading');
//    var param = "{{ zzoo_aplikazioaren_API_url }}";
    // **************************************************
    // **************************************************
    // DEBUG ONLY!!!
    // param = "http://zzoo.dev/app_dev.php/api";
    // **************************************************
    // **************************************************
    var url = param + "/ordenantzakbykodea/" + udala + "?format=json";
  //  var locale = "{{ app.request.getLocale() }}";

    // Ordenantzak
    var jqxhr = $.getJSON(url, function (result) {
        if (locale === "es") {
            $("#cmbOrdenantza").append($("<option></option>").attr("value", -1).text("Selecciona"));
        } else {
            $("#cmbOrdenantza").append($("<option></option>").attr("value", -1).text("Aukeratu"));
        }
        $.each(result, function (i, field) {
            if (locale === "es") {
                $("#cmbOrdenantza").append($("<option></option>").attr("value", field.id).text(field.kodea_prod + " - " + field.izenburuaes_prod));
            } else {
                $("#cmbOrdenantza").append($("<option></option>").attr("value", field.id).text(field.kodea_prod + " - " + field.izenburuaeu_prod));
            }
        });
    });

    jqxhr.complete(function () {
        $('#nireloader').removeClass('loading');
    });

    // Atalak
    $('#cmbOrdenantza').on('click', function () {
        $('#nireloader').addClass('loading');
        var ordenantzaid = $(this).val();
        var url = param + "/tributuak/" + ordenantzaid + "?format=json";
        var jqxhr = $.getJSON(url, function (result) {
            $("#cmbAtala").empty();
            if (locale === "es") {
                $("#cmbAtala").append($("<option></option>").attr("value", -1).text("Selecciona"));
            } else {
                $("#cmbAtala").append($("<option></option>").attr("value", -1).text("Aukeratu"));
            }

            if (result.length === 0) {
                return;
            }

            var atalak = result;

            $.each(atalak, function (i, field) {
                if (locale === "es") {
                    var txt = "";
                    if ('izenburuaes_prod' in field) {
                        if (field.izenburuaes_prod.length > 200) {
                            txt = field.kodea_prod + " - " + field.izenburuaes_prod.substr(0, 200);
                        } else {
                            txt = field.kodea_prod + " - " + field.izenburuaes_prod;
                        }
                    }

                    $("#cmbAtala").append($("<option></option>").attr("value", field.id).text(txt));
                } else {
                    var txt = "";
                    if ('izenburuaes_prod' in field) {
                        if (field.izenburuaeu_prod.length > 200) {
                            txt = field.kodea_prod + " - " + field.izenburuaeu_prod.substr(0, 200);
                        } else {
                            txt = field.kodea_prod + " - " + field.izenburuaeu_prod;
                        }
                    }
                    $("#cmbAtala").append($("<option></option>").attr("value", field.id).text(txt));
                }
            });
        });

        jqxhr.complete(function () {
            $('#nireloader').removeClass('loading');
        });
    });

    // Kontzeptua
    $('#cmbAtala').on('change', function () {

        $('#nireloader').addClass('loading');
        var atalaid = $(this).val();
        var url = param + "/zergak/" + atalaid + "?format=json";

        var jqxhr = $.getJSON(url, function (result2) {
            $("#cmbKontzeptua").empty();
            if (locale === "es") {
                $("#cmbKontzeptua").append($("<option></option>").attr("value", -1).text("Selecciona"));
            } else {
                $("#cmbKontzeptua").append($("<option></option>").attr("value", -1).text("Aukeratu"));
            }

            if (result2.length === 0) {
                return;
            }

            var azpiatalak = result2;

            $.each(azpiatalak, function (i, field2) {

                if (locale === "es") {
                    var txt = "";
                    if (field2.izenburuaes_prod.length > 200) {
                        txt = field2.kodea_prod + " - " + (field2.izenburuaes_prod.substr(0, 200)).replace("<br>", "");
                    } else {
                        txt = field2.kodea_prod + " - " + (field2.izenburuaes_prod).replace("<br>", "");
                    }
                    var sartu = $("<option></option>").attr("value", field2.id).attr("data-eu", field2.izenburuaeu_prod).attr("data-es", field2.izenburuaes_prod).text(txt);
                    $("#cmbKontzeptua").append(sartu);
                } else {
                    var txt = "";
                    if (field2.izenburuaeu_prod.length > 200) {
                        txt = field2.kodea_prod + " - " + (field2.izenburuaeu_prod.substr(0, 200)).replace("<br>", "");
                    } else {
                        txt = field2.kodea_prod + " - " + (field2.izenburuaeu_prod).replace("<br>", "");
                    }
                    var sartu = $("<option></option>").attr("value", field2.id).attr("data-eu", field2.izenburuaeu_prod).attr("data-es", field2.izenburuaes_prod).text(txt);
                    $("#cmbKontzeptua").append(sartu);
                }
            });
        });

        jqxhr.complete(function () {
            $('#nireloader').removeClass('loading');
        });

    });

    $('#btnEuskera').on('click', function () {
        var miid = $('#cmbKontzeptua').val();
        var mitext = $('select[name=cmbKontzeptua] option:selected').data("eu");
        $('#result').empty();
        $('#result').append(ekarriHtml('eu', miid, mitext));
        Prism.highlightElement($('#result')[0]);
        $('#divResult').show("slow");
        eginAdibidea('eu', miid, mitext);
    });

    $('#btnErdara').on('click', function () {
        var miid = $('#cmbKontzeptua').val();
        var mitext = $('select[name=cmbKontzeptua] option:selected').data("es");
        $('#result').empty();
        $('#result').append(ekarriHtml('es', miid, mitext));
        Prism.highlightElement($('#result')[0]);
        $('#divResult').show("slow");
        eginAdibidea('es', miid, mitext);
    });

    $('#btnOrdenantzaEuskera').on('click', function () {
        $('#ordenatzaErderaz').hide();
        $('#ordenatzaEuskeraz').show();
    });
    $('#btnOrdenantzaErdara').on('click', function () {
        $('#ordenatzaEuskeraz').hide();
        $('#ordenatzaErderaz').show();
    });
});


function eginAdibidea($locale, $id, $mitext) {
    $('#emaitza').empty();
    var url = "/zergaordenantzak/api/zerga/" + $id + "?format=json";
    // **************************************************
    // **************************************************
    // DEBUG ONLY!!!
    // var url = "http://zzoo.dev/app_dev.php/api/zerga/" + $id + "?format=json";
    // **************************************************
    // **************************************************
    var colap =0;
    if($("#chkCollapse").is(':checked')) {
        colap = 1;
    }

    if ($locale === "eu") {
        var btnEzkutu = "";
        var divEzkutu ="";

        if ( colap === 1) {
            btnEzkutu = 'onClick="javascript:MostrarOcultar(\'text_eus\');" class="botoiaCollapse"';
            divEzkutu = 'class="cp_oculta cp_oculta'+ $id + '" id="text_eus"';
        }

        var textua = '<h3 ' + btnEzkutu + '>' + $mitext + '</h3>'+
                        '<div ' + divEzkutu + '>'+
                            '<table class="table table-bordered table-condensed" id="kostuTaula">'+
                            '<thead>'+
                                '<tr>'+
                                '    <th>Deskribapena</th>'+
                                '    <th>Kopurua</th>'+
                                '    <th>Unitatea</th>'+
                                '</tr>'+
                            '</thead>'+
                            '<tbody>'+
                               '<tr>'+
                                '</tr>'+
                            '</tbody>'+
                            '</table>'+
                        '</div>';
        $('#emaitza').append(textua);

        $.getJSON(url, function (data) {
            var kontzeptuak = data.kontzeptuak;
            $.each(kontzeptuak, function (i, item) {

                if ("baldintza" in item) {
                    var td1 = (item.kontzeptuaeu_prod + " (" + item.baldintza.baldintzaeu + ")").replace("<br>", "");
                } else {
                    var td1 = (item.kontzeptuaeu_prod ).replace("<br>", "");
                }

                var td2 = (item.kopurua_prod).replace("<br>", "");
                var td3 = (item.unitatea_prod).replace("<br>", "");

                var $tr = $('<tr>').append(
                    $('<td>').text(td1),
                    $('<td>').text(td2),
                    $('<td>').text(td3)
                ).appendTo('#kostuTaula');
            });


        });
    } else {

        var btnEzkutu = "";
        var divEzkutu ="";

        if ( colap === 1) {
            btnEzkutu = 'onClick="javascript:MostrarOcultar(\'text_es\');" class="botoiaCollapse"';
            divEzkutu = 'class="cp_oculta cp_oculta' + $id +'" id="text_es"';
        }

        var textua = '<h3 ' + btnEzkutu + '>' + $mitext + '</h3>'+
                     '   <div ' + divEzkutu + '>'+
                     '       <table class="table table-bordered table-condensed" id="kostuTaula">'+
                     '       <thead>'+
                     '           <tr>'+
                     '               <th>Descripción</th>'+
                     '               <th>Cantidad</th>'+
                     '               <th>Unidad</th>'+
                     '           </tr>'+
                     '       </thead>'+
                     '       <tbody>'+
                     '           <tr>'+
                     '           </tr>'+
                     '       </tbody>'+
                     '       </table>'+
                     '   </div>';

        $('#emaitza').append(textua);

        $.getJSON(url, function (data) {
            var kontzeptuak = data.kontzeptuak;
            $.each(kontzeptuak, function (i, item) {
                if ("baldintza" in item) {
                    var td1 = (item.kontzeptuaes_prod + " (" + item.baldintza.baldintzaes + ")").replace("<br>", "");
                } else {
                    var td1 = (item.kontzeptuaeu_prod ).replace("<br>", "");
                }
                var td2 = (item.kopurua_prod).replace("<br>", "");
                var td3 = (item.unitatea_prod).replace("<br>", "");

                var $tr = $('<tr>').append(
                    $('<td>').text(td1),
                    $('<td>').text(td2),
                    $('<td>').text(td3)
                ).appendTo('#kostuTaula');
            });

        });
    }
}

function ekarriHtml($locale, $apId, mitext) {
    var colap =0;
    if($("#chkCollapse").is(':checked')) {
        colap = 1;
    }

    var nireH3 = "&#x3C;h3 id=&#x22;nireH3" + $apId + "&#x22; &#x3E;&#x3C;/h3&#x3E;";
    var nireDiv = "&#x3C;div&#x3E;";
    var nireDesk = "Deskribapena";
    var nireKop = "Kopurua";
    var nireUni = "Salneurria";
    var nireKontzep = "kontzeptuaeu_prod";
    var nireBaldin = "baldintzaeu";
    var nireIzen = "izenburuaeu_prod";

    if ( $locale === "es") {
        nireDesk = "Descripci&#xF3;n";
        nireKop = "Cantidad";
        nireUni = "Unidad";
        nireKontzep = "kontzeptuaes_prod";
        nireBaldin = "baldintzaes";
        nireIzen = "izenburuaes_prod";
    }

    if ( colap === 1 ) {
        if ( $locale === "eu" ) {
            nireH3 = "&#x3C;h3 id=&#x22;nireH3" + $apId + "&#x22; onclick=&#x22;javascript:MostrarOcultar(&#x27;text_eus" + $apId + "&#x27;);&#x22; class=&#x22;botoiaCollapse&#x22;&#x3E;&#x3C;/h3&#x3E;";
            nireDiv = "&#x3C;div class=&#x22;cp_oculta" + $apId + "&#x22; id=&#x22;text_eus" + $apId + "&#x22; style=&#x22;display: block;&#x22;&#x3E;";
        } else {
            nireH3 = "&#x3C;h3 id=&#x22;nireH3" + $apId + "&#x22; onclick=&#x22;javascript:MostrarOcultar(&#x27;text_es" + $apId + "&#x27;);&#x22; class=&#x22;botoiaCollapse&#x22;&#x3E;&#x3C;/h3&#x3E;";
            nireDiv = "&#x3C;div class=&#x22;cp_oculta" + $apId + "&#x22; id=&#x22;text_es" + $apId + "&#x22; style=&#x22;display: block;&#x22;&#x3E;";
        }
    }

    var miHtml =''+
    nireH3 + '\n' +
    nireDiv + '\n' +
    '&#x9;&#x3C;table class=&#x22;table table-bordered table-condensed&#x22; id=&#x22;kostuTaula' + $apId +'&#x22;&#x3E;'+'\n' +
    '&#x9;&#x9;&#x3C;thead&#x3E;'+'\n' +
    '&#x9;&#x9;&#x9;&#x3C;tr&#x3E;'+'\n' +
    '&#x9;&#x9;&#x9;&#x9;&#x3C;th&#x3E;' + nireDesk + '&#x3C;/th&#x3E;'+'\n' +
    '&#x9;&#x9;&#x9;&#x9;&#x3C;th&#x3E;' + nireKop + '&#x3C;/th&#x3E;'+'\n' +
    '&#x9;&#x9;&#x9;&#x9;&#x3C;th&#x3E;' + nireUni +'&#x3C;/th&#x3E;'+'\n' +
    '&#x9;&#x9;&#x9;&#x3C;/tr&#x3E;'+'\n' +
    '&#x9;&#x9;&#x3C;/thead&#x3E;'+'\n' +
    '&#x9;&#x9;&#x3C;tbody&#x3E;'+'\n' +
    '&#x9;&#x9;&#x9;&#x3C;tr&#x3E;'+'\n' +
    '&#x9;&#x9;&#x9;&#x3C;/tr&#x3E;'+'\n' +
    '&#x9;&#x9;&#x3C;/tbody&#x3E;'+'\n' +
    '&#x9;&#x3C;/table&#x3E;'+'\n' +
    '&#x3C;/div&#x3E;'+'\n' +

    '&#x3C;script src=&#x22;https://code.jquery.com/jquery-1.12.4.min.js&#x22; integrity=&#x22;sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=&#x22; crossorigin=&#x22;anonymous&#x22;&#x3E;&#x3C;/script&#x3E;'+'\n' +
    '&#x3C;script language=&#x22;javascript&#x22;&#x3E;'+'\n' +
    '&#x9;$(document).ready(function() {'+'\n' +
    '&#x9;&#x9;$(&#x27;.cp_oculta' + $apId + '&#x27;).css(&#x27;display&#x27;,&#x27;none&#x27;);'+'\n' +
    '&#x9;&#x9;var url = &#x22;/zergaordenantzak/api/zerga/' + $apId + '?format=json&#x22;;'+'\n' +
    '&#x9;&#x9;$.getJSON(url, function(data) {'+'\n' +
    '&#x9;&#x9;$(&#x27;#nireH3' + $apId + '&#x27;).text ((data.' + nireIzen + ').replace(&#x22;&#x3C;br&#x3E;&#x22;,&#x22;&#x22;).replace(&#x22;&#x26;nbsp;&#x22;,&#x22; &#x22;));'+'\n' +
    '&#x9;&#x9;&#x9;var kontzeptuak = data.kontzeptuak;'+'\n' +
    '&#x9;&#x9;&#x9;$.each(kontzeptuak, function(i, item) {'+'\n' +
    '&#x9;&#x9;&#x9;&#x9;if (&#x22;baldintza&#x22; in item) {'+'\n' +
    '&#x9;&#x9;&#x9;&#x9;&#x9;var td1 = (item.' + nireKontzep + ' + &#x22; (&#x22; + item.baldintza.' + nireBaldin +' + &#x22;)&#x22;).replace(&#x22;&#x3C;br&#x3E;&#x22;, &#x22;&#x22;);'+'\n' +
    '&#x9;&#x9;&#x9;&#x9;} else {'+'\n' +
    '&#x9;&#x9;&#x9;&#x9;&#x9;var td1 = (item.' + nireKontzep + ').replace(&#x22;&#x3C;br&#x3E;&#x22;, &#x22;&#x22;);'+'\n' +
    '&#x9;&#x9;&#x9;&#x9;}'+'\n' +
    '&#x9;&#x9;&#x9;&#x9;var td2 = (item.kopurua_prod).replace(&#x22;&#x3C;br&#x3E;&#x22;, &#x22;&#x22;);'+'\n' +
    '&#x9;&#x9;&#x9;&#x9;var td3 = (item.unitatea_prod).replace(&#x22;&#x3C;br&#x3E;&#x22;, &#x22;&#x22;);'+'\n' +
    '&#x9;&#x9;&#x9;&#x9;var $tr = $(&#x27;&#x3C;tr&#x3E;&#x27;).append('+'\n' +
    '&#x9;&#x9;&#x9;&#x9;&#x9;$(&#x27;&#x3C;td&#x3E;&#x27;).text(td1),'+'\n' +
    '&#x9;&#x9;&#x9;&#x9;&#x9;$(&#x27;&#x3C;td&#x3E;&#x27;).text(td2),'+'\n' +
    '&#x9;&#x9;&#x9;&#x9;&#x9;$(&#x27;&#x3C;td&#x3E;&#x27;).text(td3)'+'\n' +
    '&#x9;&#x9;&#x9;&#x9;).appendTo(&#x27;#kostuTaula' + $apId + '&#x27;);'+'\n' +
    '&#x9;&#x9;&#x9;});'+'\n' +
    '&#x9;&#x9;});'+'\n' +
    '&#x9;});'+'\n' +

    'function MostrarOcultar(capa, enlace) {'+'\n' +
    '  $(&#x27;#&#x27;+capa).toggle();'+'\n' +
    '}'+'\n' +

    '&#x3C;/script&#x3E;';
    return miHtml;

}

/* Prism copy to clipbaord for all pre with copytoclipboard class */
$('pre.copytoclipboard').each(function () {
    let $this = $(this);
    let $button = $('<button>'+ kopiatu +'</button>');
    $this.wrap('<div/>').removeClass('copytoclipboard');
    let $wrapper = $this.parent();
    $wrapper.addClass('copytoclipboard-wrapper').css({position: 'relative'})
    $button.css({position: 'absolute', top: 10, right: 10}).appendTo($wrapper).addClass('copytoclipboard btn btn-default');
    /* */
    var copyCode = new Clipboard('button.copytoclipboard', {
        target: function (trigger) {
            return trigger.previousElementSibling;
        }
    });
    copyCode.on('success', function (event) {
        event.clearSelection();
        event.trigger.textContent = kopiatua;
        window.setTimeout(function () {
            event.trigger.textContent = kopiatu;
        }, 2000);
    });
    copyCode.on('error', function (event) {
        event.trigger.textContent = kopiatzeko;
        window.setTimeout(function () {
            event.trigger.textContent = kopiatu;
        }, 2000);
    });
});
