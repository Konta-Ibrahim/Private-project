<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <!-- Bootstrap 3 CSS et JavaScript, car BootstrapDialog est conçu pour Bootstrap 3 -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <!-- BootstrapDialog -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.35.4/css/bootstrap-dialog.min.css">
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.35.4/js/bootstrap-dialog.min.js"></script>
    <!-- Votre Script -->
    <script src="../js/script.js?v=<?php echo time(); ?>"></script>
    <link rel="stylesheet" href="../css/style.css?v=<?php echo time(); ?>">
    <title>Document</title>
</head>
<body>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8 ">
                <div class="SearchInputContainer">
                    <input type="text" placeholder="Search product ...">
                </div>
                <div class="SearchResultContainer">
                    <div class="row grid-layout-container">
                        <!-- Product 1 -->
                        <div class="col-4 productColContainer" data-pid="32" data-name="Tableau 1" data-price="88.33" data-stock="10">
                            <div class="ProductResultContainer">
                                <img src="../images/b (2).webp" alt="" class="productImage">
                                <div class="ProductInfoContainer">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <p class="ProductName">Tableau 1</p>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="ProductPrice">$88.33</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product 2 -->
                        <div class="col-4 productColContainer" data-pid="33" data-name="Tableau 2" data-price="98.50" data-stock="5">
                            <div class="ProductResultContainer">
                                <img src="../images/b (3).png" alt="" class="productImage">
                                <div class="ProductInfoContainer">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <p class="ProductName">Tableau 2</p>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="ProductPrice">$98.50</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product 3 -->
                        <div class="col-4 productColContainer" data-pid="34" data-name="Tableau 3" data-price="112.99" data-stock="20">
                            <div class="ProductResultContainer">
                                <img src="../images/b (1).avif" alt="" class="productImage">
                                <div class="ProductInfoContainer">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <p class="ProductName">Tableau 3</p>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="ProductPrice">$112.99</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product 4 -->
                        <div class="col-4 productColContainer" data-pid="35" data-name="Tableau 4" data-price="75.00" data-stock="15">
                            <div class="ProductResultContainer">
                                <img src="../images/b (4).png" alt="" class="productImage">
                                <div class="ProductInfoContainer">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <p class="ProductName">Tableau 4</p>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="ProductPrice">$75.00</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product 5 -->
                        <div class="col-4 productColContainer" data-pid="36" data-name="Tableau 5" data-price="65.20" data-stock="25">
                            <div class="ProductResultContainer">
                                <img src="../images/b (5).png" alt="" class="productImage">
                                <div class="ProductInfoContainer">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <p class="ProductName">Tableau 5</p>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="ProductPrice">$65.20</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product 6 -->
                        <div class="col-4 productColContainer" data-pid="37" data-name="Tableau 6" data-price="120.00" data-stock="12">
                            <div class="ProductResultContainer">
                                <img src="../images/b (6).png" alt="" class="productImage">
                                <div class="ProductInfoContainer">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <p class="ProductName">Tableau 6</p>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="ProductPrice">$120.00</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product 7 -->
                        <div class="col-4 productColContainer" data-pid="38" data-name="Tableau 7" data-price="150.00" data-stock="7">
                            <div class="ProductResultContainer">
                                <img src="../images/b (7).png" alt="" class="productImage">
                                <div class="ProductInfoContainer">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <p class="ProductName">Tableau 7</p>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="ProductPrice">$150.00</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product 8 -->
                        <div class="col-4 productColContainer" data-pid="39" data-name="Tableau 8" data-price="90.00" data-stock="30">
                            <div class="ProductResultContainer">
                                <img src="../images/b (1).png" alt="" class="productImage">
                                <div class="ProductInfoContainer">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <p class="ProductName">Tableau 8</p>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="ProductPrice">$90.00</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Product 9 -->
                        <div class="col-4 productColContainer" data-pid="40" data-name="Tableau 9" data-price="95.00" data-stock="14">
                            <div class="ProductResultContainer">
                                <img src="../images/b (1).webp" alt="" class="productImage">
                                <div class="ProductInfoContainer">
                                    <div class="row">
                                        <div class="col-md-8">
                                            <p class="ProductName">Tableau 9</p>
                                        </div>
                                        <div class="col-md-4">
                                            <p class="ProductPrice">$95.00</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 posOrderContainer">
                <div class="pos_header">
                    <div class="setting AlignRight">
                        <a href="javascript:void(0);"><i class="fa fa-gear"></i></a>
                    </div>
                    <p class="logo">IMS</p>
                    <p class="TimeAndDate">XXX XX,XXXX XX:XX:XX XX</p>
                </div>
                <div class="pos_items_container">
                    <div class="pos_items">
                        <table class="table" id="pos_items_tbl">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Product</th>
                                    <th>Price</th>
                                    <th>Quantity</th>
                                    <th>Amount</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                <!-- Sample table row -->
                                <tr>
                                    <td>1</td>
                                    <td>Hiloc</td>
                                    <td>$4.22</td>
                                    <td>3</td>
                                    <td>$159.00</td>
                                    <td>
                                        <a href="javascript:void(0);" class="pos_items_btn"><i class="fa fa-edit"></i></a>
                                        <a href="javascript:void(0);" class="pos_items_btn"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>Hiloc</td>
                                    <td>$4.22</td>
                                    <td>3</td>
                                    <td>$159.00</td>
                                    <td>
                                        <a href="javascript:void(0);" class="pos_items_btn"><i class="fa fa-edit"></i></a>
                                        <a href="javascript:void(0);" class="pos_items_btn"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>Hiloc</td>
                                    <td>$4.22</td>
                                    <td>3</td>
                                    <td>$159.00</td>
                                    <td>
                                        <a href="javascript:void(0);" class="pos_items_btn"><i class="fa fa-edit"></i></a>
                                        <a href="javascript:void(0);" class="pos_items_btn"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>Hiloc</td>
                                    <td>$4.22</td>
                                    <td>3</td>
                                    <td>$159.00</td>
                                    <td>
                                        <a href="javascript:void(0);" class="pos_items_btn"><i class="fa fa-edit"></i></a>
                                        <a href="javascript:void(0);" class="pos_items_btn"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                                <tr>
                                    <td>1</td>
                                    <td>Hiloc</td>
                                    <td>$4.22</td>
                                    <td>3</td>
                                    <td>$159.00</td>
                                    <td>
                                        <a href="javascript:void(0);" class="pos_items_btn"><i class="fa fa-edit"></i></a>
                                        <a href="javascript:void(0);" class="pos_items_btn"><i class="fa fa-trash"></i></a>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div class="items_total_container">
                        <p class="items_total">
                            <span class="item_total_label">total</span>
                            <span class="item_total_value">$388888</span>
                        </p>
                    </div>
                </div>
                <div class="CheckoutBtnContainer">
                    <a href="javascript:void(0);" class="checkoutbtn">CHECKOUT</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
