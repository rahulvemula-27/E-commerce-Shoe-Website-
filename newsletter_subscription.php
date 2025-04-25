<?php
// Initialize the session
session_start();

// Include database connection
require_once "config.php";

// Define variables and initialize with empty values
$email = "";
$email_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate email
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter an email.";
    } else{
        // Prepare a select statement
        $sql = "SELECT id FROM newsletter WHERE email = ?";
        
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            
            // Set parameters
            $param_email = trim($_POST["email"]);
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Store result
                mysqli_stmt_store_result($stmt);
                
                if(mysqli_stmt_num_rows($stmt) == 1){
                    $email_err = "This email is already subscribed.";
                } else{
                    $email = trim($_POST["email"]);
                }
            } else{
                $email_err = "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Check input errors before inserting in database
    if(empty($email_err) && !empty($email)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO newsletter (email) VALUES (?)";
         
        if($stmt = mysqli_prepare($conn, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_email);
            
            // Set parameters
            $param_email = $email;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Set success message in session
                $_SESSION['newsletter_success'] = "Thank you for subscribing to our newsletter!";
                
                // Redirect back to the page they came from, or to homepage
                $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
                header("location: " . $redirect);
                exit;
            } else{
                $email_err = "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    } else {
        // Set error message in session
        $_SESSION['newsletter_error'] = $email_err;
        
        // Redirect back to the page they came from, or to homepage
        $redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'index.php';
        header("location: " . $redirect);
        exit;
    }
    
    // Close connection
    mysqli_close($conn);
    
    // If we reach here without redirecting, something went wrong
    $_SESSION['newsletter_error'] = "An unexpected error occurred. Please try again.";
    
    // Redirect to homepage
    header("location: index.php");
    exit;
}
?>