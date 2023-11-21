<?php

require_once "db/config.php";
 

$fname = $lname = $email = $password = $confirm_password = "";
$fname_err = $lname_err = $email_err = $password_err = $confirm_password_err = "";
 

if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    
    if(empty(trim($_POST["fname"]))){
        $fname_err = "Please enter a first name .";
    } elseif(!preg_match('/^[a-zA-Z0-9_]+$/', trim($_POST["fname"]))){
        $fname_err = "first name can only contain letters, numbers, and underscores.";
    } else{



       
        $sql = "SELECT id FROM user WHERE fname = ?";
        
        if($stmt = mysqli_prepare($link, $sql)){
            
            
            mysqli_stmt_bind_param($stmt, "s", $param_fname);
            
        
            $param_fname = trim($_POST["fname"]);
            
         
            if(mysqli_stmt_execute($stmt)){
           
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $fname_err = "This first name is already taken.";
                } else{
                    $fname = trim($_POST["fname"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            mysqli_stmt_close($stmt);
        }
    }
    
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 8){
        $password_err = "Password must have atleast 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }

     

    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter a email.";     
    } else{
        $email = trim($_POST["email"]);
    }



    if(empty(trim($_POST["lname"]))){
        $lname_err = "Please enter a last name.";     
    } else{
        $lname = trim($_POST["lname"]);
    }
    

    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }
    
    
    if(empty($fname_err) && empty($password_err)  && empty($lname_err)  && empty($email_err) && empty($confirm_password_err)){
        
       
        $sql = "INSERT INTO user (fname,lname,email, password) VALUES (?,?,?,?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
           
            mysqli_stmt_bind_param($stmt, "ssss", $param_fname,$param_lname, $param_email, $param_password);
            
            
            $param_fname = $fname;
            $param_lname = $lname;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); 
            
           
            if(mysqli_stmt_execute($stmt)){
               
                header("location: login.php");
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            
            mysqli_stmt_close($stmt);
        }
    }
    
    
    mysqli_close($link);
}
?>