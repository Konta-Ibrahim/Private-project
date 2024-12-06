<?php
if (isset($message)) {
   foreach ($message as $message) {
      echo '
      <div class="message">
         <span>' . $message . '</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>

<aside class="sidebar">

   <div class="sidebar-header">
      <a href="admin_page.php" class="logo">Admin<span>Panel</span></a>
   </div>

   <nav class="sidebar-nav">
      <a href="admin_page.php"><i class="fas fa-home"></i> Home</a>
      <a href="admin_products.php"><i class="fas fa-box"></i> Products</a>
      <a href="admin_orders.php"><i class="fas fa-shopping-cart"></i> Orders</a>
      <a href="admin_users.php"><i class="fas fa-users"></i> Users</a>
      <a href="admin_contacts.php"><i class="fas fa-envelope"></i> Messages</a>
   </nav>

   <div class="sidebar-footer">
      <div class="account-box">
         <p>Username: <span><?php echo $_SESSION['admin_name']; ?></span></p>
         <p>Email: <span><?php echo $_SESSION['admin_email']; ?></span></p>
         <a href="logout.php" class="delete-btn">Logout</a>
         <div>New <a href="login.php">Login</a> | <a href="register.php">Register</a></div>
      </div>
   </div>

</aside>
