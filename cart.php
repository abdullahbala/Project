<?php require("./global.php");

// remove item from cart 
if(isset($_GET['rem'])){
    $rem = $_GET['rem'];
    //delete item from cart
    $stmt = $db->prepare("delete from cart where productId='$rem' and userId='$session_userId'");
    if( $stmt->execute()){$stmt->close(); ?><script>//window.location = "./cart.php"</script><?php }else{echo mysqli_error($db);}
}

if(isset($_POST['name'])){
    
    $name = $_POST['name'];
    $lname = $_POST['lname'];
    $address = $_POST['address'];
    $city = $_POST['city'];
    $state = $_POST['state'];
    $zipcode = $_POST['zipcode'];
    
    $orderId = generateRandomString();;
    
    $total = 0;

    //get all item from current user's cart
    $stmt = $db->prepare("select p.id, p.name, p.price, p.image, sum(c.quantity) quantity, p.quantity as quantityindb from cart c 
    inner join products p on c.productId=p.id where c.userId='$session_userId' group by c.productId  ");
    $stmt->execute();
    $result_reviews = $stmt->get_result();
    $stmt->close();
    if($result_reviews->num_rows > 0) {
        while($row = $result_reviews->fetch_assoc()) {
            
            
            $productId = $row['id'];
            $quantity = $row['quantity'];
            $price = $row['price'];
            $subtotal = $row['price']*$row['quantity'];
            $total+=$subtotal;
            $id =  generateRandomString();
            
            //check if sufficient quantity in database 
            if($row['quantityindb']>=$quantity){
                //create an order and add all items from cart to the order
                $stmt = $db->prepare("insert into orderDetails set orderId=?, 
                productId=?, quantity=?, price=?, total=?");
                $stmt->bind_param("sssss", $orderId, $productId, $quantity, $price, $subtotal);
                if( $stmt->execute()){$stmt->close(); ?><?php }else{echo mysqli_error($db);}
                
                
                //reduce quantity 
                $stmt = $db->prepare("update products set quantity=quantity-? where
                id=? ");
                $stmt->bind_param("ss",$quantity, $productId);
                if( $stmt->execute()){$stmt->close(); ?><?php }else{echo mysqli_error($db);}
            }else{
                $total = 0;
                //echo "not in database";
                $err= '<p class= "alert alert-danger col-xs-4 text-center" >The item is Currently not Available </p>';

            }
            
            
        }
    }
    
    if($total>0){                                
        $stmt = $db->prepare("delete from cart where userId='$session_userId'");
        if( $stmt->execute()){$stmt->close(); ?><?php }else{echo mysqli_error($db);}         
        
        $timeAdded = time();
        //create order in database
        $stmt = $db->prepare("insert into orders set id=?, userId=?, total=?, timeAdded=?, name=?,
        lname=?, address=?, zipcode=?, city=?, state=?");
        $stmt->bind_param("ssssssssss", $orderId, $session_userId, $total, $timeAdded, $name, $lname, $address, $zipcode, $city, $state);
        if( $stmt->execute()){$stmt->close();  ?><?php }else{echo mysqli_error($db);}
        
        //rediredct to orders page
        ?>
        <script>window.location="./orders.php?id=<?php echo $orderId?>"</script>
        <?php
    }
   }


  
?>
<!doctype html>
<html lang="en">
  <head>
    <?php require("./includes/head.php");?>
  </head>

  <body class= "bg-light">
    <?php require("./includes/navbar.php");?>


   <div class="container  ">
      <div class="py-5 text-center ">
        <h2>Checkout </h2>
        <p><?php echo $err?></p>
      </div>

      <div class="row justify-content-center align-self-center">
        <div class="col-md-4 order-md-2 mb-4  " style="width: 22rem;" >
          <h4 class="d-flex justify-content-between align-items-center mb-3">
            <span class="text-muted">Your cart</span>
          </h4>
          <ul class="list-group mb-3 "  >
              <?php
              $total = 0;
              //get all items in user's cart
              $sq = "select p.id, p.name, p.price, c.quantity, p.image  from products p inner join
              cart c on c.productId=p.id where c.userId='$session_userId'";
             $result1 = $db->query($sq);
                while ($row = $result1->fetch_assoc()) {
                    $total+= $row['price']* $row['quantity'];
                ?>
            <li class="list-group-item d-flex justify-content-between lh-condensed">
              <div>
                  <div class="row">
                      <div class="col-md-4">
                          <img class="card-img-top" src="./uploads/<?php echo $row['image'] ?>" alt="<?php echo $row['name'] ?>">
                      </div>
                      <div class="col-md-8">
                          <h6 class="my-0"><?php echo $row['name']?> x <?php echo $row['quantity']?></h6>
                          <small class="text-muted btn-danger text-white"><a href="?rem=<?php echo $row['id']?>" class="text-white">Remove</a></small>
                      </div>
                  </div>
                
                
              </div>
              <span class="text-muted">$<?php echo $row['price']?></span>
            </li>
            <?php  }mysqli_close($db); ?>

            <li class="list-group-item d-flex justify-content-between">
              <span>Total (USD)</span>
              <strong>$<?php echo $total?></strong>
            </li>
          </ul>
             <!-- button to return to homepage-->
            <a class="btn btn-block btn-outline-primary btn-sm" href="./">Continue Shopping</a>
         
        </div>
        

        <div class="col-md-8 order-md-1">
          <h4 class="mb-3">Contact Address</h4>
          <form action="" method="post">
            <div class="row">
              <div class="col-md-6 mb-3">
                <label for="firstName">First name</label>
                <input type="text" class="form-control" id="firstName" name="name" placeholder="" value="<?php echo $session_data['name']?>" required="">
                <div class="invalid-feedback">
                  Valid first name is required.
                </div>
              </div>
              <div class="col-md-6 mb-3">
                <label for="lastName">Last name</label>
                <input type="text" class="form-control" id="lastName" name="lname" placeholder="" value="<?php echo $session_data['lname']?>" required="">
                <div class="invalid-feedback">
                  Valid last name is required.
                </div>
              </div>
            </div>


            <div class="mb-3">
              <label for="address">Street Address</label>
              <input type="text" class="form-control" name="address" placeholder="1234 Main St" required >
            
            </div>

            <div class="row">
              <div class="col-md-4 mb-3">
                <label for="country">City</label>
                <input type="text" class="form-control" name="city" required="">
              </div>
              <div class="col-md-4 mb-3">
                <label for="country">State</label>
                <input type="text" class="form-control" placeholder="XX" maxlength="2" name="state" required="">
              </div>
              <div class="col-md-4 mb-3">
                <label for="country">Zipcode</label>
                <input type="text" class="form-control" placeholder="00000"  maxlength="5" name="zipcode" required="">
              </div>
            </div>
            <hr class="mb-4">
            

            <button class="btn btn-primary btn-lg btn-block" type="submit">Place an Order</button>
            <br>
          </form>
        </div>
      </div>

     
    </div>

 <?php require("./includes/footer.php");?>
</body>

<style>
  body {
      
      padding-bottom: 120px
    }
    </style>
</html>
