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
        .hero-gradient {
            background: linear-gradient(135deg, #b5121b 0%, #8f0e15 100%);
        }
        .text-shadow {
            text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
        }
    </style>
</head>
<body class="bg-gray-50">
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
                    <a href="index.php" class="text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition-colors">Home</a>
                    <a href="index.php?page=about" class="text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition-colors">About</a>
                    <a href="index.php?page=buses" class="text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition-colors">Our Buses</a>
                    <a href="index.php?page=contact" class="text-gray-700 hover:text-primary px-3 py-2 rounded-md text-sm font-medium transition-colors">Contact</a>
                    <a href="admin/login.php" class="bg-primary hover:bg-primary-dark text-white px-4 py-2 rounded-md text-sm font-medium transition-colors">Admin</a>
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
                <a href="admin/login.php" class="bg-primary hover:bg-primary-dark text-white block px-3 py-2 rounded-md text-base font-medium">Admin</a>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main>
        <?php if ($page === 'home'): ?>
            <!-- Hero Section -->
            <section class="hero-gradient text-white py-20">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
                    <h1 class="text-4xl md:text-6xl font-bold mb-6 text-shadow">
                        Your Trusted Partner for Bus Higher Purchase
                    </h1>
                    <p class="text-xl md:text-2xl mb-8 text-shadow opacity-90">
                        Quality buses, flexible financing, and exceptional service
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="index.php?page=buses" class="bg-white text-primary px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors">
                            View Our Buses
                        </a>
                        <a href="index.php?page=contact" class="border-2 border-white text-white px-8 py-3 rounded-lg font-semibold hover:bg-white hover:text-primary transition-colors">
                            Get Started
                        </a>
                    </div>
                </div>
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

        <?php elseif ($page === 'contact'): ?>
            <!-- Contact Page -->
            <section class="py-16 bg-gray-50">
                <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h1 class="text-4xl font-bold text-center text-gray-900 mb-12">Contact Us</h1>
                    
                    <div class="grid md:grid-cols-2 gap-8 mb-12">
                        <div>
                            <h3 class="text-xl font-semibold mb-4">Get in Touch</h3>
                            <div class="space-y-4">
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                                    </svg>
                                    <span><?php echo getSetting('contact_email', 'info@annhurst.com'); ?></span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                    </svg>
                                    <span><?php echo getSetting('contact_phone', '+234 123 456 7890'); ?></span>
                                </div>
                                <div class="flex items-center">
                                    <svg class="w-5 h-5 text-primary mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    <span><?php echo getSetting('contact_address', '123 Transport Street, Lagos, Nigeria'); ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-xl font-semibold mb-4">Business Hours</h3>
                            <div class="space-y-2">
                                <p><strong>Monday - Friday:</strong> 8:00 AM - 6:00 PM</p>
                                <p><strong>Saturday:</strong> 9:00 AM - 4:00 PM</p>
                                <p><strong>Sunday:</strong> Closed</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Contact Form -->
                    <div class="bg-white rounded-lg shadow-lg p-8">
                        <h3 class="text-xl font-semibold mb-6">Send us a Message</h3>
                        <form action="process_contact.php" method="POST" class="space-y-6">
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name *</label>
                                    <input type="text" id="name" name="name" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                </div>
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address *</label>
                                    <input type="email" id="email" name="email" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                </div>
                            </div>
                            <div class="grid md:grid-cols-2 gap-6">
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                                    <input type="tel" id="phone" name="phone" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                </div>
                                <div>
                                    <label for="subject" class="block text-sm font-medium text-gray-700 mb-2">Subject</label>
                                    <input type="text" id="subject" name="subject" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent">
                                </div>
                            </div>
                            <div>
                                <label for="message" class="block text-sm font-medium text-gray-700 mb-2">Message *</label>
                                <textarea id="message" name="message" rows="5" required class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-primary focus:border-transparent"></textarea>
                            </div>
                            <div class="text-center">
                                <button type="submit" class="bg-primary hover:bg-primary-dark text-white px-8 py-3 rounded-lg font-semibold transition-colors">
                                    Send Message
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </section>

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
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid md:grid-cols-4 gap-8">
                <div class="col-span-2">
                    <div class="flex items-center mb-4">
                        <img src="img/logo.png" alt="Annhurst Transport" class="h-10 w-auto">
                    </div>
                    <p class="text-gray-300 mb-4">Your trusted partner for bus higher purchase solutions. We provide quality buses with flexible financing options.</p>
                    <div class="flex space-x-4">
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                            </svg>
                        </a>
                        <a href="#" class="text-gray-400 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.047-1.852-3.047-1.853 0-2.136 1.445-2.136 2.939v5.677H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/>
                            </svg>
                        </a>
                    </div>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="index.php" class="text-gray-300 hover:text-white transition-colors">Home</a></li>
                        <li><a href="index.php?page=about" class="text-gray-300 hover:text-white transition-colors">About Us</a></li>
                        <li><a href="index.php?page=buses" class="text-gray-300 hover:text-white transition-colors">Our Buses</a></li>
                        <li><a href="index.php?page=contact" class="text-gray-300 hover:text-white transition-colors">Contact</a></li>
                    </ul>
                </div>
                
                <div>
                    <h3 class="text-lg font-semibold mb-4">Contact Info</h3>
                    <ul class="space-y-2 text-gray-300">
                        <li><?php echo getSetting('contact_email', 'info@annhurst.com'); ?></li>
                        <li><?php echo getSetting('contact_phone', '+234 123 456 7890'); ?></li>
                        <li><?php echo getSetting('contact_address', '123 Transport Street, Lagos, Nigeria'); ?></li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p><?php echo getSetting('footer_text', '© 2024 Annhurst Transport Service Limited. All rights reserved.'); ?></p>
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
</html>
