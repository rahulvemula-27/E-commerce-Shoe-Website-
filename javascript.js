// Wait for the DOM to be fully loaded
document.addEventListener("DOMContentLoaded", function () {
    // Get DOM elements
    const wrapper = document.querySelector(".sliderWrapper");
    const menuItems = document.querySelectorAll(".menuItem");
    const productImg = document.querySelector(".productImg");
    const productTitle = document.querySelector(".productTitle");
    const productPrice = document.querySelector(".productPrice");
    const productColors = document.querySelectorAll(".color");
    const productSizes = document.querySelectorAll(".size");

    // Product data
    const products = [
        {
            id: 1,
            title: "AIR FORCE",
            price: 7495,
            colors: [
                {
                    code: "black",
                    img: "./img/air.png",
                },
                {
                    code: "darkblue",
                    img: "./img/air2.png",
                },
            ],
        },
        {
            id: 2,
            title: "AIR JORDAN",
            price: 14995,
            colors: [
                {
                    code: "lightgray",
                    img: "./img/jordan.png",
                },
                {
                    code: "green",
                    img: "./img/jordan2.png",
                },
            ],
        },
        {
            id: 3,
            title: "BLAZER",
            price: 6295,
            colors: [
                {
                    code: "lightgray",
                    img: "./img/blazer.png",
                },
                {
                    code: "green",
                    img: "./img/blazer2.png",
                },
            ],
        },
        {
            id: 4,
            title: "CRATER",
            price: 7995,
            colors: [
                {
                    code: "black",
                    img: "./img/crater.png",
                },
                {
                    code: "lightgray",
                    img: "./img/crater2.png",
                },
            ],
        },
        {
            id: 5,
            title: "HIPPIE",
            price: 10495,
            colors: [
                {
                    code: "gray",
                    img: "./img/hippie.png",
                },
                {
                    code: "black",
                    img: "./img/hippie2.png",
                },
            ],
        },
    ];

    // Current slide and slide interval timer
    let currentSlide = 0;
    let slideInterval;

    // Initial chosen product
    let chosenProduct = products[0];

    // Initialize color choices
    if (productColors && productColors.length > 0) {
        productColors.forEach((color, index) => {
            if (index < chosenProduct.colors.length) {
                color.style.backgroundColor = chosenProduct.colors[index].code;
            }
        });
    }

    // Function to update the product display
    function updateProductDisplay(index) {
        // Update chosen product
        chosenProduct = products[index];

        // Update product details
        if (productTitle) productTitle.textContent = chosenProduct.title;
        if (productPrice) productPrice.textContent = "₹" + chosenProduct.price;
        if (productImg) productImg.src = chosenProduct.colors[0].img;

        // Update colors
        if (productColors) {
            productColors.forEach((color, i) => {
                if (i < chosenProduct.colors.length) {
                    color.style.backgroundColor = chosenProduct.colors[i].code;
                }
            });
        }
    }

    // Function to change the slide
    function changeSlide(index) {
        // Update current slide index
        currentSlide = index;

        // Check if wrapper exists (might not exist on all pages)
        if (wrapper) {
            // Move the slider
            wrapper.style.transform = `translateX(${-100 * currentSlide}vw)`;

            // Log to verify function is being called
            console.log("Changing slide to:", currentSlide);
        }

        // Update product display
        updateProductDisplay(currentSlide);
    }

    // Function to go to the next slide
    function nextSlide() {
        // Calculate next slide index (with wraparound)
        const nextIndex = (currentSlide + 1) % products.length;

        // Change to next slide
        changeSlide(nextIndex);
    }

    // Start the automatic slideshow
    function startSlideshow() {
        // Clear any existing interval
        if (slideInterval) {
            clearInterval(slideInterval);
        }

        // Set a new interval for auto-rotating slides
        slideInterval = setInterval(function () {
            nextSlide();
        }, 5000); // Change slide every 5 seconds

        console.log("Slideshow started");
    }

    // Menu item click events
    menuItems.forEach((item, index) => {
        item.addEventListener("click", function () {
            // Only perform slide change if on page with slider
            if (wrapper) {
                // Reset slideshow timer
                clearInterval(slideInterval);
                startSlideshow();

                // Change to selected slide
                changeSlide(index);
            }
        });
    });

    // Product color selection
    if (productColors) {
        productColors.forEach((color, index) => {
            color.addEventListener("click", function () {
                // Update product image based on selected color
                if (productImg && chosenProduct.colors[index]) {
                    productImg.src = chosenProduct.colors[index].img;
                }
            });
        });
    }

    // Product size selection - Updated with sizes 8, 9, 10
    if (productSizes) {
        productSizes.forEach((size) => {
            size.addEventListener("click", function () {
                // Reset all sizes
                productSizes.forEach((s) => {
                    s.style.backgroundColor = "white";
                    s.style.color = "black";
                });

                // Highlight selected size
                size.style.backgroundColor = "black";
                size.style.color = "white";
            });
        });
    }

    // Payment modal toggle
    const productButton = document.querySelector(".productButton");
    const payment = document.querySelector(".payment");
    const close = document.querySelector(".close");

    if (productButton) {
        productButton.addEventListener("click", function () {
            payment.style.display = "flex";
        });
    }

    if (close) {
        close.addEventListener("click", function () {
            payment.style.display = "none";
        });
    }

    // Start the slideshow when the page loads
    if (wrapper) {
        console.log("Initializing slideshow");
        startSlideshow();
    }
});