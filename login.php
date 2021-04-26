<?php require("./global.php");


if(isset($_POST["email"])){

    $email = ($_POST["email"]); 
    
    
    //check if the user existgs in database with these credentials
    $stmt = $db->prepare("SELECT * FROM users WHERE email=?  ");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $stmt->close();
    if ($result->num_rows > 0){
        while($row = $result->fetch_assoc()) 
        {
            if(password_verify($_POST['password'], $row['password'])) {
                //add user to session vaiabel when logged in succesfully

          
                $_SESSION['email'] = $email;
                $_SESSION['password'] = $_POST['password'];
                

          
                ?>
                <!--send to homepage-->
                <script>window.location = "./";</script>
                <?php
            }else{
              
              $err= '<p class= "alert alert-danger col-xs-4 text-center" >Invalid Credentials </p>';
            }
        }
        
    }else{

      $err= '<p class= "alert alert-danger col-xs-4 text-center" >Invalid Credentials </p>';
            
            }
            ;
        
}
mysqli_close($db);
?>

<!doctype html>
<html lang="en">
  <head>
    <?php require("./includes/head.php");?>
  </head>
  <?php require("./includes/navbar.php");?>
      
  <body>
  
  <div class ="form text-center">
    <form action="" method="post">
      <h1 class="h3 mb-3 ">Sign in</h1>
      <br>
      <input class="form-control" name="email" type="email" required placeholder="Email">
      <br>
         <input class="form-control" name="password" type="password" required placeholder="Password">
      <br>
      <button class="btn btn-success ">Login</button>
      <br>
      <br>
      <!-- display error -->
      <?php echo $err; ?> 
      <a class="mt-5 mb-3" href="./signup.php" > Create an Account</a>
    </form>
  </div>

 <?php require("./includes/footer.php");?>
</body>

<style>
    html,
    body {
      height: 100%;
    
    }
    body {
      align-items: center;
      justify-content: center;
      padding-bottom: 40px;
      background-color: #e1e6eb ;
    } 
    form{
      width: 100%;
      max-width: 420px ;
      padding: 15px;
      margin: 0 auto;
        width: 380px;
        margin: 50px auto;
        font-size: 15px;
        background: #f7f7f7;
        padding: 27px;
    }

</style>
</html>