$( document ).ready(function() {


  // POUR LA PAGINATION
  pages = document.getElementsByClassName('page-item');
  for(let i=0; i< pages.length; i++){
    if(pages[i].children[0] && pages[i].children[0].innerHTML){
      pages[i].children[0].className="page-link";
  }
    if(!pages[i].innerHTML.startsWith('<a')){
      pages[i].classList.add("page-link");
      pages[i].style.color = '#6c757d';
    }
  }

  //au choix de la juridiction le pays en question est mis dans le input du pays avant l'envoi du formulaire
  $(document).change(function(){
    if($("#juridiction").val() != ""){
      pays = $("#juridiction :selected").data("pays");
      $('#pays_filter').val(pays);
    }
  });

  //SAVOIR SI ON EST EN MODE MOBILE GRACE A   <span id="is_mobile" class="d-lg-none"></span> qui se trouve dans le header
  var is_mobile = function() {
      return !(window.getComputedStyle(document.getElementById('is_mobile')).display === "none");
  };

  //SI ON EST EN MODE MOBILE
  if(is_mobile()){

    if(document.getElementById("textArret")){ //id dans la page d'un arret/templates/indexSuccess.php on cache le texte pour n'afficher que le texte début au tout début
      $("#textArret").addClass("collapse");
    }
    if(document.getElementById('hidden-mode-mobile')){ //id dans le header si page d'un arret alors pas de barre de recherche
      $('#hidden-mode-mobile').addClass("d-none");
      $('#hidden-mode-mobile').addClass("d-lg-none");
    }
    if(document.getElementById("btn-see-more")){  //id dans la page d'un arret/templates/indexSuccess.php si click sur le bouton voir plus d'un arrêt.
      $("#btn-see-more").click(function(){
          if($("#btn-see-more").text()=="Voir plus"){
            $("#btn-see-more").html("Voir moins");
            $("#btn-expand").html("<i class='bi bi-arrows-collapse'></i>") //change icone dans la barre d'option
            $("#debutArret").addClass("d-none"); //cache le texte debut "resume"
            location.hash = "#debutArret";
            window.scroll({
             top: 10,
             left: 10,
             behavior: 'smooth'
            });
          }
          else{
            $("#btn-see-more").html("Voir plus");
            $("#btn-expand").html("<i class='bi bi-arrows-expand'></i>") //change icone dans la barre d'option
            $("#debutArret").removeClass("d-none");//affiche le texte debut "resume"
            
            window.scroll({
             top: 10,
             left: 10,
             behavior: 'smooth'
            });
            // location.hash = "#btn-see-more";

          }
      });
    }
    if(document.getElementById('btn-expand')){
      $('#btn-expand').click(function(){
        $("#btn-see-more").trigger( "click" );
      });
    }
    if(document.getElementById('bloc-filtres')){ //id dans la page des resultats d'une recherche recherche/templates/searchSuccess.php
      $("#bloc-filtres").addClass("collapse"); //on cache les blocs des filtres.
    }

    //class dans la page des resultats d'une recherche recherche/templates/searchSuccess.php
    //permet de pouvoir cliquer sur une "card" div d'un arret pour aller sur la page de l'arret
    var arrets=document.getElementsByClassName("card-body");
    if(arrets){
      for(i=0;i<arrets.length;i++){
        $(arrets[i]).wrap('<a class="text-decoration-none" href='+$(arrets[i]).data("link")+'></a>');
      }
    }
  }

  //SI PAS en mode mobile => maj des résultats au changement du filtres
  //en mode mobile obligé de cliquer sur le bouton filtrer.
  else{
    $(document).change(function(){
      $( "#filtrer" ).trigger( "click" );
    });
    $('#hidden-mode-mobile').addClass("mb-3"); //ajoute de l'espace entre la barre de recherche et le contenu en mode desktoop.
  }

});

//fonction appelé au click du bouton "zoom" de la barre d'outils dans la page d'un arret.
function fontSizePlus(){
  corps = document.getElementById('arret');
  size = window.getComputedStyle(corps).getPropertyValue("font-size");
  value = parseInt(size.match(/(\d+)/));
  corps.style.fontSize = (value + 1) + 'px';
}

//fonction appelé au click du bouton "dézoom" de la barre d'outils dans la page d'un arret.
function fontSizeMoins(){
  corps = document.getElementById('arret');
  size = window.getComputedStyle(corps).getPropertyValue("font-size");
  value = parseInt(size.match(/(\d+)/));
  corps.style.fontSize = (value - 1) + 'px';
}

//fonction appelé au click du bouton "copier" de la barre d'outils dans la page d'un arret pour copier le titre et l'url
function copyArretUrl(titre){
  var btnCpy = document.getElementById("btn-cpy");
  btnCpy.children[0].setAttribute("class","bi bi-check2-square");
  setTimeout(redisplayClipBoard, 5000);
  navigator.clipboard.writeText(titre+' '+window.location.href+'');
}

//fonction qui réaffiche l'icone initial du copier qui a été changé après le click pour avertir que ça avait bien été copié
function redisplayClipBoard(){
  var btnCpy = document.getElementById("btn-cpy");
  btnCpy.children[0].setAttribute("class","bi bi-clipboard");
}

