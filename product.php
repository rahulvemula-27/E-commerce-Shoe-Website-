<?php
// Initialize the session
session_start();

// Include database connection
require_once "config.php";

// Handle sorting
$sort_clause = "";
$sort_option = "default";

if(isset($_GET['sort'])) {
    $sort_option = $_GET['sort'];
    
    switch($sort_option) {
        case 'price_asc':
            $sort_clause = "ORDER BY price ASC";
            break;
        case 'price_desc':
            $sort_clause = "ORDER BY price DESC";
            break;
        default:
            $sort_clause = "ORDER BY category, id ASC";
    }
} else {
    $sort_clause = "ORDER BY category, id ASC";
}

// Prepare a select statement to get all products
$sql = "SELECT * FROM products $sort_clause";

if($stmt = mysqli_prepare($conn, $sql)){
    // Attempt to execute the prepared statement
    if(mysqli_stmt_execute($stmt)){
        $result = mysqli_stmt_get_result($stmt);
    } else{
        echo "Oops! Something went wrong. Please try again later.";
    }
} else {
    echo "Could not prepare query: " . mysqli_error($conn);
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
    <title>.Sneakers - All Products</title>
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
        
        .product-grid {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 30px;
            padding: 30px 50px;
        }
        
        .product-card {
            width: 300px;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            padding-bottom: 15px;
        }
        
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.2);
        }
        
        .product-image {
            width: 100%;
            height: 200px;
            object-fit: contain;
            background-color: #f8f8f8;
        }
        
        .product-colors {
            display: flex;
            padding: 10px;
            gap: 10px;
            justify-content: center;
        }
        
        .product-color {
            width: 20px;
            height: 20px;
            border-radius: 50%;
            cursor: pointer;
            border: 1px solid #ddd;
        }
        
        .product-color.selected {
            border: 2px solid #333;
        }
        
        .product-details {
            padding: 20px;
            text-align: center;
        }
        
        .product-title {
            font-size: 18px;
            margin-bottom: 5px;
        }
        
        .product-price {
            font-weight: bold;
            color: #333;
        }
        
        .product-category {
            display: inline-block;
            background-color: #f0f0f0;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 12px;
            margin-top: 10px;
        }
        
        .page-title {
            text-align: center;
            padding: 20px;
            font-size: 24px;
            background-color: #f5f5f5;
            margin: 0;
        }
        
        .category-title {
            width: 100%;
            padding: 10px 20px;
            margin-top: 20px;
            background-color: #f9f9f9;
            border-left: 4px solid #333;
        }
        
        .no-products {
            text-align: center;
            padding: 50px;
            font-size: 18px;
            color: #666;
        }
        
        .category-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0 50px;
            margin-top: 20px;
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
        
        .add-to-cart-form {
            margin-top: 15px;
            padding: 0 20px;
        }
        
        .size-selector {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 15px;
        }
        
        .size-option {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            border: 1px solid #ddd;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 12px;
        }
        
        .size-option.selected {
            background-color: #333;
            color: white;
            border-color: #333;
        }
        
        .add-to-cart-btn {
            width: 100%;
            padding: 10px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-weight: bold;
        }
        
        .add-to-cart-btn:hover {
            background-color: #555;
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
    
    <div class="category-header">
        <h1 class="page-title">All Products</h1>
        <div class="sort-options">
            <span>Sort by:</span>
            <select onchange="window.location.href='product.php?sort='+this.value">
                <option value="default" <?php echo $sort_option == 'default' ? 'selected' : ''; ?>>Default</option>
                <option value="price_asc" <?php echo $sort_option == 'price_asc' ? 'selected' : ''; ?>>Price: Low to High</option>
                <option value="price_desc" <?php echo $sort_option == 'price_desc' ? 'selected' : ''; ?>>Price: High to Low</option>
            </select>
        </div>
    </div>
    
    <div class="product-grid">
        <?php
        // Check if any products were found
        if(mysqli_num_rows($result) > 0) {
            // Counter to alternate between colors
            $product_count = 0;
            
            // Create an array to track which combinations we've displayed
            $displayed_combos = [];
            
            // Display each product
            while($row = mysqli_fetch_assoc($result)) {
                // Alternate between first and second color for every other product
                $default_color_index = $product_count % 2;
                
                // Map category to image name patterns
                $image_map = [
                    'AIR FORCE' => 'air',
                    'JORDAN' => 'jordan',
                    'BLAZER' => 'blazer',
                    'CRATER' => 'crater',
                    'HIPPIE' => 'hippie'
                ];
                
                // Get base filename for this product
                $product_base = isset($image_map[$row['category']]) ? $image_map[$row['category']] : strtolower(str_replace(' ', '', $row['category']));
                
                // Create a unique key for this product category + color
                $combo_key = $row['category'] . '_' . $default_color_index;
                
                // If we've already displayed this combo, skip to next color
                if (in_array($combo_key, $displayed_combos)) {
                    $default_color_index = ($default_color_index + 1) % 2;
                    $combo_key = $row['category'] . '_' . $default_color_index;
                }
                
                // Add this combo to our tracking array
                $displayed_combos[] = $combo_key;
                
                // Increment counter for next product
                $product_count++;
                
                // Generate image path based on color
                $image_path = "./img/" . $product_base . ($default_color_index == 1 ? "2" : "") . ".png";
                
                // If there's an image in the database record, use that instead
                if(!empty($row['image'])) {
                    $image_path = $row['image'];
                }
                ?>
                <div class="product-card">
                    <img src="<?php echo $image_path; ?>" alt="<?php echo htmlspecialchars($row['title']); ?>" class="product-image">
                    
                    <div class="product-details">
                        <h3 class="product-title"><?php echo htmlspecialchars($row['title']); ?></h3>
                        <p class="product-price">₹<?php echo number_format($row['price'], 2); ?></p>
                        <span class="product-category"><?php echo htmlspecialchars($row['category']); ?></span>
                    </div>
                    
                    <form action="update_cart.php" method="post" class="add-to-cart-form">
                        <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                        <input type="hidden" name="action" value="add">
                        <input type="hidden" name="quantity" value="1">
                        <input type="hidden" name="color" value="<?php echo $default_color_index; ?>" id="color-input-<?php echo $row['id']; ?>">
                        <input type="hidden" name="size" value="" id="size-input-<?php echo $row['id']; ?>">
                        
                        <div class="product-colors">
                            <div class="product-color <?php echo $default_color_index == 0 ? 'selected' : ''; ?>" 
                                 data-product-id="<?php echo $row['id']; ?>" 
                                 data-color-index="0" 
                                 style="background-color: black;"></div>
                            <div class="product-color <?php echo $default_color_index == 1 ? 'selected' : ''; ?>" 
                                 data-product-id="<?php echo $row['id']; ?>" 
                                 data-color-index="1" 
                                 style="background-color: darkblue;"></div>
                        </div>
                        
                        <div class="size-selector">
                            <div class="size-option" data-product-id="<?php echo $row['id']; ?>" data-size="8">8</div>
                            <div class="size-option" data-product-id="<?php echo $row['id']; ?>" data-size="9">9</div>
                            <div class="size-option" data-product-id="<?php echo $row['id']; ?>" data-size="10">10</div>
                        </div>
                        
                        <button type="submit" class="add-to-cart-btn">Add to Cart</button>
                    </form>
                </div>
                <?php
            }
        } else {
            echo '<div class="no-products">No products available at this time.</div>';
        }
        ?>
    </div>

    <script>
        // Handle color selection
        document.querySelectorAll('.product-color').forEach(color => {
            color.addEventListener('click', function() {
                const productId = this.dataset.productId;
                const colorIndex = this.dataset.colorIndex;
                
                // Update hidden input
                document.getElementById('color-input-' + productId).value = colorIndex;
                
                // Update visual selection
                document.querySelectorAll('.product-color[data-product-id="' + productId + '"]').forEach(c => {
                    c.classList.remove('selected');
                });
                this.classList.add('selected');
                
                // Get the image element for this product
                const productCard = this.closest('.product-card');
                const productImage = productCard.querySelector('.product-image');
                const productCategory = productCard.querySelector('.product-category').textContent.trim();
                
                // Map product category to image base name
                const imageMap = {
                    'AIR FORCE': 'air',
                    'JORDAN': 'jordan',
                    'BLAZER': 'blazer',
                    'CRATER': 'crater',
                    'HIPPIE': 'hippie'
                };
                
                // Get base name for this product's images
                const baseName = imageMap[productCategory] || productCategory.toLowerCase().replace(' ', '');
                
                // Update image based on selected color
                const colorSuffix = colorIndex === '1' ? '2' : '';
                productImage.src = `./img/${baseName}${colorSuffix}.png`;
            });
        });
        
        // Handle size selection
        document.querySelectorAll('.size-option').forEach(size => {
            size.addEventListener('click', function() {
                const productId = this.dataset.productId;
                const sizeValue = this.dataset.size;
                
                // Update hidden input
                document.getElementById('size-input-' + productId).value = sizeValue;
                
                // Update visual selection
                document.querySelectorAll('.size-option[data-product-id="' + productId + '"]').forEach(s => {
                    s.classList.remove('selected');
                });
                this.classList.add('selected');
            });
        });
        
        // Validate form before submission
        document.querySelectorAll('.add-to-cart-form').forEach(form => {
            form.addEventListener('submit', function(e) {
                const sizeInput = this.querySelector('input[name="size"]');
                if (!sizeInput.value) {
                    e.preventDefault();
                    alert('Please select a size.');
                }
            });
        });
        
        // Pre-select the first size option for each product
        document.querySelectorAll('.product-card').forEach(card => {
            const firstSize = card.querySelector('.size-option:first-child');
            if(firstSize) {
                firstSize.click();
            }
        });
    </script>
</body>
</html>