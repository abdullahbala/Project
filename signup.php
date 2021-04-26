<?php require("./global.php");

if(isset($_POST['name'])){
    $id = generateRandomString();
    //store the input in variable in  order to store it in db
    $name = ($_POST['name']);
    $lname = ($_POST['lname']);
    $email = ($_POST['email']);

    //check if the password and confirm password are the same
    if($_POST['password']==$_POST['password1']){
        // hash the input password 
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    
        //insert user into database
        $stmt = $db->prepare("insert into users set name=?, email=?, password=?, lname=?");
        $stmt->bind_param("ssss", $name, $email, $password, $lname);
    	if( $stmt->execute()){
    		$stmt->close();
    		header("Location: ./login.php");
    	}else{
        // message if user email address existed
      $err= '<p class= "alert alert-danger col-xs-4 text-center" > User with same email already exist choose another email address </p>';
      
    	}
    }
    else
        {
      //message if password are not matched
        $err= '<p class= "alert alert-danger col-xs-4 text-center" > Passwords must match </p>';
    }
    }


    mysqli_close($db);
?>
<!doctype html>
<html lang="en">
  <head>
    <?php require("./includes/head.php");?>
  </head>

  <body >
    <?php require("./includes/navbar.php");?>

    <div class ="form text-center">
<form action="" method="post">
<h1 class="h3 mb-3 ">Signup</h1>
                <input class="form-control" name="name" required placeholder="Firstname" >
                <br>
                <input class="form-control" name="lname" required placeholder="Last name">
                <br>
                <input class="form-control" name="email" type="email" required placeholder="Email">
                <br>
                <input class="form-control" name="password" type="password" required placeholder="Password" minlength="8">
                <br>
                <input class="form-control" name="password1" type="password" required placeholder="Confirm Password" minlength="8">
                <br>
                <button class="btn btn-success">Signup</button>

                 <!-- display error -->
                <p><?php echo $err?></p>

                <!-- link to login page-->
               <a class="mt-5 mb-3"> Already have an Account ? <a class="mt-5 mb-3" href="./login.php" > Sign-in </a>
            </form>
</div>

    <?php require("./includes/footer.php");?>
</body>

<!-- style the form -->
<style>

    
    body {
      height: 100%;
      padding-bottom: 120px
    }
    body {
      align-items: center;
      justify-content: center;
      padding-bottom: 44px;
      background-color: #e1e6eb;
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

