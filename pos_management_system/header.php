
<header class="header">
   <div class="header-2">
   <div class="flex">
    <div class="elliptical-buttons">
        <a href="customer_crud.php" class="btn-ellipse">Clients</a>
        <a href="archive.php" class="btn-ellipse">Archive</a>
        <a href="dette.php" class="btn-ellipse">dette</a>
        <a href="pos.php" class="btn-ellipse">POS</a>
    </div>

    <a href="home.php" class="logo">Pouina.</a>

    <div class="icons">
      <div id="user-btn" class="fas fa-user">
        <?php
        $select_cart_number = mysqli_query($conn, "SELECT * FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
        $cart_rows_number = mysqli_num_rows($select_cart_number);
        ?>

<div class="user-box">
            <p>username : <span><?php echo $_SESSION['user_name']; ?></span></p>
            <p>email : <span><?php echo $_SESSION['user_email']; ?></span></p>
            <a href="logout.php" class="delete-btn">logout</a>
         </div>
      </div>  
    </div>
  
    </div>
</div>

   </div>
</header>