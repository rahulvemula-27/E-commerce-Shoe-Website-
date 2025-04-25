<?php
// Initialize the session
session_start();

// Include database connection
require_once "config.php";

// Check if category is provided
if(!isset($_GET["category"])) {
    header("location: index.php");
    exit;
}

$category = $_GET["category"];

// Prepare a select statement to get products by category
$sql = "SELECT * FROM products WHERE category = ?";

if($stmt = mysqli_prepare($conn, $sql)){
    // Bind variables to the prepared statement as parameters
    mysqli_stmt_bind_param($stmt, "s", $param_category);
    
    // Set parameters
    $param_category = $category;
    
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
    <title>.Sneakers - <?php echo htmlspecialchars($category); ?></title>
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
        
        .no-products {
            text-align: center;
            padding: 50px;
            font-size: 18px;
            color: #666;
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
    
    <h1 class="page-title"><?php echo htmlspecialchars($category); ?> Collection</h1>
    
    <div class="product-grid">
        <?php
        if(mysqli_num_rows($result) > 0){
            $product_count = 0;
            
            // Display each product
            while($row = mysqli_fetch_assoc($result)){
                // Alternate between first and second color for every other product
                $default_color_index = $product_count % 2;
                $product_count++;
                
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
                
                // Default image (first color)
                $image_file = $product_base . '.png';
                
                // If displaying the second color by default
                if($default_color_index == 1) {
                    $image_file = $product_base . '2.png';
                }
        ?>
        <div class="product-card">
            <img src="./img/<?php echo $image_file; ?>" alt="<?php echo htmlspecialchars($row['title']); ?>" class="product-image">
            
            <div class="product-colors">
                <!-- First color option -->
                <div class="product-color" style="background-color: <?php echo $default_color_index == 0 ? '#333' : '#ddd'; ?>;" 
                     data-image="./img/<?php echo $product_base; ?>.png"></div>
                
                <!-- Second color option -->
                <div class="product-color" style="background-color: <?php echo $default_color_index == 1 ? '#333' : '#ddd'; ?>;" 
                     data-image="./img/<?php echo $product_base; ?>2.png"></div>
            </div>
            
            <div class="product-details">
                <div class="product-title"><?php echo htmlspecialchars($row['title']); ?></div>
                <div class="product-price">₹<?php echo number_format($row['price'], 2); ?></div>
                <div class="product-category"><?php echo htmlspecialchars($row['category']); ?></div>
                
                <form action="update_cart.php" method="post" style="margin-top: 15px;">
                    <input type="hidden" name="product_id" value="<?php echo $row['id']; ?>">
                    <input type="hidden" name="action" value="add">
                    <input type="hidden" name="quantity" value="1">
                    <input type="hidden" name="color" value="<?php echo $default_color_index; ?>">
                    
                    <div style="display: flex; justify-content: center; margin-bottom: 10px;">
                        <div style="display: flex;">
                            <?php
                            // Changed sizes from EU (36-42) to US (8, 9, 10)
                            $sizes = ['8', '9', '10'];
                            foreach($sizes as $index => $size) {
                                echo '<div style="width: 25px; height: 25px; display: flex; align-items: center; justify-content: center; 
                                            border: 1px solid #ddd; margin: 0 3px; border-radius: 50%; cursor: pointer; font-size: 12px;"
                                           class="size-option" data-size="' . $size . '">' . $size . '</div>';
                            }
                            ?>
                        </div>
                    </div>
                    
                    <input type="hidden" name="size" id="size-input-<?php echo $row['id']; ?>" value="9">
                    
                    <button type="submit" style="background-color: #333; color: white; border: none; padding: 5px 15px; 
                                              border-radius: 3px; cursor: pointer; width: 100%;">
                        Add to Cart
                    </button>
                </form>
            </div>
        </div>
        <?php
            }
        } else {
            echo '<div class="no-products">No products found in this category.</div>';
        }
        ?>
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
                    <form action="newsletter_subscription.php" method="post">
                        <input type="email" name="email" placeholder="your@email.com" class="fInput" required>
                        <button type="submit" class="fButton">Join!</button>
                    </form>
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
                <span class="copyright">@.Sneakers. All rights reserved. 2023.</span>
            </div>
        </div>
    </footer>
    
    <script>
        // Add event listener for color selection
        document.querySelectorAll('.product-color').forEach(color => {
            color.addEventListener('click', function() {
                // Get the product card container
                const productCard = this.closest('.product-card');
                // Get the image element
                const productImg = productCard.querySelector('.product-image');
                // Update image source
                productImg.src = this.getAttribute('data-image');
                
                // Highlight the selected color
                // First, reset all colors in this product card
                productCard.querySelectorAll('.product-color').forEach(c => {
                    c.style.backgroundColor = '#ddd';
                });
                // Then highlight the clicked color
                this.style.backgroundColor = '#333';
                
                // Update the hidden color input
                const colorIndex = Array.from(productCard.querySelectorAll('.product-color')).indexOf(this);
                productCard.querySelector('input[name="color"]').value = colorIndex;
            });
        });
        
        // Add event listener for size selection
        document.querySelectorAll('.size-option').forEach(size => {
            size.addEventListener('click', function() {
                // Get the product card container
                const productCard = this.closest('.product-card');
                // Get the product ID from the form
                const productId = productCard.querySelector('input[name="product_id"]').value;
                
                // Highlight the selected size
                // First, reset all sizes in this product card
                productCard.querySelectorAll('.size-option').forEach(s => {
                    s.style.backgroundColor = '';
                    s.style.color = '';
                    s.style.border = '1px solid #ddd';
                });
                
                // Then highlight the clicked size
                this.style.backgroundColor = '#333';
                this.style.color = 'white';
                this.style.border = '1px solid #333';
                
                // Update the hidden size input
                document.getElementById('size-input-' + productId).value = this.getAttribute('data-size');
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