function initFunctions(){

  /*fonction pour la pagination*/
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
}
function goTo(){
  id = document.getElementById("selected-pays").value;
  link=document.getElementById(id).dataset.test;
  window.location.replace(link);
}
