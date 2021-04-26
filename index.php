<?php require("./global.php");

//logout when user check in nav bar logout button 
if(isset($_GET['logout'])){
    session_destroy();
    //$logged=false;//
    ?>
    <script type="text/javascript">
            window.location = "./";
        </script>
    <?php
}

//add to cart
if(isset($_POST['product'])){
    $p = $_POST['product'];
    $quantity = $_POST['quantity'];
    $time = time();
    $id = generateRandomString();;
    
    //check if item exists in cart
    $stmt = $db->prepare("select * from cart where productId=? and userId=?");
    $stmt->bind_param("ss", $p, $session_userId);
    $stmt->execute();
  
    $result_reviews = $stmt->get_result();
    $stmt->close();

    // update the cart table 
    if($result_reviews->num_rows > 0) {
        $stmt = $db->prepare("update cart set quantity=quantity+? where productId=? and userId=?");
        $stmt->bind_param("sss", $quantity, $p, $session_userId);
        if( $stmt->execute()){$stmt->close(); ;?>
        <script>window.location = "./cart.php"</script>
        <?php }else{echo mysqli_error($con);}

    }else{
        //inserts into the database cart
        $stmt = $db->prepare("insert into cart set productId=?, quantity=?, timeAdded=?, userId=?");
        $stmt->bind_param("ssss", $p,$quantity, $time, $session_userId);
        if( $stmt->execute()){$stmt->close();
          ?>
         

        <script>window.location = "./cart.php"</script>

        <?php }else{ echo mysqli_error($con);}
    }
    
    ?>
    <script>window.location = "./cart.php"</script>
    <?php
}

?>
<!doctype html>
<html lang="en">
  <head>
    <?php require("./includes/head.php");?>
  </head>

  <body>
    <?php require("./includes/navbar.php");?>


    <main role="main">

      <section class="jumbotron text-center">
        <div class="container">
          <h1 class="jumbotron-heading">Clear Beauty</h1>
          <p class="lead text-muted">Buying Something has never been easier</p>
          <?php if(!isset($_SESSION['email'])/*$logged==false*/){?>
          <p>
            <a href="./signup.php" class="btn btn-primary my-2">Signup</a>
            <a href="./login.php" class="btn btn-secondary my-2">Login</a>
          </p>
          <?php }else {?>
            <!--  Display user name when logged in -->
          <p class="lead"> Welcome <?php echo $session_name ?> </p>
          <?php }?>
        </div>
      </section>

      <div class="album py-5 bg-light">
        <div class="container">

          <div class="row justify-content-center align-self-center">

              <!--show all products from the database-->
            <?php $sq = "select * from products";
             $result1 = $db->query($sq);
                while ($row = $result1->fetch_assoc()) {
                ?>

            <div class="col-md-4 " style="width: 25rem;">
              <div class="card mb-4 box-shadow">
                <img class="card-img-top "  src="./uploads/<?php echo $row['image'] ?>" alt="<?php echo $row['name'] ?>">
                <div class="card-body">
                  <p class="card-title"><?php echo $row['name'] ?> - $<?php echo $row['price'] ?></p>
                  <div class="d-flex justify-content-between align-items-center">

                      <!--  handle quantity input -->
                    <form action="" method="POST">

                      <div class="btn-group">
                        <input name="product" value="<?php echo $row['id']?>" hidden>
                        <button  type="<?php if($row['quantity']>0 && isset($_SESSION['email'])/*$logged==true*/){?>submit<?php }else{echo "button";}?>"    class="btn btn-sm btn-outline-<?php if($row['quantity']>0 && isset($_SESSION['email']) /*$logged==true*/){?>primary<?php } else {echo "disabled";}?>">Add to Cart</button>
                        <input class="ml-2 form-control" name="quantity" type="number" min="1" max="<?php echo $row['quantity']?>" value="1" required/>
                      </div>
                        
                    </form>
                  
                   
                  </div>
                  <!--  display item status according to inventory -->
                   <p><?php if($row['quantity']<1)
                   
                   {echo '  <p class= "alert alert-warning text-center" > The item is Out of Stock </p>';
                    
                   
                   }else{

                    echo '<p class= "alert alert-success text-center" > The item is Available </p>';
                    
                  
                  }
                   
                   ?></p>
                </div>
              </div>
            </div>
            <?php }mysqli_close($db);?>
          </div>
        </div>
      </div>

    </main>


    <?php require("./includes/footer.php");?>

</body>
<style>
  body {
      
      padding-bottom: 80px
    }
    </style>
</html>
