<?php
// Initialize the session
session_start();

// Include database connection
require_once "config.php";

// Check for newsletter subscription
if(isset($_SESSION['newsletter_message'])) {
    $newsletter_message = $_SESSION['newsletter_message'];
    // Clear the message
    unset($_SESSION['newsletter_message']);
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
    <title>.Sneakers - Your Authentic Sneaker Store</title>
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
        
        .newsletter-message {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 25px;
            border-radius: 5px;
            color: white;
            z-index: 1000;
            animation: fadeOut 3s ease-in-out 2s forwards;
        }
        
        .newsletter-message.success {
            background-color: #2ecc71;
        }
        
        .newsletter-message.error {
            background-color: #e74c3c;
        }
        
        @keyframes fadeOut {
            from { opacity: 1; }
            to { opacity: 0; visibility: hidden; }
        }
    </style>
</head>

<body>
    <?php if(isset($newsletter_message)): ?>
        <div class="newsletter-message <?php echo strpos($newsletter_message, 'successfully') !== false ? 'success' : 'error'; ?>">
            <?php echo $newsletter_message; ?>
        </div>
    <?php endif; ?>

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
    
    <!-- Slider section -->
    <div class="slider">
        <div class="sliderWrapper">
            <div class="sliderItem">
                <img src="./img/air.png" alt="" class="sliderImg">
                <div class="sliderBg"></div>
                <h1 class="sliderTitle">AIR FORCE</br> NEW</br> SEASON</h1>
                <h2 class="sliderPrice">₹7495</h2>
                <a href="category.php?category=AIR FORCE" class="buyButton">BUY NOW!</a>
            </div>
            <div class="sliderItem">
                <img src="./img/jordan.png" alt="" class="sliderImg">
                <div class="sliderBg"></div>
                <h1 class="sliderTitle">AIR JORDAN</br> NEW</br> SEASON</h1>
                <h2 class="sliderPrice">₹14995</h2>
                <a href="category.php?category=JORDAN" class="buyButton">BUY NOW!</a>
            </div>
            <div class="sliderItem">
                <img src="./img/blazer.png" alt="" class="sliderImg">
                <div class="sliderBg"></div>
                <h1 class="sliderTitle">BLAZER</br> NEW</br> SEASON</h1>
                <h2 class="sliderPrice">₹6295</h2>
                <a href="category.php?category=BLAZER" class="buyButton">BUY NOW!</a>
            </div>
            <div class="sliderItem">
                <img src="./img/crater.png" alt="" class="sliderImg">
                <div class="sliderBg"></div>
                <h1 class="sliderTitle">CRATER</br> NEW</br> SEASON</h1>
                <h2 class="sliderPrice">₹7995</h2>
                <a href="category.php?category=CRATER" class="buyButton">BUY NOW!</a>
            </div>
            <div class="sliderItem">
                <img src="./img/hippie.png" alt="" class="sliderImg">
                <div class="sliderBg"></div>
                <h1 class="sliderTitle">HIPPIE</br> NEW</br> SEASON</h1>
                <h2 class="sliderPrice">₹10495</h2>
                <a href="category.php?category=HIPPIE" class="buyButton">BUY NOW!</a>
            </div>
        </div>
        
        <!-- Slider navigation buttons -->
        <div class="slider-nav">
            <div class="slider-prev" onclick="prevSlide()">&#10094;</div>
            <div class="slider-next" onclick="nextSlide()">&#10095;</div>
        </div>
    </div>
    
    <!-- Features section -->
    <div class="features">
        <div class="feature">
            <img src="./img/shipping.png" alt="" class="featureIcon">
            <span class="featureTitle">FREE SHIPPING</span>
            <span class="featureDesc">Free worldwide shipping on all orders.</span>
        </div>
        <div class="feature">
            <img src="./img/return.png" alt="" class="featureIcon">
            <span class="featureTitle">30 DAYS RETURN</span>
            <span class="featureDesc">No question return and easy refund in 14 days.</span>
        </div>
        <div class="feature">
            <img src="./img/gift.png" alt="" class="featureIcon">
            <span class="featureTitle">GIFT CARDS</span>
            <span class="featureDesc">Buy gift cards and use coupon codes easily.</span>
        </div>
        <div class="feature">
            <img src="./img/contact.png" alt="" class="featureIcon">
            <span class="featureTitle">CONTACT US!</span>
            <span class="featureDesc">Keep in touch via email and support system.</span>
        </div>
    </div>
    
    <!-- Product section -->
    <div class="product" id="product">
        <img src="./img/air.png" alt="" class="productImg">
        <div class="productDetails">
            <h1 class="productTitle">AIR FORCE</h1>
            <h2 class="productPrice">₹7495</h2>
            <div class="colors">
                <div class="color"></div>
                <div class="color"></div>
            </div>
            <div class="sizes">
                <!-- Updated sizes from 36-42 to 8, 9, 10 -->
                <div class="size">8</div>
                <div class="size">9</div>
                <div class="size">10</div>
            </div>
            <button class="productButton">BUY NOW!</button>
        </div>
        
        <div class="payment">
            <h1 class="payTitle">Personal Information</h1>
            <label>Name and Surname</label>
            <input type="text" placeholder="John Doe" class="payInput">
            <label>Phone Number</label>
            <input type="text" placeholder="+91 12345 67890" class="payInput">
            <label>Address</label>
            <input type="text" placeholder="15B, Raj Nagar, Delhi - 110085" class="payInput">
            <h1 class="payTitle">Card Information</h1>
            <div class="cardIcons">
                <img src="./img/visa.png" width="40" alt="" class="cardIcon">
                <img src="./img/master.png" width="40" alt="" class="cardIcon">
            </div>
            <input type="password" class="payInput" placeholder="Card Number">
            <div class="cardInfo">
                <input type="text" placeholder="mm" class="payInput sm">
                <input type="text" placeholder="yyyy" class="payInput sm">
                <input type="text" placeholder="cvv" class="payInput sm">
            </div>
            <button class="payButton">Checkout!</button>
            <span class="close">X</span>
        </div>
    </div>
    
    <!-- Gallery section -->
    <div class="gallery">
        <div class="galleryItem">
            <h1 class="galleryTitle">Wear The Style</h1>
            <img src="https://images.pexels.com/photos/9295809/pexels-photo-9295809.jpeg?auto=compress&cs=tinysrgb&dpr=2&w=500"
                alt="" class="galleryImg">
        </div>
        <div class="galleryItem">
            <img src="https://images.pexels.com/photos/1040427/pexels-photo-1040427.jpeg?auto=compress&cs=tinysrgb&dpr=2&w=500"
                alt="" class="galleryImg">
            <h1 class="galleryTitle">Be Different</h1>
        </div>
        <div class="galleryItem">
            <h1 class="galleryTitle">Live Your Passion</h1>
            <img src="https://images.pexels.com/photos/7856965/pexels-photo-7856965.jpeg?auto=compress&cs=tinysrgb&dpr=2&w=500"
                alt="" class="galleryImg">
        </div>
    </div>
    
    <!-- New season section -->
    <div class="newSeason">
        <div class="nsItem">
            <img src="https://images.pexels.com/photos/4753986/pexels-photo-4753986.jpeg?auto=compress&cs=tinysrgb&dpr=2&w=500"
                alt="" class="nsImg">
        </div>
        <div class="nsItem">
            <h3 class="nsTitleSm">WINTER NEW ARRIVALS</h3>
            <h1 class="nsTitle">New Season</h1>
            <h1 class="nsTitle">New Collection</h1>
            <a href="product.php" class="nsButton">VIEW ALL</a>
        </div>
        <div class="nsItem">
            <img src="https://images.pexels.com/photos/7856965/pexels-photo-7856965.jpeg?auto=compress&cs=tinysrgb&dpr=2&w=500"
                alt="" class="nsImg">
        </div>
    </div>
    
    <!-- Footer section -->
    <footer>
        <div class="footerLeft">
            <div class="footerMenu">
                <h1 class="fMenuTitle">About Us</h1>
                <ul class="fList">
                    <li class="fListItem">Company</li>
                    <li class="fListItem">Contact</li>
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
    
    <script src="javascript.js"></script>
</body>
</html>