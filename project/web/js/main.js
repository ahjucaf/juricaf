$( document ).ready(function() {
  pages = document.getElementsByClassName('page-item');
  for(let i=0; i< pages.length; i++){
    if(pages[i].children[0]){
      pages[i].children[0].className="page-link";
      // pages[i].children[0].classList.add('a-unstyled');
  }
    if(!pages[i].innerHTML.startsWith('<a')){
      pages[i].classList.add("page-link");
      pages[i].style.color = '#6c757d';
    }
  }

  var is_mobile = function() {
      return !(window.getComputedStyle(document.getElementById('is_mobile')).display === "none");
  };

  if(is_mobile()){
    if(document.getElementById("navbar")){
      $("#navbar").addClass('fixed-top');
    }
    if(document.getElementById("menu")){
      $('#menu').addClass("mt-10");
    }
    if(document.getElementById("textArret")){
      $("#textArret").addClass("collapse");
    }
    if(document.getElementById('hidden-mode-mobile')){
      $('#hidden-mode-mobile').addClass("d-none");
      $('#hidden-mode-mobile').addClass("d-lg-none");
    }
    if(document.getElementById("btn-see-more")){
      $("#btn-see-more").click(function(){
          if($("#btn-see-more").text()=="Voir plus"){
            $("#btn-see-more").html("Voir moins");
            $("#debutArret").addClass("d-sm-none");
          }
          else{
            $("#btn-see-more").html("Voir plus");
            $("#debutArret").removeClass("d-sm-none");
          }
      });
    }
    var arrets=document.getElementsByClassName("card-body");
    if(arrets){
      for(i=0;i<arrets.length;i++){
        $(arrets[i]).wrap('<a class="text-decoration-none" href='+$(arrets[i]).data("link")+'></a>');
      }
    }
  }

});




function fontSizePlus(){
  corps = document.getElementById('arret');
  size = window.getComputedStyle(corps).getPropertyValue("font-size");
  value = parseInt(size.match(/(\d+)/));
  corps.style.fontSize = (value + 1) + 'px';
}

function fontSizeMoins(){
  corps = document.getElementById('arret');
  size = window.getComputedStyle(corps).getPropertyValue("font-size");
  value = parseInt(size.match(/(\d+)/));
  corps.style.fontSize = (value - 1) + 'px';
}

function deletePaysfilter(){
  var parsedUrl = new URL(window.location.href);
  parsedUrl.searchParams.delete("pays");
  parsedUrl.searchParams.delete("juridiction");
  parsedUrl.searchParams.delete("page");
  window.location.replace(parsedUrl);
}

function deleteJuridictionfilter(){
  var parsedUrl = new URL(window.location.href);
  parsedUrl.searchParams.delete("juridiction");
  parsedUrl.searchParams.delete("page");
  window.location.replace(parsedUrl);
}

function copyArretUrl(titre){
  var btnCpy = document.getElementById("btn-cpy");
  btnCpy.children[0].setAttribute("class","bi bi-check2-square");
  setTimeout(redisplayClipBoard, 5000);
  navigator.clipboard.writeText(titre+' ('+window.location.href+')');
}

function redisplayClipBoard(){
  var btnCpy = document.getElementById("btn-cpy");
  btnCpy.children[0].setAttribute("class","bi bi-clipboard");
}
