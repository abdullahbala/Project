<?php

session_start();

require("./database.php");

// this statement to manage the session of the user who is logged in
// after their input was validated and thier info stored in session variable.
//this function allows us to use info of the current user.
// when adding to cart table and getting the current user added items from cart table
//instead of Creating the same variable in every page 
if (isset($_SESSION['email']))
{   // regenerate session id after user succefully logged in
    session_regenerate_id();

        $session_pwd = $_SESSION['password'];
        $session_em =  $_SESSION['email'];
        
        //searches for user with the session email and password
        $stmt = $db->prepare("SELECT * FROM users WHERE email=? ");
        $stmt->bind_param("s", $session_em);
        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
        if ($result->num_rows > 0){
            while($row = $result->fetch_assoc()) 
            {
                if(password_verify( $session_pwd, $row['password'])) {

                    //store current user info in variable for future use.
                    // like linking the cart to  the user and usage of the name in checkout form
                
                    $session_userId = $row['id'];
                    $session_name = $row['name']." ".$row['lname'];
                    $session_email = $row['email'];
                    $session_data = $row;
                }
            }
            
            } 
        
}

// generates random string For 
// Credit https://stackoverflow.com/a/4356295. 

function generateRandomString($length = 10) {
    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    }
    return $randomString;
}

?>