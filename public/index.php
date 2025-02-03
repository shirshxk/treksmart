<?php
// Include the database connection file
require '../includes/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TrekSmart</title>
    <!-- Link to the CSS file -->
    <link rel="stylesheet" href="/TrekSmart/assets/CSS/style.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <!-- Include the Navigation Bar -->
    <?php include '../includes/header.php'; ?>

    <!-- Hero Section -->
    <div class="hero">
        <div class="hero-text">
            Navigating <b class="highlight">Adventures</b>,
            <div>Sustaining <b class="highlight">Journeys</b>.</div>
        </div>
    </div>

    <!-- Main Content -->
    <main>
        <!-- First Section: What We Offer -->
        <section class="firstsection">
            <section class="what-we-offer">
                <div class="offer-container">
                    <h2>What We Offer</h2>
                    <p>Explore our premium services designed for your adventures.</p>
                    <div class="offer-items">
                        <div class="offer-item">
                            <i class="fas fa-fingerprint"></i>
                            <h3>Unique Trek Packages</h3>
                            <p>Explore specially curated trekking packages for diverse preferences and adventures.</p>
                        </div>
                        <div class="offer-item">
                            <i class="fas fa-mountain"></i>
                            <h3>Trekking Bookings</h3>
                            <p>Seamlessly book, view, and edit trekking adventures for exclusive destinations.</p>
                        </div>
                        <div class="offer-item">
                            <i class="fas fa-handshake-angle"></i>
                            <h3>Guided Tours</h3>
                            <p>Expert-led guided tours for a safe and enriching adventure.</p>
                        </div>
                        <div class="offer-item">
                            <i class="fas fa-table"></i>
                            <h3>User Dashboard</h3>
                            <p>Manage your trekking bookings, preferences, and profile in one place.</p>
                        </div>
                        <div class="offer-item">
                            <i class="fas fa-phone"></i>
                            <h3>Contact Support</h3>
                            <p>Easy connection with the TrekSmart team for inquiries, support, or custom trekking plans.</p>
                        </div>
                        <div class="offer-item">
                            <i class="fas fa-money-bill"></i>
                            <h3>No Pre-Payment</h3>
                            <p>Easy onsite cash on delivery payment for hassle-free transactions.</p>
                        </div>
                        <div class="offer-item">
                            <i class="fas fa-square-rss"></i>
                            <h3>Blog Updates</h3>
                            <p>Explore detailed trekking guides, tips, and stories to inspire your journey.</p>
                        </div>
                        <div class="offer-item">
                            <i class="fas fa-star"></i>
                            <h3>Quality Service</h3>
                            <p>Enjoy top-notch services ensuring satisfaction and memorable trekking experiences.</p>
                        </div>
                    </div>
                </div>
            </section>
                    <!-- Our Partners Section -->
            <section class="our-partners">
                <div class="partner-container">
                    <h2>Our Affiliations</h2>
                    <p>Our trusted partners who help us deliver the best trekking experiences.</p>
                    <div class="partner-logos">
                        <img src="/TrekSmart/assets/partners/partner1.png" alt="Partner 1">
                        <img src="/TrekSmart/assets/partners/partner2.png" alt="Partner 2">
                        <img src="/TrekSmart/assets/partners/partner3.png" alt="Partner 3">
                        <img src="/TrekSmart/assets/partners/partner4.png" alt="Partner 4">
                        <img src="/TrekSmart/assets/partners/partner5.png" alt="Partner 5">
                        <img src="/TrekSmart/assets/partners/partner6.png" alt="Partner 6">
                    </div>
                </div>
            </section>

            <!-- Testimonials Section -->
            <section class="testimonials">
                <div class="testimonial-container">
                    <h2>Testimonials</h2>
                    <p class="test-capt">Read what our customers have to say about their trekking experiences.</p>
                    <div class="testimonial-items">
                        <div class="testimonial">
                            <img class="testimonial-avatar" src="/TrekSmart/assets/testimonials/avatar1.jpg" alt="Sujata">
                            <h3 class="testimonial-name">Sujata Hamal</h3>
                            <div class="testimonial-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                            <p class="testimonial-text">"Thanks to TrekSmart, my EBC trek was unforgettable. Their expert guides and seamless service made the journey effortless."</p>
                        </div>
                        <div class="testimonial">
                            <img class="testimonial-avatar" src="/TrekSmart/assets/testimonials/avatar2.jpg" alt="Tilak Dhakal">
                            <h3 class="testimonial-name">Tilak Dhakal</h3>
                            <div class="testimonial-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <p class="testimonial-text">"TrekSmart planned every detail for my adventure, ensuring a safe and exhilarating experience from start to finish."</p>
                        </div>
                        <div class="testimonial">
                            <img class="testimonial-avatar" src="/TrekSmart/assets/testimonials/avatar3.jpg" alt="Anish Bajracharya">
                            <h3 class="testimonial-name">Anish Bajracharya</h3>
                            <div class="testimonial-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                            </div>
                            <p class="testimonial-text">"With TrekSmart's innovative technology and dedicated support, I felt confident and well-prepared for every step of my trek."</p>
                        </div>
                        <div class="testimonial">
                            <img class="testimonial-avatar" src="/TrekSmart/assets/testimonials/avatar4.jpg" alt="Nikita Poudel">
                            <h3 class="testimonial-name">Nikita Poudel</h3>
                            <div class="testimonial-rating">
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star"></i>
                                <i class="fas fa-star-half-alt"></i>
                            </div>
                            <p class="testimonial-text">"Choosing TrekSmart was the best decision for my trek. Their personalized service and reliable technology ensured a smooth, worry-free adventure."</p>
                        </div>
                    </div>
                </div>
            </section>
        </section>
    </main>

    <!-- Include the Footer -->
    <?php include '../includes/footer.php'; ?>

    <!-- Link to the JavaScript file -->
    <script src="/TrekSmart/assets/JS/script.js"></script>
</body>
</html>
