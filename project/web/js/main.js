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
});

function goTo(){
  id = document.getElementById("selected-pays").value;
  link=document.getElementById(id).dataset.test;
  window.location.replace(link);
}

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
  currentlink = window.location.href;
  pays= document.getElementById("pays_filter").value;
  toGo = currentlink.replace("&pays="+pays, '');
  window.location.replace(toGo);
}
