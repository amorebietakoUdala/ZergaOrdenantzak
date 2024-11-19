import '../../css/udala/index.css';

function ezabatu(id) {
   var r = confirm("Udala EZABATU. Ziur zaude? Ekintza honek udalaren erregistro guztiak ezabatuko ditu!!");
   if (r == true) {
      document.getElementById(id).submit()
   } else {
   }
};
