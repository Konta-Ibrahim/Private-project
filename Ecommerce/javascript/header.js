function toggleMenu() {
    var x = document.getElementById("myLinks");
    var icon = document.getElementById("menu-icon");
    
    if (x.style.display === "flex") {
      x.style.display = "none";
      x.classList.remove("open");
      icon.classList.remove("fa-times");
      icon.classList.add("fa-bars");
    } else {
      x.style.display = "flex";
      x.classList.add("open");
      icon.classList.remove("fa-bars");
      icon.classList.add("fa-times");
    }
  }
  