function openMenu() {
  var menu = document.getElementById("menu");

  if (menu.style.display === "block") {
    menu.style.display = "none";
  } else {menu.style.display = "block";}
  }

function openPays() {
  var pays = document.getElementById("pays_content");
  var icon = document.getElementById("icon_rolldown");

  if (pays.style.display === "block") {
    pays.style.height = "none";
  } else {pays.style.height = "auto";icon.style.display="none"}}
