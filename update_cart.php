<?php
// Initialize the session
session_start();

// Check if cart exists in session
if(!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Check if product_id and action are set
if(isset($_POST['product_id']) && isset($_POST['action'])) {
    $product_id = (int)$_POST['product_id'];
    $action = $_POST['action'];
    
    // If this is a new item being added to cart
    if($action === 'add') {
        // Check if all required parameters are set
        if(isset($_POST['quantity']) && isset($_POST['color']) && isset($_POST['size'])) {
            $quantity = (int)$_POST['quantity'];
            $color = (int)$_POST['color'];
            $size = $_POST['size'];
            
            // Add to cart or update quantity if already exists
            if(isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id]['quantity'] += $quantity;
            } else {
                $_SESSION['cart'][$product_id] = [
                    'quantity' => $quantity,
                    'color' => $color,
                    'size' => $size
                ];
            }
        }
    } 
    // If this is an update to existing item
    else {
        if($action === 'increase') {
            // Increase quantity by 1
            if(isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id]['quantity']++;
            }
        } elseif($action === 'decrease') {
            // Decrease quantity by 1, remove if quantity becomes 0
            if(isset($_SESSION['cart'][$product_id])) {
                $_SESSION['cart'][$product_id]['quantity']--;
                
                if($_SESSION['cart'][$product_id]['quantity'] <= 0) {
                    unset($_SESSION['cart'][$product_id]);
                }
            }
        }
    }
}

// Redirect back to referring page or cart
$redirect_url = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'cart.php';
header("Location: $redirect_url");
exit;
?>  