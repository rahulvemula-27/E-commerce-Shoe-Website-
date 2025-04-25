<?php
// Initialize the session
session_start();

// Include database connection
require_once "config.php";

// Initialize the cart if it doesn't exist
if(!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Handle sorting
$sort_option = "default";
if(isset($_GET['sort'])) {
    $sort_option = $_GET['sort'];
}

// Handle remove from cart
if(isset($_GET['remove']) && is_numeric($_GET['remove'])) {
    $product_id = (int)$_GET['remove'];
    
    // Find the product in the cart and remove it
    if(isset($_SESSION['cart'][$product_id])) {
        unset($_SESSION['cart'][$product_id]);
        
        // Redirect to avoid accidental refreshes removing items
        header("Location: cart.php" . (isset($_GET['sort']) ? "?sort=" . $_GET['sort'] : ""));
        exit;
    }
}

// Get cart items from database
$cart_items = [];
if(!empty($_SESSION['cart'])) {
    // Create a comma-separated list of product IDs
    $product_ids = implode(',', array_keys($_SESSION['cart']));
    
    // Get sorting clause
    $sort_clause = "";
    switch($sort_option) {
        case 'price_asc':
            $sort_clause = "ORDER BY price ASC";
            break;
        case 'price_desc':
            $sort_clause = "ORDER BY price DESC";
            break;
        default:
            $sort_clause = "ORDER BY id ASC";
    }
    
    // Fetch the products in the cart
    $sql = "SELECT * FROM products WHERE id IN ($product_ids) $sort_clause";
    $result = mysqli_query($conn, $sql);
    
    if($result) {
        while($row = mysqli_fetch_assoc($result)) {
            $row['quantity'] = $_SESSION['cart'][$row['id']]['quantity'];
            $row['color'] = $_SESSION['cart'][$row['id']]['color'];
            $row['size'] = $_SESSION['cart'][$row['id']]['size'];
            $cart_items[] = $row;
        }
    }
}

// Calculate total
$total = 0;
foreach($cart_items as $item) {
    $total += $item['price'] * $item['quantity'];
}
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
    <title>.Sneakers - Shopping Cart</title>
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
        
        .cart-container {
            max-width: 1100px;
            margin: 30px auto;
            padding: 20px;
        }
        
        .cart-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #eee;
            padding-bottom: 10px;
        }
        
        .sort-options {
            display: flex;
            align-items: center;
        }
        
        .sort-options select {
            margin-left: 10px;
            padding: 8px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .cart-empty {
            text-align: center;
            padding: 50px 0;
            color: #666;
        }
        
        .cart-table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .cart-table th {
            text-align: left;
            padding: 12px;
            background-color: #f9f9f9;
            border-bottom: 2px solid #eee;
        }
        
        .cart-table td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            vertical-align: middle;
        }
        
        .product-image-cart {
            width: 80px;
            height: 80px;
            object-fit: contain;
        }
        
        .product-details-cart {
            display: flex;
            flex-direction: column;
        }
        
        .product-title-cart {
            font-weight: bold;
            margin-bottom: 5px;
        }
        
        .product-category-cart {
            font-size: 12px;
            color: #666;
        }
        
        .product-color-size {
            font-size: 13px;
            color: #666;
            margin-top: 5px;
        }
        
        .quantity-selector {
            display: flex;
            align-items: center;
        }
        
        .quantity-selector button, .remove-btn {
            background: none;
            border: 1px solid #ddd;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 3px;
        }
        
        .quantity-selector button:hover, .remove-btn:hover {
            background-color: #f5f5f5;
        }
        
        .quantity-selector span {
            margin: 0 10px;
        }
        
        .remove-btn {
            color: #e74c3c;
        }
        
        .cart-footer {
            margin-top: 30px;
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }
        
        .cart-total {
            font-size: 18px;
            margin-right: 20px;
        }
        
        .checkout-btn {
            background-color: #333;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
            border-radius: 5px;
            font-weight: bold;
            text-decoration: none;
        }
        
        .checkout-btn:hover {
            background-color: #555;
        }
        
        .continue-shopping {
            display: inline-block;
            margin-top: 20px;
            color: #333;
            text-decoration: none;
        }
        
        .continue-shopping:hover {
            text-decoration: underline;
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
                        <?php if(isset($_SESSION['cart']) && !empty($_SESSION['cart'])): ?>
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
    
    <h1 class="page-title">Shopping Cart</h1>
    
    <div class="cart-container">
        <?php if(!empty($cart_items)): ?>
            <div class="cart-header">
                <h2>Your Items</h2>
                <div class="sort-options">
                    <span>Sort by:</span>
                    <select onchange="window.location.href='cart.php?sort='+this.value">
                        <option value="default" <?php echo $sort_option == 'default' ? 'selected' : ''; ?>>Default</option>
                        <option value="price_asc" <?php echo $sort_option == 'price_asc' ? 'selected' : ''; ?>>Price: Low to High</option>
                        <option value="price_desc" <?php echo $sort_option == 'price_desc' ? 'selected' : ''; ?>>Price: High to Low</option>
                    </select>
                </div>
            </div>
            
            <table class="cart-table">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Price</th>
                        <th>Quantity</th>
                        <th>Total</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($cart_items as $item): 
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
                        
                        // Determine image path based on color selection
                        $color_index = $item['color'];
                        $image_suffix = ($color_index == '1') ? '2' : ''; // If color index is 1, use second color image
                        $image_path = "./img/{$product_base}{$image_suffix}.png";
                        
                        // If there's an image in the database, use that instead
                        if(!empty($item['image'])) {
                            $image_path = $item['image'];
                        }
                        
                        // Map color index to name
                        $color_names = [
                            '0' => 'Black/Primary',
                            '1' => 'Alternate'
                        ];
                        $color_name = isset($color_names[$color_index]) ? $color_names[$color_index] : 'Default';
                    ?>
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center;">
                                    <img src="<?php echo $image_path; ?>" alt="<?php echo htmlspecialchars($item['title']); ?>" class="product-image-cart">
                                    <div class="product-details-cart" style="margin-left: 15px;">
                                        <span class="product-title-cart"><?php echo htmlspecialchars($item['title']); ?></span>
                                        <span class="product-category-cart"><?php echo htmlspecialchars($item['category']); ?></span>
                                        <span class="product-color-size">
                                            <?php echo "Color: $color_name, Size: " . htmlspecialchars($item['size']); ?>
                                        </span>
                                    </div>
                                </div>
                            </td>
                            <td>₹<?php echo number_format($item['price'], 2); ?></td>
                            <td>
                                <form action="update_quantity.php" method="post" class="quantity-selector">
                                    <input type="hidden" name="product_id" value="<?php echo $item['id']; ?>">
                                    <button type="submit" name="action" value="decrease">-</button>
                                    <span><?php echo $item['quantity']; ?></span>
                                    <button type="submit" name="action" value="increase">+</button>
                                </form>
                            </td>
                            <td>₹<?php echo number_format($item['price'] * $item['quantity'], 2); ?></td>
                            <td>
                                <a href="cart.php?remove=<?php echo $item['id']; ?><?php echo isset($_GET['sort']) ? '&sort=' . $_GET['sort'] : ''; ?>" class="remove-btn">Remove</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            
            <div class="cart-footer">
                <div class="cart-total">Total: ₹<?php echo number_format($total, 2); ?></div>
                <a href="checkout.php" class="checkout-btn">Proceed to Checkout</a>
            </div>
        <?php else: ?>
            <div class="cart-empty">
                <p>Your cart is empty.</p>
                <a href="product.php" class="continue-shopping">Continue Shopping</a>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>