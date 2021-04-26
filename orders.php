<?php require("./global.php");
if(!isset($_SESSION['email'])/*$logged==false*/){
    //if user is not logged in then send to homepage
    header("Location: ./");
}


?>
<!doctype html>
<html lang="en">
  <head>
    <?php require("./includes/head.php");?>
  </head>

  <body class="bg-light" >
    <?php require("./includes/navbar.php");?>


   <div class="container">
      <div class="py-5 text-center">
        <h2>Orders</h2>
      </div>



    <?php
    //gett all the orders of the user.
    $sq = "select * from orders c where c.userId='$session_userId'";
     $result2 = $db->query($sq);
        while ($roworder = $result2->fetch_assoc()) {
            $orderId = $roworder['id'];
    ?>
      <div class="row justify-content-center align-self-center">
        <div class="col-md-12 order-md-2 mb-4 " style="width: 25rem;">
          <h4 class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted">Order #<?php echo $roworder['id']?> </span> 
            <br> 
            <span class="text-muted" > Date :<?php echo date("d M Y", $roworder['timeAdded'])?> </span>
          </h4>
          <ul class="list-group mb-3">

              <?php
              $total = 0;
              // get all order details of the current order. This is a nested loop.
              $sq = "select c.id, p.name, p.price, c.quantity, p.image  from products p inner join
              orderDetails c on c.productId=p.id where c.orderId='$orderId'";
             $result1 = $db->query($sq);
                while ($row = $result1->fetch_assoc()) {
                    $total+= $row['price']* $row['quantity'];
                ?>

                <li class="list-group-item d-flex justify-content-between lh-condensed">
                  <div>
                      <div class="row">
                          <div class="col-md-1">
                              <img class="card-img-top" src="./uploads/<?php echo $row['image'] ?>" alt="<?php echo $row['name'] ?>">
                          </div>
                          <div class="col-md-11">
                              <h6 class="my-0"><?php echo $row['name']?> x <?php echo $row['quantity']?></h6>
                          </div>
                      </div>
                  </div>
                  <span class="text-muted">$<?php echo $row['price']?></span>
                </li>
            <?php } ?>

            <li class="list-group-item d-flex justify-content-between">
              <span>Total (USD)</span>
              <strong>$<?php echo $total?></strong>
            </li>
          </ul>

        </div>
      </div>
    <?php  }  mysqli_close($db); ?>
     
    </div>
    

    <?php require("./includes/footer.php");?>
</body>

<style>
  body {
      
      padding-bottom: 120px
    }
    </style>
</html>