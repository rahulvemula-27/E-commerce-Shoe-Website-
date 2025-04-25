<?php
// Initialize the session
session_start();

// Include database connection
require_once "config.php";

// Redirect if cart is empty
if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

// Get cart items from database
$cart_items = [];
$total = 0;

if (!empty($_SESSION['cart'])) {
    // Create a comma-separated list of product IDs
    $product_ids = implode(',', array_keys($_SESSION['cart']));
    
    // Fetch the products in the cart
    $sql = "SELECT * FROM products WHERE id IN ($product_ids)";
    $result = mysqli_query($conn, $sql);
    
    if ($result) {
        while ($row = mysqli_fetch_assoc($result)) {
            $row['quantity'] = $_SESSION['cart'][$row['id']]['quantity'];
            $row['color'] = $_SESSION['cart'][$row['id']]['color'];
            $row['size'] = $_SESSION['cart'][$row['id']]['size'];
            $cart_items[] = $row;
            $total += $row['price'] * $row['quantity'];
        }
    }
}

// Handle order submission
$order_success = false;
$order_error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['place_order'])) {
    // Get form data
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $address = trim($_POST['address']);
    $city = trim($_POST['city']);
    $pincode = trim($_POST['pincode']);
    $payment_method = trim($_POST['payment_method']);
    
    // Basic validation
    if (empty($name) || empty($email) || empty($phone) || empty($address) || 
        empty($city) || empty($pincode) || empty($payment_method)) {
        $order_error = "All fields are required";
    } else {
        // In a real application, you would:
        // 1. Insert order into database
        // 2. Process payment
        // 3. Send confirmation email
        
        // For this demo, we'll just simulate a successful order
        $order_success = true;
        
        // Clear the cart
        $_SESSION['cart'] = [];
    }
}

// Calculate shipping and total
$shipping = 250; // Fixed shipping rate
$grand_total = $total + $shipping;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link
        href="https://fonts.googleapis.com/css2?family=Lato:ital,wght@0,100;0,300;0,400;0,700;0,900;1,100;1,300;1,400;1,700;1,900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <title>.Sneakers - Checkout</title>
    <style>
        .user-nav {
            display: flex;
            align-items: center;
            margin-left: 20px;
        }
        
        .user-nav a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
        }
        
        .user-nav a:hover {
            text-decoration: underline;
        }
        
        .logo-link {
            display: block;
        }
        
        .page-title {
            text-align: center;
            padding: 20px;
            font-size: 24px;
            background-color: #f5f5f5;
            margin: 0;
        }
        
        .checkout-container {
            max-width: 1100px;
            margin: 30px auto;
            padding: 20px;
            display: flex;
            flex-wrap: wrap;
        }
        
        .checkout-form {
            flex: 2;
            padding-right: 30px;
        }
        
        .checkout-summary {
            flex: 1;
            min-width: 300px;
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
        }
        
        .checkout-section {
            margin-bottom: 30px;
        }
        
        .checkout-section-title {
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 1px solid #eee;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        .form-label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }
        
        .form-control {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .form-row {
            display: flex;
            gap: 15px;
        }
        
        .form-row .form-group {
            flex: 1;
        }
        
        .btn {
            padding: 12px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .btn-primary {
            background-color: #333;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #555;
        }
        
        .btn-secondary {
            background-color: #f4f4f4;
            color: #333;
        }
        
        .btn-secondary:hover {
            background-color: #e0e0e0;
        }
        
        .summary-item {
            display: flex;
            margin-bottom: 10px;
        }
        
        .summary-image {
            width: 60px;
            height: 60px;
            object-fit: contain;
        }
        
        .summary-details {
            flex: 1;
            padding-left: 10px;
        }
        
        .summary-title {
            font-weight: bold;
            margin-bottom: 3px;
        }
        
        .summary-meta {
            font-size: 12px;
            color: #666;
        }
        
        .summary-price {
            font-weight: bold;
        }
        
        .summary-totals {
            margin-top: 20px;
            border-top: 1px solid #ddd;
            padding-top: 15px;
        }
        
        .summary-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        
        .summary-total {
            font-size: 18px;
            font-weight: bold;
            margin-top: 10px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
        }
        
        .payment-options {
            margin-top: 15px;
        }
        
        .payment-option {
            margin-bottom: 10px;
        }
        
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 20px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 20px;
        }
        
        .error-message {
            background-color: #f8d7da;
            color: #721c24;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        
        .action-buttons {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
        }
    </style>
</head>

<body>
    <nav id="nav">
        <div class="navTop">
            <div class="navItem">
                <a href="index.php" class="logo-link">
                    <img src="./img/sneakers.png" alt="Sneakers Logo">
                </a>
            </div>
            <div class="navItem">
                <div class="search">
                    <input type="text" placeholder="Search..." class="searchInput">
                    <img src="./img/search.png" width="20" height="20" alt="" class="searchIcon">
                </div>
            </div>
            <div class="navItem">
                <span class="limitedOffer">Authenticity Guarantee</span>
                <div class="user-nav">
                    <?php
                    if(isset($_SESSION["loggedin"]) && $_SESSION["loggedin"] === true) {
                        echo '<span style="color:white;">Welcome, ' . htmlspecialchars($_SESSION["username"]) . '!</span> &nbsp;';
                        echo '<a href="logout.php">Logout</a>';
                    } else {
                        echo '<a href="login.php">Login</a> / ';
                        echo '<a href="register.php">Register</a>';
                    }
                    ?>
                    <a href="cart.php" style="position: relative; margin-left: 15px; color: white; text-decoration: none;">
                        Cart
                        <?php if(!empty($_SESSION['cart'])): ?>
                        <span style="position: absolute; top: -8px; right: -8px; background-color: red; color: white; 
                                 border-radius: 50%; width: 16px; height: 16px; font-size: 10px; 
                                 display: flex; align-items: center; justify-content: center;">
                            <?php echo array_sum(array_column($_SESSION['cart'], 'quantity')); ?>
                        </span>
                        <?php endif; ?>
                    </a>
                </div>
            </div>
        </div>
        <div class="navBottom">
            <a href="category.php?category=AIR FORCE" style="text-decoration:none; color:inherit;"><h3 class="menuItem">AIR FORCE</h3></a>
            <a href="category.php?category=JORDAN" style="text-decoration:none; color:inherit;"><h3 class="menuItem">JORDAN</h3></a>
            <a href="category.php?category=BLAZER" style="text-decoration:none; color:inherit;"><h3 class="menuItem">BLAZER</h3></a>
            <a href="category.php?category=CRATER" style="text-decoration:none; color:inherit;"><h3 class="menuItem">CRATER</h3></a>
            <a href="category.php?category=HIPPIE" style="text-decoration:none; color:inherit;"><h3 class="menuItem">HIPPIE</h3></a>
            <a href="product.php" style="text-decoration:none; color:inherit;"><h3 class="menuItem">ALL PRODUCTS</h3></a>
        </div>
    </nav>
    
    <h1 class="page-title">Checkout</h1>
    
    <div class="checkout-container">
        <?php if($order_success): ?>
            <div class="success-message" style="width: 100%;">
                <h2>Thank you for your order!</h2>
                <p>Your order has been placed successfully. We will process it soon.</p>
                <p>You'll receive an email confirmation shortly.</p>
                <a href="product.php" class="btn btn-primary" style="display: inline-block; margin-top: 15px;">Continue Shopping</a>
            </div>
        <?php else: ?>
            <?php if(!empty($order_error)): ?>
                <div class="error-message" style="width: 100%;">
                    <?php echo $order_error; ?>
                </div>
            <?php endif; ?>
            
            <div class="checkout-form">
                <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <div class="checkout-section">
                        <h2 class="checkout-section-title">Personal Information</h2>
                        <div class="form-group">
                            <label class="form-label">Full Name</label>
                            <input type="text" name="name" class="form-control" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">Email Address</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Phone Number</label>
                                <input type="tel" name="phone" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="checkout-section">
                        <h2 class="checkout-section-title">Shipping Address</h2>
                        <div class="form-group">
                            <label class="form-label">Address</label>
                            <input type="text" name="address" class="form-control" required>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label class="form-label">City</label>
                                <input type="text" name="city" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label class="form-label">PIN Code</label>
                                <input type="text" name="pincode" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="checkout-section">
                        <h2 class="checkout-section-title">Payment Method</h2>
                        <div class="payment-options">
                            <div class="payment-option">
                                <input type="radio" id="cod" name="payment_method" value="cod" required>
                                <label for="cod">Cash on Delivery</label>
                            </div>
                            <div class="payment-option">
                                <input type="radio" id="card" name="payment_method" value="card" required>
                                <label for="card">Credit/Debit Card</label>
                            </div>
                            <div class="payment-option">
                                <input type="radio" id="upi" name="payment_method" value="upi" required>
                                <label for="upi">UPI</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="action-buttons">
                        <a href="cart.php" class="btn btn-secondary">Back to Cart</a>
                        <button type="submit" name="place_order" class="btn btn-primary">Place Order</button>
                    </div>
                </form>
            </div>
            
            <div class="checkout-summary">
                <h2 class="checkout-section-title">Order Summary</h2>
                
                <?php foreach($cart_items as $item): ?>
                <div class="summary-item">
                    <?php
                    // Map category to image name patterns
                    $image_map = [
                        'AIR FORCE' => 'air',
                        'JORDAN' => 'jordan',
                        'BLAZER' => 'blazer',
                        'CRATER' => 'crater',
                        'HIPPIE' => 'hippie'
                    ];
                    
                    // Get base filename for this product
                    $product_base = isset($image_map[$item['category']]) ? $image_map[$item['category']] : strtolower(str_replace(' ', '', $item['category']));
                    
                    // Determine image to show based on color
                    $img_suffix = $item['color'] == 1 ? '2' : '';
                    $image_file = $product_base . $img_suffix . '.png';
                    ?>
                    <img src="./img/<?php echo $image_file; ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" class="summary-image">
                    <div class="summary-details">
                        <div class="summary-title"><?php echo htmlspecialchars($item['title']); ?></div>
                        <div class="summary-meta">
                            <?php echo $item['color'] == 0 ? 'Primary' : 'Secondary'; ?> | 
                            Size: <?php echo $item['size']; ?> | 
                            Qty: <?php echo $item['quantity']; ?>
                        </div>
                        <div class="summary-price">₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
                
                <div class="summary-totals">
                    <div class="summary-row">
                        <span>Subtotal</span>
                        <span>₹<?php echo number_format($total, 2); ?></span>
                    </div>
                    <div class="summary-row">
                        <span>Shipping</span>
                        <span>₹<?php echo number_format($shipping, 2); ?></span>
                    </div>
                    <div class="summary-row summary-total">
                        <span>Total</span>
                        <span>₹<?php echo number_format($grand_total, 2); ?></span>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <footer>
        <div class="footerLeft">
            <div class="footerMenu">
                <h1 class="fMenuTitle">About Us</h1>
                <ul class="fList">
                    <li class="fListItem">Company</li>
                    <li class="fListItem">Contact</li>
                    <li class="fListItem">Careers</li>
                    <li class="fListItem">Affiliates</li>
                    <li class="fListItem">Stores</li>
                </ul>
            </div>
            <div class="footerMenu">
                <h1 class="fMenuTitle">Useful Links</h1>
                <ul class="fList">
                    <li class="fListItem">Support</li>
                    <li class="fListItem">Refund</li>
                    <li class="fListItem">FAQ</li>
                    <li class="fListItem">Feedback</li>
                    <li class="fListItem">Stories</li>
                </ul>
            </div>
            <div class="footerMenu">
                <h1 class="fMenuTitle">Products</h1>
                <ul class="fList">
                    <li class="fListItem">Air Force</li>
                    <li class="fListItem">Air Jordan</li>
                    <li class="fListItem">Blazer</li>
                    <li class="fListItem">Crater</li>
                    <li class="fListItem">Hippie</li>
                </ul>
            </div>
        </div>
        <div class="footerRight">
            <div class="footerRightMenu">
                <h1 class="fMenuTitle">Subscribe to our newsletter</h1>
                <div class="fMail">
                    <input type="text" placeholder="your@email.com" class="fInput">
                    <button class="fButton">Join!</button>
                </div>
            </div>
            <div class="footerRightMenu">
                <h1 class="fMenuTitle">Follow Us</h1>
                <div class="fIcons">
                    <img src="./img/facebook.png" alt="" class="fIcon">
                    <img src="./img/twitter.png" alt="" class="fIcon">
                    <img src="./img/instagram.png" alt="" class="fIcon">
                    <img src="./img/whatsapp.png" alt="" class="fIcon">
                </div>
            </div>
            <div class="footerRightMenu">
                <span class="copyright">@Sneakers. All rights reserved. 2023.</span>
            </div>
        </div>
    </footer>
</body>

</html>