<?php
// Initialize the session
session_start();

// Include database connection
require_once "config.php";

// Check if form data was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Get product ID
    if (isset($_POST['product_id']) && is_numeric($_POST['product_id'])) {
        $product_id = (int)$_POST['product_id'];
        
        // Check if product exists in cart
        if (isset($_SESSION['cart'][$product_id])) {
            // Get action type
            if (isset($_POST['action'])) {
                // Handle increase/decrease quantity
                if ($_POST['action'] == 'increase') {
                    $_SESSION['cart'][$product_id]['quantity']++;
                } elseif ($_POST['action'] == 'decrease' && $_SESSION['cart'][$product_id]['quantity'] > 1) {
                    $_SESSION['cart'][$product_id]['quantity']--;
                }
            }
        }
    }
}

// Redirect back to cart
header("Location: cart.php" . (isset($_GET['sort']) ? "?sort=" . $_GET['sort'] : ""));
exit;
?>