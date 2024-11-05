<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="../css/header.css">
  <link rel="stylesheet" href="../css/header.scss">
  <script src="../javascript/header.js" defer></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
  <title>Header Responsive</title>
</head>
<body>

<div class="mobile-container">
  <!-- Top Navigation Menu -->
  <div class="topnav">
    <a href="#home" class="logo active">Logo</a>

    <!-- Menu icon container -->
    <div class="container">
      <input type="checkbox" id="toggle" />
      <label class="button" for="toggle">
        <nav class="nav">
          <ul>
            <li><a href="#0"><i class="ri-mic-line"></i></a></li>
            <li><a href="#0"><i class="ri-message-2-line"></i></a></li>
            <li><a href="#0"><i class="ri-file-line"></i></a></li>
            <li><a href="#0"><i class="ri-send-plane-2-line"></i></a></li>
          </ul>
        </nav>
      </label>
    </div>

    <!-- Menu and login icons -->
    <div class="menu-icons">
      <a href="javascript:void(0);" class="icon" onclick="toggleMenu()">
        <i class="fa fa-bars" id="menu-icon"></i>
      </a>
      <a href="#login" class="login">Se connecter</a>
    </div>
  </div>
</div>

</body>
</html>
