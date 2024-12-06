let navbar = document.querySelector('.header .navbar');
let accountBox = document.querySelector('.header .account-box');

document.querySelector('#menu-btn').onclick = () =>{
   navbar.classList.toggle('active');
   accountBox.classList.remove('active');
}

document.querySelector('#user-btn').onclick = () =>{
   accountBox.classList.toggle('active');
   navbar.classList.remove('active');
}

window.onscroll = () =>{
   navbar.classList.remove('active');
   accountBox.classList.remove('active');
}

document.querySelector('#close-update').onclick = () =>{
   document.querySelector('.edit-product-form').style.display = 'none';
   window.location.href = 'admin_products.php';
}

document.addEventListener("DOMContentLoaded", function () {
   const alerts = document.querySelectorAll('.alert');
   alerts.forEach(alert => {
      setTimeout(() => {
         alert.style.opacity = '0';
         alert.style.transition = 'opacity 0.5s';
      }, 3000);

      setTimeout(() => {
         alert.remove();
      }, 3500);
   });
});

   // Faire disparaître les alertes après 3 secondes
   document.addEventListener("DOMContentLoaded", function () {
      const alerts = document.querySelectorAll('.alert');
      alerts.forEach(alert => {
         setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transition = 'opacity 0.5s';
         }, 3000);

         setTimeout(() => {
            alert.remove();
         }, 3500);
      });
   });


   