
<nav class="navbar navbar-expand-md navbar-light " style="background: #8100b9;color: white;">
  <a class="navbar-brand text-white" href="./">Clear Beauty</a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav ml-auto">
     
    <!--  if user is logged in--> 
      <?php if(isset($_SESSION['email']) /*$logged==true*/){?>
      <li class="nav-item">
        <a class="nav-link text-white" href="./orders.php">Orders</a>
        </li>
        <!--  if user is not logged in-->
        <?php }else{?>
        <li class="nav-item">
        <a class="nav-link text-white" href="./login.php">Login</a>
        </li>
        <?php }?>
    <!-- if user is not logged in-->
      <?php if(!isset($_SESSION['email'])/*$logged==false*/){?>
        <li class="nav-item">
            <a class="nav-link text-white" href="./signup.php">Sign up</a>
      </li>
      <!--  if user is logged in-->
      <?php }else{?>
        <li class="nav-item">
            <a class="nav-link text-white" href="./?logout=1">Logout</a>
        </li>
      <?php }?>
    <li class="nav-item">
            <a class="nav-link text-white" href="./cart.php">Cart</a>
      </li>


    </ul>

  </div>
</nav>
<style>
    .jumbotron{
        margin: 0px;
    }
</style>