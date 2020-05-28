function openMenu() {
      var menu = document.getElementById("menu");
      if (menu.style.display === "block") {
      menu.style.display = "none";
      } else {menu.style.display = "block";}}


function openPays() {
      var pays = document.getElementById("payscols");
      var icon = document.getElementById("icon_down");
      if (pays.style.display === "block") {
          pays.style.height = "none";
          } else {pays.style.height = "auto";icon.style.display="none"}}

  function openFilter() {
        var affinercols = document.getElementById("affinercols");
        if (affinercols.style.display === "block") {
            affinercols.style.display = "none";
          } else {affinercols.style.display = "block";}}
