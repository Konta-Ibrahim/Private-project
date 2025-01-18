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

   document.getElementById('scanBarcode').addEventListener('click', function () {
      const barcodeScanner = document.getElementById('barcode-scanner');
      const videoElement = document.getElementById('video');
      barcodeScanner.style.display = 'block';
  
      Quagga.init(
          {
              inputStream: {
                  name: 'Live',
                  type: 'LiveStream',
                  target: videoElement,
                  constraints: {
                      facingMode: 'environment',
                  },
              },
              decoder: {
                  readers: ['code_128_reader', 'ean_reader', 'ean_8_reader'],
              },
          },
          function (err) {
              if (err) {
                  console.error('Erreur lors de l\'initialisation :', err);
                  return;
              }
              Quagga.start();
          }
      );
  
      Quagga.onDetected(function (result) {
          const barcode = result.codeResult.code;
          console.log('Code détecté :', barcode);
  
          // Envoyer au serveur pour traitement
          fetch('add_product.php', {
              method: 'POST',
              headers: {
                  'Content-Type': 'application/json',
              },
              body: JSON.stringify({ barcode: barcode }),
          })
              .then((response) => response.json())
              .then((data) => {
                  if (data.success) {
                      alert('Produit ajouté : ' + barcode);
                  } else {
                      alert('Erreur : ' + data.message);
                  }
                  Quagga.stop();
                  barcodeScanner.style.display = 'none';
              })
              .catch((err) => {
                  console.error('Erreur serveur :', err);
                  Quagga.stop();
                  barcodeScanner.style.display = 'none';
              });
      });
  
      document.getElementById('stopScan').addEventListener('click', function () {
          Quagga.stop();
          barcodeScanner.style.display = 'none';
      });
  });
  

  function toggleSidebar() {
    const sidebar = document.getElementById('sidebar');
    const mainContent = document.getElementById('mainContent');
    sidebar.classList.toggle('collapsed');
    mainContent.classList.toggle('expanded');
 }