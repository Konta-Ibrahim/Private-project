let userBox = document.querySelector('.header .header-2 .user-box');

document.querySelector('#user-btn').onclick = () =>{
   userBox.classList.toggle('active');
   navbar.classList.remove('active');
}

let navbar = document.querySelector('.header .header-2 .navbar');

document.querySelector('#menu-btn').onclick = () =>{
   navbar.classList.toggle('active');
   userBox.classList.remove('active');
}

window.onscroll = () =>{
   userBox.classList.remove('active');
   navbar.classList.remove('active');

   if(window.scrollY > 60){
      document.querySelector('.header .header-2').classList.add('active');
   }else{
      document.querySelector('.header .header-2').classList.remove('active');
   }
}

  // Ajout d'une disparition automatique au bout de 3 secondes
  document.addEventListener("DOMContentLoaded", function () {
   const alertBoxes = document.querySelectorAll('.alert-box');
   alertBoxes.forEach(alertBox => {
       setTimeout(() => {
           alertBox.style.transform = "translateX(100%)"; // Glisse hors de l'écran
           alertBox.style.opacity = "0"; // Rend invisible
           setTimeout(() => alertBox.remove(), 500); // Supprime après l'animation
       }, 3000); // Temps d'affichage
   });
});

