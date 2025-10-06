<?php
session_start();
require_once 'config/database.php';

// Get the requested page
$page = $_GET['page'] ?? 'home';

// Get page content from database
function getPageContent($slug) {
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("SELECT * FROM pages WHERE slug = ? AND status = 'published'");
        $stmt->execute([$slug]);
        return $stmt->fetch();
    } catch (Exception $e) {
        return null;
    }
}

// Get all available buses
function getBuses() {
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("SELECT * FROM buses WHERE status = 'available' ORDER BY created_at DESC");
        $stmt->execute();
        return $stmt->fetchAll();
    } catch (Exception $e) {
        return [];
    }
}

$currentPage = getPageContent($page);
$buses = getBuses();

// If page not found, redirect to home
if (!$currentPage && $page !== 'home') {
    header('Location: index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $currentPage ? htmlspecialchars($currentPage['title']) : 'Annhurst Transport Service Limited'; ?></title>
    <meta name="description" content="<?php echo $currentPage ? htmlspecialchars($currentPage['meta_description'] ?? '') : 'Your trusted partner for bus higher purchase solutions'; ?>">
    <meta name="keywords" content="<?php echo $currentPage ? htmlspecialchars($currentPage['meta_keywords'] ?? '') : 'bus, transport, higher purchase, annhurst'; ?>">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" xintegrity="sha512-z3gLpd7yXhUqG20I6z+e57vM2A/tA8W/FhQz4aM2FpQv+P4H2rG8M9BfT4g/eF8n5mBf4P8GfRzF2fF5" crossorigin="anonymous" referrerpolicy="no-referrer" /> 

    <!-- Tabler Icons for Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">
  
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#b5121b',
                        'primary-dark': '#8f0e15',
                        'primary-light': '#d41a25'
                    }
                }
            }
        }
    </script>
    
    <!-- Custom CSS -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        .playfair-display {
            font-family: 'Playfair Display', serif;
        }        
        .hero-gradient {
            background: linear-gradient(135deg, #b5121b 0%, #8f0e15 100%);
        }
        .text-shadow {
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
        .icon-box {
            background-color: #d41a25;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Shimmer Animation CSS */
        .shimmer-effect {
            position: relative;
            overflow: hidden;
        }

        .shimmer-effect::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 50%;
            height: 100%;
            background: rgba(255, 255, 255, 0.1);
            transform: skewX(-20deg);
            animation: shimmer 3s infinite;
        }

        @keyframes shimmer {
            0% {
                left: -100%;
            }
            100% {
                left: 150%;
            }
        }
        
        /* Custom styles for the investment cards */
        .card-high-roi {
            background-color: #e6f4e6;
            border-color: #38a169;
        }
        .card-premium {
            background-color: #fde8e8;
            border-color: #e53e3e;
        }
        .card-expert {
            background-color: #e9eef7;
            border-color: #4a5568;
        }
        .card-high-roi .icon-box {
            background-color: #d1e7c5;
        }
        .card-premium .icon-box {
            background-color: #f4d3d3;
        }
        .card-expert .icon-box {
            background-color: #dbe2ef;
        }

        /* Testimonial Section Styles */
        .testimonials {
            position: relative;
            overflow: hidden;
        }
        .testimonials-bg {
            position: absolute;
            inset: 0;
            background: linear-gradient(135deg, #21202e 0%, #363544 100%);
            z-index: -2;
        }
        .testimonials-bg:before {
            content: "";
            position: absolute;
            inset: 0;
            background-image: radial-gradient(circle at 20% 80%, rgba(255, 255, 255, .1) 0%, transparent 50%), radial-gradient(circle at 80% 20%, rgba(181, 18, 27, .1) 0%, transparent 50%);
            z-index: -1;
        }
        .testimonials-overlay {
            position: absolute;
            inset: 0;
            background: #0000004d;
            z-index: -1;
        }
        .testimonial-carousel {
            position: relative;
            max-width: 900px;
            margin: 0 auto 4rem;
        }
        .testimonial-track {
            display: flex;
            transition: transform .5s ease;
        }
        .testimonial-card {
            flex: 0 0 100%;
            opacity: .6;
            transform: scale(.95);
            transition: all .5s ease;
            padding: 0 1rem;
        }
        .testimonial-card.active {
            opacity: 1;
            transform: scale(1);
        }
        .testimonial-content {
            background: #fff;
            border-radius: 12px;
            padding: 3rem 2rem;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            position: relative;
        }
        .quote-icon {
            position: absolute;
            top: -24px;
            left: 2rem;
            width: 48px;
            height: 48px;
            background: #d41a25;
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .testimonial-text {
            font-size: 1.25rem;
            line-height: 1.7;
            color: #333;
            margin-bottom: 2rem;
            font-style: italic;
        }
        .testimonial-author {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        .author-avatar {
            width: 60px;
            height: 60px;
            background: #d41a25;
            color: #fff;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 1.25rem;
            flex-shrink: 0;
        }
        .author-info {
            flex: 1;
        }
        .author-name {
            margin-bottom: .25rem;
            color: #333;
            font-size: 1.125rem;
        }
        .author-title {
            color: #666;
            font-size: .875rem;
            margin-bottom: .5rem;
        }
        .author-rating {
            display: flex;
            align-items: center;
            gap: .5rem;
        }
        .stars {
            display: flex;
            gap: 2px;
            color: gold;
        }
        .rating-text {
            font-size: .875rem;
            color: #666;
            font-weight: 500;
        }
        .carousel-nav {
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            transform: translateY(-50%);
            display: flex;
            justify-content: space-between;
            pointer-events: none;
            z-index: 2;
        }
        .nav-btn {
            width: 50px;
            height: 50px;
            background: rgba(255, 255, 255, 0.9);
            border: none;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all .3s ease;
            pointer-events: all;
            color: #333;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .nav-btn:hover {
            background: #fff;
            transform: scale(1.1);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
        }
        .nav-btn:disabled {
            opacity: .5;
            cursor: not-allowed;
        }
        .carousel-indicators {
            display: flex;
            justify-content: center;
            gap: .5rem;
            margin-top: 2rem;
        }
        .indicator {
            width: 12px;
            height: 12px;
            background: rgba(255, 255, 255, 0.4);
            border: none;
            border-radius: 50%;
            cursor: pointer;
            transition: all .3s ease;
        }
        .indicator.active {
            background: #fff;
            transform: scale(1.2);
        }
        .trust-signals {
            background: rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            padding: 2rem;
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        .trust-grid {
            align-items: center;
        }
        .trust-stat {
            display: flex;
            align-items: center;
            gap: 1rem;
            color: #fff;
        }
        .stat-icon {
            width: 60px;
            height: 60px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .stat-content h3 {
            margin-bottom: .5rem;
            color: #fff;
            font-size: 1.25rem;
        }
        .stat-content p {
            color: rgba(255, 255, 255, 0.8);
            font-size: .875rem;
            margin: 0;
            line-height: 1.4;
        }
        @media (max-width: 768px) {
            .testimonial-content {
                padding: 2rem 1.5rem;
            }
            .testimonial-text {
                font-size: 1.125rem;
            }
            .author-avatar {
                width: 50px;
                height: 50px;
                font-size: 1rem;
            }
            .carousel-nav {
                display: none;
            }
            .trust-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }
            .trust-stat {
                justify-content: center;
                text-align: center;
            }
            .trust-signals {
                padding: 1.5rem;
            }
        }
        @media (max-width: 480px) {
            .testimonial-card {
                padding: 0 .5rem;
            }
            .testimonial-content {
                padding: 1.5rem 1rem;
            }
            .testimonial-text {
                font-size: 1rem;
            }
            .testimonial-author {
                flex-direction: column;
                text-align: center;
            }
        }
    </style>
</head>
<body class="bg-gray-100 playfair-display">

<?php
  $current = $_GET['page'] ?? 'home';
?>
    <!-- Navigation -->
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <a href="index.php" class="flex items-center">
                            <img src="img/ann.png" alt="Annhurst Transport" class="h-10 w-auto">
                        </a>
                    </div>
                </div>
                
                <div class="hidden md:flex items-center space-x-8">
                    <a href="index.php" class="px-3 py-2 rounded-md text-sm font-medium transition-colors <?= $current === 'home' ? 'text-primary' : 'text-gray-800 hover:text-primary' ?>">Home</a>
                    <a href="index.php?page=about" class="px-3 py-2 rounded-md text-sm font-medium transition-colors <?= $current === 'about' ? 'text-primary' : 'text-gray-800 hover:text-primary' ?>">About</a>
                    <a href="index.php?page=buses" class="px-3 py-2 rounded-md text-sm font-medium transition-colors <?= $current === 'buses' ? 'text-primary' : 'text-gray-800 hover:text-primary' ?>">Our Buses</a>
                    <a href="index.php?page=contact" class="px-3 py-2 rounded-md text-sm font-medium transition-colors <?= $current === 'contact' ? 'text-primary' : 'text-gray-800 hover:text-primary' ?>">Contact</a>
                    <a href="index.php?page=contact" class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">Get Started</a>
                </div>
                
                <!-- Mobile menu button -->
                <div class="md:hidden flex items-center">
                    <button class="mobile-menu-button text-gray-700 hover:text-primary">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Mobile menu -->
        <div class="mobile-menu hidden md:hidden">
            <div class="px-2 pt-2 pb-3 space-y-1 sm:px-3">
                <a href="index.php" class="text-gray-700 hover:text-primary block px-3 py-2 rounded-md text-base font-medium">Home</a>
                <a href="index.php?page=about" class="text-gray-700 hover:text-primary block px-3 py-2 rounded-md text-base font-medium">About</a>
                <a href="index.php?page=buses" class="text-gray-700 hover:text-primary block px-3 py-2 rounded-md text-base font-medium">Our Buses</a>
                <a href="index.php?page=contact" class="text-gray-700 hover:text-primary block px-3 py-2 rounded-md text-base font-medium">Contact</a>
                <a href="index.php?page=contact" class="bg-primary hover:bg-primary-dark text-white block px-3 py-2 rounded-md text-base font-medium">Get Started</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        <?php if ($page === 'home'): ?>
            <!-- Hero Section -->
                <section class="bg-gradient-to-r from-gray-100 to-red-50 text-gray-900 py-20">
                    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-12">
                        <div class="flex-1 text-left md:pr-12">
                            <h1 class="text-4xl md:text-6xl font-bold mb-6 text-shadow leading-tight">
                                Your Global Solutions Provider<br>
                                with <span class="text-primary-light">Unmatched Expertise</span>
                            </h1>
                            <p class="text-lg md:text-xl mb-8 text-shadow opacity-90 text-gray-800">
                                Annhurst Global Services Limited is Nigeria's leading provider of investment opportunities and management services. We specialize in transportation, property investment, and tailored business solutions that deliver exceptional returns.
                            </p>
                            <div class="flex flex-col sm:flex-row gap-4 mb-10">
                                <a href="index.php?page=buses" class="bg-primary hover:bg-primary-dark text-white px-8 py-3 rounded-lg font-semibold transition-colors flex items-center justify-center">
                                    Explore Investments <span class="ml-2">&rarr;</span>
                                </a>
                                <a href="index.php?page=contact" class="border-2 border-primary text-primary px-8 py-3 rounded-lg font-semibold hover:bg-primary hover:text-white transition-colors flex items-center justify-center">
                                    Contact Us <span class="ml-2">&#128222;</span>
                                </a>
                            </div>
                            <div class="flex flex-wrap gap-8 mt-6">
                                <div class="text-center">
                                    <span class="text-3xl font-bold text-primary-light">4+</span>
                                    <div class="text-gray-100 mt-2">Years of Excellence</div>
                                </div>
                                <div class="text-center">
                                    <span class="text-3xl font-bold text-primary-light">100%</span>
                                    <div class="text-gray-100 mt-2">On-Time Payments</div>
                                </div>
                                <div class="text-center">
                                    <span class="text-3xl font-bold text-primary-light">24/7</span>
                                    <div class="text-gray-100 mt-2">Customer Support</div>
                                </div>
                            </div>
                        </div>
                        <div class="flex-1 flex flex-col gap-8 items-center justify-center">
                            <div class="bg-white shadow-lg rounded-xl p-6 w-80 mb-6">
                                <div class="flex items-center mb-2">
                                    <span class="bg-primary-light rounded-full p-2 mr-3">
                                        <svg class="w-6 h-6 text-white"  xmlns="http://www.w3.org/2000/svg"  
                                            width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-star">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 17.75l-6.172 3.245l1.179 -6.873l-5 -4.867l6.9 -1l3.086 -6.253l3.086 6.253l6.9 1l-5 4.867l1.179 6.873z" />
                                        </svg>
                                    </span>
                                    <span class="font-semibold text-gray-900 text-lg">Investment Success</span>
                                </div>
                                <div class="flex justify-between text-sm mb-2">
                                    <span class="text-primary font-bold">High Returns</span>
                                    <span class="text-gray-500">Bus Investment ROI</span>
                                </div>
                                <div class="w-full h-2 bg-gray-200 rounded-full">
                                    <div class="h-2 bg-primary rounded-full" style="width: 85%"></div>
                                </div>
                            </div>
                            <div class="bg-white shadow-lg rounded-xl p-6 w-80 flex flex-col items-center">
                                <div class="flex items-center mb-2">
                                    <span class="bg-primary-light rounded-full p-2 mr-3">
                                        <svg class="w-6 h-6 text-white"  xmlns="http://www.w3.org/2000/svg"
                                          width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-user-star">
                                          <path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h.5" />
                                          <path d="M17.8 20.817l-2.172 1.138a.392 .392 0 0 1 -.568 -.41l.415 -2.411l-1.757 -1.707a.389 .389 0 0 1 .217 -.665l2.428 -.352l1.086 -2.193a.392 .392 0 0 1 .702 0l1.086 2.193l2.428 .352a.39 .39 0 0 1 .217 .665l-1.757 1.707l.414 2.41a.39 .39 0 0 1 -.567 .411l-2.172 -1.138z" />
                                        </svg>
                                    </span>
                                    <span class="font-semibold text-gray-900 text-lg">Customer Satisfaction</span>
                                </div>
                                <span class="text-3xl font-bold text-primary-light">98%</span>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-16">
                        <div class="w-1 h-8 bg-primary-light mx-auto mt-2 rounded-full"></div>
                        <span class="text-gray-500 text-lg">Scroll to explore</span>
                    </div>
                </section>

            <!-- What we Offer -->
              <section class="flex flex-col items-center py-16 px-4 sm:px-6 lg:px-8">
                <section class="max-w-4xl text-center mb-16">
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">What We Offer</h1>
                    <p class="text-sm sm:text-base text-gray-600 max-w-2xl mx-auto">
                        Annhurst Global Services Limited is a leader in solutions delivery in Nigeria. We provide solutions in industries ranging from transportation to property and land investment, with a highly skilled professional management team.
                    </p>
                </section>

                <!-- Cards Section -->
                <section class="max-w-6xl grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">

                    <!-- Card 1: Customer Services -->
                    <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-200 text-center transform transition duration-300 ease-in-out hover:scale-105 hover:shadow-2xl hover:border-2 hover:border-red-600">
                        <div class="icon-box mx-auto mb-6 group">
                            <svg class="w-6 h-6 text-white transition-transform duration-300 ease-in-out group-hover:rotate-12" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" data-astro-cid-g5jplrhu=""> <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" data-astro-cid-g5jplrhu=""></path> <circle cx="9" cy="7" r="4" data-astro-cid-g5jplrhu=""></circle> <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75" data-astro-cid-g5jplrhu=""></path> </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800 mb-2">Customer Services</h2>
                        <p class="text-sm text-gray-500 mb-6">
                            Our key goal is the achievement of our customers' satisfaction. Our customer service department will stay in contact with you to ensure premium quality service and support throughout your investment journey.
                        </p>
                        <ul class="text-sm text-left text-gray-600 space-y-2">
                            <li class="flex items-center">
                                <i class="ti ti-circle-check text-red-500 mr-2"></i>
                                <span>24/7 Support</span>
                            </li>
                            <li class="flex items-center">
                                <i class="ti ti-circle-check text-red-500 mr-2"></i>
                                <span>Dedicated Account Manager</span>
                            </li>
                            <li class="flex items-center">
                                <i class="ti ti-circle-check text-red-500 mr-2"></i>
                                <span>Regular Updates</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Card 2: Tailored Solutions (Most Popular) -->
                    <div class="relative bg-white p-8 rounded-2xl shadow-2xl border-2 border-red-600 text-center transform scale-105">
                        <div class="absolute -top-3 left-1/2 transform -translate-x-1/2 bg-red-600 text-white text-xs font-bold px-4 py-1 rounded-full shadow-lg">
                            Most Popular
                        </div>
                        <div class="icon-box mx-auto mb-6 group">
                            <svg class="w-6 h-6 text-white transition-transform duration-300 ease-in-out group-hover:rotate-12" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" data-astro-cid-g5jplrhu=""> <circle cx="12" cy="12" r="3" data-astro-cid-g5jplrhu=""></circle> <path d="M19.4 15a1.65 1.65 0 00.33 1.82l.06.06a2 2 0 010 2.83 2 2 0 01-2.83 0l-.06-.06a1.65 1.65 0 00-1.82-.33 1.65 1.65 0 00-1 1.51V21a2 2 0 01-2 2 2 2 0 01-2-2v-.09A1.65 1.65 0 009 19.4a1.65 1.65 0 00-1.82.33l-.06.06a2 2 0 01-2.83 0 2 2 0 010-2.83l.06-.06a1.65 1.65 0 00.33-1.82 1.65 1.65 0 00-1.51-1H3a2 2 0 01-2-2 2 2 0 012-2h.09A1.65 1.65 0 004.6 9a1.65 1.65 0 00-.33-1.82l-.06-.06a2 2 0 010-2.83 2 2 0 012.83 0l.06.06a1.65 1.65 0 001.82.33H9a1.65 1.65 0 001-1.51V3a2 2 0 012-2 2 2 0 012 2v.09a1.65 1.65 0 001 1.51 1.65 1.65 0 001.82-.33l.06-.06a2 2 0 012.83 0 2 2 0 010 2.83l-.06.06a1.65 1.65 0 00-.33 1.82V9a1.65 1.65 0 001.51 1H21a2 2 0 012 2 2 2 0 01-2 2h-.09a1.65 1.65 0 00-1.51 1z" data-astro-cid-g5jplrhu=""></path> </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800 mb-2">Tailored Solutions</h2>
                        <p class="text-sm text-gray-500 mb-6">
                            Find out how we can support you in tackling your business or investment challenges in the most effective way. Our custom solutions are designed to maximize your returns and minimize risk.
                        </p>
                        <ul class="text-sm text-left text-gray-600 space-y-2">
                            <li class="flex items-center">
                                <i class="ti ti-circle-check text-red-500 mr-2"></i>
                                <span>Custom Investment Plans</span>
                            </li>
                            <li class="flex items-center">
                                <i class="ti ti-circle-check text-red-500 mr-2"></i>
                                <span>Risk Assessment</span>
                            </li>
                            <li class="flex items-center">
                                <i class="ti ti-circle-check text-red-500 mr-2"></i>
                                <span>Portfolio Optimization</span>
                            </li>
                        </ul>
                    </div>

                    <!-- Card 3: Collaborate with Us -->
                    <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-200 text-center transform transition duration-300 ease-in-out hover:scale-105 hover:shadow-2xl hover:border-2 hover:border-red-600">
                        <div class="icon-box mx-auto mb-6 group">
                            <svg class="w-6 h-6 text-white transition-transform duration-300 ease-in-out group-hover:rotate-12" width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" data-astro-cid-g5jplrhu=""> <path d="M20 7h-9" data-astro-cid-g5jplrhu=""></path> <path d="M14 17H5" data-astro-cid-g5jplrhu=""></path> <circle cx="17" cy="17" r="3" data-astro-cid-g5jplrhu=""></circle> <circle cx="7" cy="7" r="3" data-astro-cid-g5jplrhu=""></circle> </svg>
                        </div>
                        <h2 class="text-xl font-semibold text-gray-800 mb-2">Collaborate with Us</h2>
                        <p class="text-sm text-gray-500 mb-6">
                            Engage with our experienced team for a fresh perspective and creative solutions. Benefit from our collective expertise and network to accelerate your business growth and investment success.
                        </p>
                        <ul class="text-sm text-left text-gray-600 space-y-2">
                            <li class="flex items-center">
                                <i class="ti ti-circle-check text-red-500 mr-2"></i>
                                <span>Expert Consultation</span>
                            </li>
                            <li class="flex items-center">
                                <i class="ti ti-circle-check text-red-500 mr-2"></i>
                                <span>Strategic Planning</span>
                            </li>
                            <li class="flex items-center">
                                <i class="ti ti-circle-check text-red-500 mr-2"></i>
                                <span>Network Access</span>
                            </li>
                        </ul>
                    </div>
                </section>

                <!-- Call to Action Section with Shimmer Animation -->
                <section class="max-w-6xl w-full bg-gray-900 rounded-2xl p-8 sm:p-12 text-center shadow-lg shimmer-effect">
                    <h2 class="text-2xl sm:text-3xl font-bold text-white mb-4">Ready to get started?</h2>
                    <p class="text-sm sm:text-base text-gray-300 mb-6">
                        Join hundreds of satisfied investors who trust Annhurst GSL with their investments.
                    </p>
                    <button class="bg-white text-gray-900 font-semibold py-3 px-8 rounded-full shadow-lg hover:bg-gray-200 transition duration-300">
                        Start Your Journey
                    </button>
                </section>

                <!-- Investment Opportunities Section -->
                <section class="max-w-6xl w-full py-16 px-4 sm:px-6 lg:px-8 text-center">
                    <h2 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4">Investment Opportunities</h2>
                    <p class="text-sm sm:text-base text-gray-600 max-w-2xl mx-auto mb-12">
                        Discover how you can make your money work harder for you with our proven investment opportunities. Join our community of successful investors who enjoy consistent returns.
                    </p>

                    <!-- Investment Cards -->
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                        <!-- Card 1: Bus Investment Program -->
                        <div class="bg-white p-8 rounded-2xl shadow-lg border-2 card-high-roi text-left transform transition duration-300 ease-in-out hover:scale-105 hover:shadow-2xl">
                            <div class="flex items-center justify-between mb-4">
                                <span class="bg-green-600 text-white text-xs font-bold px-4 py-1 rounded-full shadow-lg">High ROI</span>
                                <div class="icon-box">
                                    <svg class="w-10 h-10 text-green-600" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-astro-cid-mj52klvg=""> <path d="M8 6v6M16 6v6M6 21h12a2 2 0 002-2v-7a2 2 0 00-2-2H6a2 2 0 00-2 2v7a2 2 0 002 2z" data-astro-cid-mj52klvg=""></path> <path d="M6 8V6a2 2 0 012-2h8a2 2 0 012 2v2" data-astro-cid-mj52klvg=""></path> <circle cx="8" cy="18" r="1" data-astro-cid-mj52klvg=""></circle> <circle cx="16" cy="18" r="1" data-astro-cid-mj52klvg=""></circle> </svg>
                                </div>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">Bus Investment Program</h3>
                            <p class="text-sm text-gray-600 mb-6">
                                Get a high return on your investment through our proven bus purchasing scheme. Join our fleet and earn consistent monthly returns from Nigeria's growing transportation sector.
                            </p>
                            <div class="grid grid-cols-2 gap-4 text-sm text-gray-600 mb-6">
                                <div>
                                    <p class="font-medium">Investment Period:</p>
                                    <p>3-5 years</p>
                                </div>
                                <div>
                                    <p class="font-medium">Expected Returns:</p>
                                    <p>Competitive ROI</p>
                                </div>
                                <div>
                                    <p class="font-medium">Payment Schedule:</p>
                                    <p>Monthly</p>
                                </div>
                            </div>
                            <ul class="text-sm text-gray-600 space-y-2 mb-8">
                                <li class="flex items-center">
                                    <i class="ti ti-circle-check text-green-600 mr-2"></i>
                                    <span>Guaranteed monthly payments</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="ti ti-circle-check text-green-600 mr-2"></i>
                                    <span>Full maintenance included</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="ti ti-circle-check text-green-600 mr-2"></i>
                                    <span>Insurance coverage</span>
                                </li>
                            </ul>
                            <button class="w-full text-red-600 border-2 border-red-600 font-semibold py-3 px-8 rounded-full hover:bg-red-50 transition duration-300">
                                Learn More
                            </button>
                        </div>

                        <!-- Card 2: Property & Land Investment -->
                        <div class="bg-white p-8 rounded-2xl shadow-lg border-2 card-premium text-left transform transition duration-300 ease-in-out hover:scale-105 hover:shadow-2xl">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-2">
                                    <span class="bg-red-600 text-white text-xs font-bold px-4 py-1 rounded-full shadow-lg">Premium</span>
                                    <span class="bg-gray-600 text-white text-xs font-bold px-4 py-1 rounded-full shadow-lg">Recommended</span>
                                </div>
                                <div class="icon-box">
                                    <svg class="w-10 h-10 text-red-600" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-astro-cid-mj52klvg=""> <path d="M3 21h18M5 21V7l8-4v18M19 21V11l-6-3" data-astro-cid-mj52klvg=""></path> <path d="M9 9v.01M9 12v.01M9 15v.01M9 18v.01" data-astro-cid-mj52klvg=""></path> </svg>
                                </div>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">Property & Land Investment</h3>
                            <p class="text-sm text-gray-600 mb-6">
                                Become a shared land owner and receive passive income from some of the most sought-after areas in Lagos. Benefit from Nigeria's booming real estate market.
                            </p>
                            <div class="grid grid-cols-2 gap-4 text-sm text-gray-600 mb-6">
                                <div>
                                    <p class="font-medium">Investment Period:</p>
                                    <p>5-10 years</p>
                                </div>
                                <div>
                                    <p class="font-medium">Expected Returns:</p>
                                    <p>Capital appreciation + Income</p>
                                </div>
                                <div>
                                    <p class="font-medium">Payment Schedule:</p>
                                    <p>Quarterly</p>
                                </div>
                            </div>
                            <ul class="text-sm text-gray-600 space-y-2 mb-8">
                                <li class="flex items-center">
                                    <i class="ti ti-circle-check text-red-600 mr-2"></i>
                                    <span>Prime Lagos locations</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="ti ti-circle-check text-red-600 mr-2"></i>
                                    <span>Capital appreciation</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="ti ti-circle-check text-red-600 mr-2"></i>
                                    <span>Passive income stream</span>
                                </li>
                            </ul>
                            <button class="w-full bg-red-600 text-white font-semibold py-3 px-8 rounded-full shadow-lg hover:bg-red-700 transition duration-300">
                                Get Started
                            </button>
                        </div>

                        <!-- Card 3: Business Expansion -->
                        <div class="bg-white p-8 rounded-2xl shadow-lg border-2 card-expert text-left transform transition duration-300 ease-in-out hover:scale-105 hover:shadow-2xl">
                            <div class="flex items-center justify-between mb-4">
                                <span class="bg-gray-600 text-white text-xs font-bold px-4 py-1 rounded-full shadow-lg">Expert Led</span>
                                <div class="icon-box">
                                    <svg class="w-10 h-10 text-gray-600" width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" data-astro-cid-mj52klvg=""> <path d="M16 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2" data-astro-cid-mj52klvg=""></path> <circle cx="8.5" cy="7" r="4" data-astro-cid-mj52klvg=""></circle> <path d="M20 8v6M23 11l-3 3-3-3" data-astro-cid-mj52klvg=""></path> <path d="M17 4h4v4" data-astro-cid-mj52klvg=""></path> </svg>
                                </div>
                            </div>
                            <h3 class="text-xl font-semibold text-gray-800 mb-2">Business Expansion</h3>
                            <p class="text-sm text-gray-600 mb-6">
                                Our highly skilled professional management team will assist you to manage your investment and increase your passive income with strategic business expansion opportunities.
                            </p>
                            <div class="grid grid-cols-2 gap-4 text-sm text-gray-600 mb-6">
                                <div>
                                    <p class="font-medium">Investment Period:</p>
                                    <p>Flexible</p>
                                </div>
                                <div>
                                    <p class="font-medium">Expected Returns:</p>
                                    <p>Variable based on sector</p>
                                </div>
                                <div>
                                    <p class="font-medium">Management:</p>
                                    <p>Full service</p>
                                </div>
                            </div>
                            <ul class="text-sm text-gray-600 space-y-2 mb-8">
                                <li class="flex items-center">
                                    <i class="ti ti-circle-check text-gray-600 mr-2"></i>
                                    <span>Expert management</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="ti ti-circle-check text-gray-600 mr-2"></i>
                                    <span>Diversified portfolio</span>
                                </li>
                                <li class="flex items-center">
                                    <i class="ti ti-circle-check text-gray-600 mr-2"></i>
                                    <span>Strategic partnerships</span>
                                </li>
                            </ul>
                            <button class="w-full text-gray-600 border-2 border-gray-600 font-semibold py-3 px-8 rounded-full hover:bg-gray-50 transition duration-300">
                                Explore Options
                            </button>
                        </div>
                    </div>
                </section>

                <!-- Testimonial Section -->
                <section class="testimonials w-full py-16 px-4 sm:px-6 lg:px-8">
                    <div class="testimonials-bg">
                        <div class="testimonials-overlay"></div>
                    </div>
                    <div class="container mx-auto">
                        <div class="max-w-xl mx-auto text-center mb-16">
                            <h2 class="text-3xl sm:text-4xl font-bold text-white mb-4">What Our Investors Say</h2>
                            <p class="text-sm sm:text-base text-white opacity-80">
                                Don't just take our word for it. Here's what our satisfied investors have to say about their experience with Annhurst GSL.
                            </p>
                        </div>

                        <div class="testimonial-carousel">
                            <div class="testimonial-track" id="testimonial-track">
                                <!-- Testimonial 1 -->
                                <div class="testimonial-card">
                                    <div class="testimonial-content">
                                        <div class="quote-icon">
                                            <svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-10zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h4v10h-10z"></path>
                                            </svg>
                                        </div>
                                        <p class="testimonial-text">
                                            "In the 4 years that I have been an investor at Annhurst, I have invested in 3 buses and I get paid my returns promptly! Despite the slowdown during COVID, Annhurst did not miss a single payment. I will definitely be investing in more buses!"
                                        </p>
                                        <div class="testimonial-author">
                                            <div class="author-avatar">
                                                <span>SO</span>
                                            </div>
                                            <div class="author-info">
                                                <h4 class="author-name">Somto O.</h4>
                                                <p class="author-title">Bus Investment Program Investor</p>
                                                <div class="author-rating">
                                                    <div class="stars">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
                                                    </div>
                                                    <span class="rating-text">5.0 out of 5</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Testimonial 2 -->
                                <div class="testimonial-card">
                                    <div class="testimonial-content">
                                        <div class="quote-icon">
                                            <svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-10zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h4v10h-10z"></path>
                                            </svg>
                                        </div>
                                        <p class="testimonial-text">
                                            "The property investment opportunities through Annhurst have exceeded my expectations. The team's expertise in the Lagos real estate market is evident in the quality of investments they recommend. I'm now earning passive income from prime locations!"
                                        </p>
                                        <div class="testimonial-author">
                                            <div class="author-avatar">
                                                <span>AA</span>
                                            </div>
                                            <div class="author-info">
                                                <h4 class="author-name">Adunni A.</h4>
                                                <p class="author-title">Property Investment Participant</p>
                                                <div class="author-rating">
                                                    <div class="stars">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
                                                    </div>
                                                    <span class="rating-text">5.0 out of 5</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Testimonial 3 -->
                                <div class="testimonial-card">
                                    <div class="testimonial-content">
                                        <div class="quote-icon">
                                            <svg width="48" height="48" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-10zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h4v10h-10z"></path>
                                            </svg>
                                        </div>
                                        <p class="testimonial-text">
                                            "What sets Annhurst apart is their transparency and professionalism. They provide regular updates, clear communication, and most importantly, deliver on their promises. The customer service is exceptional and they truly care about investor success."
                                        </p>
                                        <div class="testimonial-author">
                                            <div class="author-avatar">
                                                <span>CU</span>
                                            </div>
                                            <div class="author-info">
                                                <h4 class="author-name">Chidi U.</h4>
                                                <p class="author-title">Business Expansion Partner</p>
                                                <div class="author-rating">
                                                    <div class="stars">
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
                                                        <svg width="16" height="16" viewBox="0 0 24 24" fill="currentColor"><path d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"></path></svg>
                                                    </div>
                                                    <span class="rating-text">5.0 out of 5</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <!-- Navigation buttons -->
                            <div class="carousel-nav">
                                <button class="nav-btn prev-btn" id="prev-btn" aria-label="Previous testimonial">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M15 18l-6-6 6-6"></path>
                                    </svg>
                                </button>
                                <button class="nav-btn next-btn" id="next-btn" aria-label="Next testimonial">
                                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                        <path d="M9 18l6-6-6-6"></path>
                                    </svg>
                                </button>
                            </div>
                            <!-- Indicators -->
                            <div class="carousel-indicators">
                                <button class="indicator" data-index="0"></button>
                                <button class="indicator active" data-index="1"></button>
                                <button class="indicator" data-index="2"></button>
                            </div>
                        </div>

                        <!-- Trust signals -->
                        <div class="trust-signals mx-auto max-w-4xl">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                                <div class="trust-stat">
                                    <div class="stat-icon">
                                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                            <path d="M9 12l2 2 4-4"></path>
                                        </svg>
                                    </div>
                                    <div class="stat-content">
                                        <h3>100% Payment Reliability</h3>
                                        <p>Not a single payment missed, even during challenging times like COVID-19</p>
                                    </div>
                                </div>
                                <div class="trust-stat">
                                    <div class="stat-icon">
                                        <svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                                            <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"></path>
                                            <circle cx="9" cy="7" r="4"></circle>
                                            <path d="M23 21v-2a4 4 0 00-3-3.87M16 3.13a4 4 0 010 7.75"></path>
                                        </svg>
                                    </div>
                                    <div class="stat-content">
                                        <h3>500+ Satisfied Investors</h3>
                                        <p>Join a growing community of investors who trust us with their financial future</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>

                <!-- Other Sections -->
                <section class="max-w-6xl w-full grid grid-cols-1 md:grid-cols-2 gap-8 py-16 px-4 sm:px-6 lg:px-8">
                    <!-- Why Choose Section -->
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Why Choose Our Investment Programs?</h2>
                        <p class="text-gray-600 mb-6">
                            We have a proven track record of delivering consistent returns to our investors. Our approach carefully manages every aspect of your investment to ensure optimal performance.
                        </p>
                        <div class="flex justify-between items-center text-center">
                            <div>
                                <span class="text-3xl font-bold text-gray-900">4+</span>
                                <p class="text-sm text-gray-500">Years of Excellence</p>
                            </div>
                            <div>
                                <span class="text-3xl font-bold text-gray-900">100%</span>
                                <p class="text-sm text-gray-500">On-Time Payments</p>
                            </div>
                            <div>
                                <span class="text-3xl font-bold text-gray-900">50+</span>
                                <p class="text-sm text-gray-500">Satisfied Investors</p>
                            </div>
                        </div>
                    </div>

                    <!-- Investment Process Section -->
                    <div>
                        <h2 class="text-2xl font-semibold text-gray-900 mb-4">Investment Process</h2>
                        <ul class="space-y-4">
                            <li class="flex items-start space-x-4">
                                <div class="w-8 h-8 flex items-center justify-center bg-red-600 text-white rounded-full font-bold flex-shrink-0">1</div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">Consultation</h3>
                                    <p class="text-sm text-gray-600">Discuss your investment goals with our experts</p>
                                </div>
                            </li>
                            <li class="flex items-start space-x-4">
                                <div class="w-8 h-8 flex items-center justify-center bg-red-600 text-white rounded-full font-bold flex-shrink-0">2</div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">Selection</h3>
                                    <p class="text-sm text-gray-600">Choose the investment option that fits your profile</p>
                                </div>
                            </li>
                            <li class="flex items-start space-x-4">
                                <div class="w-8 h-8 flex items-center justify-center bg-red-600 text-white rounded-full font-bold flex-shrink-0">3</div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">Investment</h3>
                                    <p class="text-sm text-gray-600">Complete the investment process with our guidance</p>
                                </div>
                            </li>
                            <li class="flex items-start space-x-4">
                                <div class="w-8 h-8 flex items-center justify-center bg-red-600 text-white rounded-full font-bold flex-shrink-0">4</div>
                                <div>
                                    <h3 class="font-semibold text-gray-900">Returns</h3>
                                    <p class="text-sm text-gray-600">Start receiving your scheduled returns</p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </section>                              
            </section>  

            <!-- Features Section -->
            <section class="py-16 bg-white">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Why Choose Annhurst Transport?</h2>
                    <div class="grid md:grid-cols-3 gap-8">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold mb-2">Quality Assurance</h3>
                            <p class="text-gray-600">All our buses undergo rigorous quality checks to ensure reliability and safety.</p>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold mb-2">Flexible Financing</h3>
                            <p class="text-gray-600">Competitive higher purchase terms that work for your business needs.</p>
                        </div>
                        <div class="text-center">
                            <div class="w-16 h-16 bg-primary rounded-full flex items-center justify-center mx-auto mb-4">
                                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192L5.636 18.364M12 2.25a9.75 9.75 0 100 19.5 9.75 9.75 0 000-19.5z"></path>
                                </svg>
                            </div>
                            <h3 class="text-xl font-semibold mb-2">24/7 Support</h3>
                            <p class="text-gray-600">Round-the-clock customer support to assist you with any questions or concerns.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Contact Section -->
            <?php include('use/contact.php'); ?>            

            <!-- Featured Buses Section -->
            <section class="py-16 bg-gray-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h2 class="text-3xl font-bold text-center text-gray-900 mb-12">Featured Buses</h2>
                    <div class="grid md:grid-cols-3 gap-8">
                        <?php foreach (array_slice($buses, 0, 3) as $bus): ?>
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                            <div class="h-48 bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-500 text-lg">Bus Image</span>
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-semibold mb-2"><?php echo htmlspecialchars($bus['bus_name']); ?></h3>
                                <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($bus['description']); ?></p>
                                <div class="flex justify-between items-center">
                                    <span class="text-2xl font-bold text-primary">₦<?php echo number_format($bus['price']); ?></span>
                                    <a href="index.php?page=buses" class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg transition-colors">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="text-center mt-8">
                        <a href="index.php?page=buses" class="bg-primary hover:bg-primary-dark text-white px-8 py-3 rounded-lg font-semibold transition-colors">
                            View All Buses
                        </a>
                    </div>
                </div>
            </section>

        <?php elseif ($page === 'buses'): ?>
            <!-- Buses Page -->
            <section class="py-16 bg-gray-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h1 class="text-4xl font-bold text-center text-gray-900 mb-12">Available Buses</h1>
                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <?php foreach ($buses as $bus): ?>
                        <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition-shadow">
                            <div class="h-48 bg-gray-200 flex items-center justify-center">
                                <span class="text-gray-500 text-lg">Bus Image</span>
                            </div>
                            <div class="p-6">
                                <h3 class="text-xl font-semibold mb-2"><?php echo htmlspecialchars($bus['bus_name']); ?></h3>
                                <p class="text-gray-600 mb-2"><strong>Model:</strong> <?php echo htmlspecialchars($bus['model']); ?></p>
                                <p class="text-gray-600 mb-2"><strong>Year:</strong> <?php echo htmlspecialchars($bus['year']); ?></p>
                                <p class="text-gray-600 mb-2"><strong>Capacity:</strong> <?php echo htmlspecialchars($bus['capacity']); ?> passengers</p>
                                <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($bus['description']); ?></p>
                                <div class="mb-4">
                                    <h4 class="font-semibold mb-2">Features:</h4>
                                    <p class="text-gray-600 text-sm"><?php echo htmlspecialchars($bus['features']); ?></p>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-2xl font-bold text-primary">₦<?php echo number_format($bus['price']); ?></span>
                                    <a href="index.php?page=contact" class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-lg transition-colors">
                                        Inquire Now
                                    </a>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </section>

<?php elseif ($page === 'about'): ?>
            <!-- About Page -->
            <?php include('use/about.php'); ?>

        <?php elseif ($page === 'contact'): ?>
            <!-- Contact Page -->
            <?php include('use/contact.php'); ?>

        <?php else: ?>
            <!-- Generic Page Content -->
            <section class="py-16 bg-gray-50">
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h1 class="text-4xl font-bold text-center text-gray-900 mb-8"><?php echo htmlspecialchars($currentPage['title']); ?></h1>
                    <div class="bg-white rounded-lg shadow-lg p-8">
                        <div class="prose max-w-none">
                            <?php echo $currentPage['content']; ?>
                        </div>
                    </div>
                </div>
            </section>
        <?php endif; ?>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-900 text-gray-300 pt-16 md:pt-24 pb-8">
        <div class="container mx-auto px-4 md:px-8 max-w-7xl">

            <!-- Main Footer Section -->
            <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-12 lg:gap-16 pb-12 md:pb-16 lg:pb-24 border-b border-gray-700">

                <!-- Company Info -->
                <div class="space-y-6">
                    <div class="flex items-center space-x-2">
                        <!-- Placeholder for the logo from the image -->
                        <div class="flex-shrink-0">
                            <img src="img/logo.png" alt="Annhurst Transport" class="h-10 w-auto">
                        </div>
                        <div>
                            <h2 class="text-white text-2xl font-bold tracking-tight">ANNHURST</h2>
                            <p class="text-sm font-light text-gray-400">Global Services Ltd</p>
                        </div>
                    </div>
                    <p class="text-sm font-medium text-gray-400">Your Global Solutions Provider</p>
                    <p class="text-xs leading-relaxed text-gray-400">
                        Leading provider of investment opportunities and management services in Nigeria. We specialize in transportation, property investment, and tailored business solutions that deliver exceptional returns.
                    </p>
                    <div class="flex space-x-4">
                        <a href="https://www.facebook.com/Annhurst-Global-Services-Ltd-100954948649293/" class="text-gray-400 hover:text-white transition-colors duration-200">
                            <svg class="text-lg"  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="currentColor"  class="icon icon-tabler icons-tabler-filled icon-tabler-brand-facebook"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M18 2a1 1 0 0 1 .993 .883l.007 .117v4a1 1 0 0 1 -.883 .993l-.117 .007h-3v1h3a1 1 0 0 1 .991 1.131l-.02 .112l-1 4a1 1 0 0 1 -.858 .75l-.113 .007h-2v6a1 1 0 0 1 -.883 .993l-.117 .007h-4a1 1 0 0 1 -.993 -.883l-.007 -.117v-6h-2a1 1 0 0 1 -.993 -.883l-.007 -.117v-4a1 1 0 0 1 .883 -.993l.117 -.007h2v-1a6 6 0 0 1 5.775 -5.996l.225 -.004h3z" /></svg>
                        </a>
                        <a href="https://www.instagram.com/annhurst_gsl" class="text-gray-400 hover:text-white transition-colors duration-200">
                            <svg class="text-lg"  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-brand-instagram"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M4 8a4 4 0 0 1 4 -4h8a4 4 0 0 1 4 4v8a4 4 0 0 1 -4 4h-8a4 4 0 0 1 -4 -4z" /><path d="M9 12a3 3 0 1 0 6 0a3 3 0 0 0 -6 0" /><path d="M16.5 7.5v.01" /></svg>
                        </a>
                    </div>
                </div>

                <!-- Quick Links -->
                <div class="md:col-span-1 space-y-4">
                    <h3 class="text-white font-semibold text-lg mb-4">Quick Links</h3>
                    <ul class="space-y-3">
                        <li><a href="index.php" class="text-sm text-gray-400 hover:text-white transition-colors duration-200">Home</a></li>
                        <li><a href="index.php?page=buses" class="text-sm text-gray-400 hover:text-white transition-colors duration-200">Our Services</a></li>
                        <li><a href="index.php?page=about" class="text-sm text-gray-400 hover:text-white transition-colors duration-200">Investment Opportunities</a></li>
                        <li><a href="index.php?page=about" class="text-sm text-gray-400 hover:text-white transition-colors duration-200">About Us</a></li>
                        <li><a href="index.php?page=contact" class="text-sm text-gray-400 hover:text-white transition-colors duration-200">Contact Us</a></li>
                    </ul>
                </div>

                <!-- Contact Information -->
                <div class="md:col-span-1 space-y-4">
                    <h3 class="text-white font-semibold text-lg mb-4">Contact Information</h3>
                    <div class="flex items-center space-x-3">
                        <svg class="text-red-600" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" data-astro-cid-sz7xmlte=""> <path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72 12.84 12.84 0 00.7 2.81 2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45 12.84 12.84 0 002.81.7A2 2 0 0122 16.92z" data-astro-cid-sz7xmlte=""></path> </svg>
                        <p class="text-sm text-gray-400"><?php echo getSetting('contact_phone', '+234 809 318 3556'); ?></p>
                    </div>
                    <div class="flex items-center space-x-3">
                        <svg class="text-red-600" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" data-astro-cid-sz7xmlte=""> <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z" data-astro-cid-sz7xmlte=""></path> <polyline points="22,6 12,13 2,6" data-astro-cid-sz7xmlte=""></polyline> </svg>
                        <p class="text-sm text-gray-400"><?php echo getSetting('contact_email', 'customerservices@annhurst-gsl.com'); ?></p>
                    </div>
                    <div class="flex items-start space-x-3">
                        <svg class="text-red-600" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" data-astro-cid-sz7xmlte=""> <path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z" data-astro-cid-sz7xmlte=""></path> <circle cx="12" cy="10" r="3" data-astro-cid-sz7xmlte=""></circle> </svg>
                        <p class="text-sm text-gray-400"><?php echo getSetting('contact_address', 'Lagos, Nigeria'); ?></p>
                    </div>
                </div>
            </div>

            <!-- Newsletter and Copyright Section -->
            <div class="mt-8 space-y-8">
                <!-- Newsletter -->
                <div class="bg-[#242831] p-6 sm:p-8 rounded-xl shadow-lg flex flex-col md:flex-row items-center justify-between space-y-6 md:space-y-0 md:space-x-8">
                    <div class="text-center md:text-left">
                        <h4 class="text-xl font-bold text-white mb-2">Stay Updated</h4>
                        <p class="text-sm text-gray-400">
                            Subscribe to our newsletter for the latest investment opportunities and company updates.
                        </p>
                    </div>
                    <form class="flex flex-col sm:flex-row items-center space-y-4 sm:space-y-0 sm:space-x-4 w-full md:w-auto">
                        <input type="email" placeholder="Enter your email address" class="w-full sm:w-64 px-5 py-3 rounded-lg bg-[#30343c] text-white border border-gray-600 focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                        <button type="submit" class="w-full sm:w-auto px-6 py-3 rounded-lg bg-red-600 text-white font-semibold shadow-lg hover:bg-red-700 transition-colors duration-200 flex items-center justify-center space-x-2">
                            <span>Subscribe</span>
                            <i class="fas fa-arrow-right"></i>
                        </button>
                    </form>
                </div>
                
                <!-- Copyright and Back to Top -->
                <div class="flex flex-col md:flex-row items-center justify-between space-y-4 md:space-y-0 text-sm text-gray-500 pt-8 border-t border-gray-700">
                    <p><?php echo getSetting('footer_text', '© 2025 Annhurst Transport Service Limited. All rights reserved.'); ?></p>
                    <div class="flex items-center space-x-2">
                        <span>Powered by</span>
                        <a href="#" class="text-red-500 hover:underline">UT Express</a>
                    </div>
                    <!-- Back to top button -->
                    <button onclick="window.scrollTo({ top: 0, behavior: 'smooth' });" class="p-3 rounded-full bg-red-600 hover:bg-red-700 transition-colors duration-200 text-white shadow-lg focus:outline-none focus:ring-2 focus:ring-red-500">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" data-astro-cid-sz7xmlte=""> <path d="M18 15l-6-6-6 6" data-astro-cid-sz7xmlte=""></path> </svg>
                    </button>
                </div>
            </div>

        </div>
    </footer>

    <!-- JavaScript -->
    <script>
        // Mobile menu toggle
        document.querySelector('.mobile-menu-button').addEventListener('click', function() {
            document.querySelector('.mobile-menu').classList.toggle('hidden');
        });
    </script>
</body>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const track = document.getElementById('testimonial-track');
        const cards = track.querySelectorAll('.testimonial-card');
        const prevBtn = document.getElementById('prev-btn');
        const nextBtn = document.getElementById('next-btn');
        const indicators = document.querySelectorAll('.indicator');
        let currentIndex = 0;

        function updateCarousel() {
            const offset = -currentIndex * 100;
            track.style.transform = `translateX(${offset}%)`;

            // Update active class for cards
            cards.forEach((card, index) => {
                if (index === currentIndex) {
                    card.classList.add('active');
                } else {
                    card.classList.remove('active');
                }
            });

            // Update active class for indicators
            indicators.forEach((indicator, index) => {
                if (index === currentIndex) {
                    indicator.classList.add('active');
                } else {
                    indicator.classList.remove('active');
                }
            });
            
            // Disable/enable buttons
            prevBtn.disabled = currentIndex === 0;
            nextBtn.disabled = currentIndex === cards.length - 1;
        }

        prevBtn.addEventListener('click', () => {
            if (currentIndex > 0) {
                currentIndex--;
                updateCarousel();
            }
        });

        nextBtn.addEventListener('click', () => {
            if (currentIndex < cards.length - 1) {
                currentIndex++;
                updateCarousel();
            }
        });

        indicators.forEach(indicator => {
            indicator.addEventListener('click', (e) => {
                currentIndex = parseInt(e.target.dataset.index);
                updateCarousel();
            });
        });

        // Initialize the carousel
        updateCarousel();
    });
</script>
</html>
