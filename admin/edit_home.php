<?php
// admin/edit_home.php
// Assume this file is placed in the admin folder and requires authentication (e.g., check session)
// Add this at the top of your admin pages for security

session_start();
require_once '../config/database.php';

// Check if user is logged in
if (!isset($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Function to get home page content
function getHomeContent($section, $key) {
    try {
        $pdo = getDBConnection();
        $stmt = $pdo->prepare("SELECT `value` FROM home_page WHERE section = ? AND `key` = ?");
        $stmt->execute([$section, $key]);
        $result = $stmt->fetch();
        return $result ? htmlspecialchars($result['value']) : ''; // Escape for output in inputs
    } catch (Exception $e) {
        return '';
    }
}

// The rest is the edited homepage UI with inputs instead of static text
// Wrap everything in a form that posts to a processing script (e.g., process_edit_home.php)
// In process_edit_home.php, you would loop through $_POST and update the table accordingly

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Same head as index.php, omitted for brevity -->
    <title>Edit Home Page</title>
</head>
<body class="bg-gray-100">
    <!-- Navigation - same as index.php, omitted for brevity -->

    <main>
        <form method="POST" action="process_edit_home.php"> <!-- Create this file to handle updates -->
            <!-- Hero Section -->
            <section class="bg-gradient-to-r from-gray-100 to-red-50 text-gray-900 py-20">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row items-center justify-between gap-12">
                    <div class="flex-1 text-left md:pr-12">
                        <h1 class="text-4xl md:text-6xl font-bold mb-6 text-shadow leading-tight">
                            <textarea name="hero_title" class="w-full border border-gray-300 rounded p-2" rows="2" placeholder="Enter hero title"><?php echo getHomeContent('hero', 'title'); ?></textarea>
                        </h1>
                        <p class="text-lg md:text-xl mb-8 text-shadow opacity-90 text-gray-800">
                            <textarea name="hero_description" class="w-full border border-gray-300 rounded p-2" rows="3" placeholder="Enter hero description"><?php echo getHomeContent('hero', 'description'); ?></textarea>
                        </p>
                        <div class="flex flex-col sm:flex-row gap-4 mb-10">
                            <a href="index.php?page=buses" class="bg-primary hover:bg-primary-dark text-white px-8 py-3 rounded-lg font-semibold transition-colors flex items-center justify-center">
                                <input name="hero_button1_text" class="w-full border border-gray-300 rounded p-2 text-center" value="<?php echo getHomeContent('hero', 'button1_text'); ?>" placeholder="Enter button 1 text">
                            </a>
                            <a href="index.php?page=contact" class="border-2 border-primary text-primary px-8 py-3 rounded-lg font-semibold hover:bg-primary hover:text-white transition-colors flex items-center justify-center">
                                <input name="hero_button2_text" class="w-full border border-gray-300 rounded p-2 text-center" value="<?php echo getHomeContent('hero', 'button2_text'); ?>" placeholder="Enter button 2 text">
                            </a>
                        </div>
                        <div class="flex flex-wrap gap-8 mt-6">
                            <div class="text-center">
                                <span class="text-3xl font-bold text-primary-light"><input name="hero_stat1_number" class="w-20 border border-gray-300 rounded p-1 text-center" value="<?php echo getHomeContent('hero', 'stat1_number'); ?>"></span>
                                <div class="text-gray-100 mt-2"><input name="hero_stat1_text" class="w-full border border-gray-300 rounded p-1" value="<?php echo getHomeContent('hero', 'stat1_text'); ?>"></div>
                            </div>
                            <div class="text-center">
                                <span class="text-3xl font-bold text-primary-light"><input name="hero_stat2_number" class="w-20 border border-gray-300 rounded p-1 text-center" value="<?php echo getHomeContent('hero', 'stat2_number'); ?>"></span>
                                <div class="text-gray-100 mt-2"><input name="hero_stat2_text" class="w-full border border-gray-300 rounded p-1" value="<?php echo getHomeContent('hero', 'stat2_text'); ?>"></div>
                            </div>
                            <div class="text-center">
                                <span class="text-3xl font-bold text-primary-light"><input name="hero_stat3_number" class="w-20 border border-gray-300 rounded p-1 text-center" value="<?php echo getHomeContent('hero', 'stat3_number'); ?>"></span>
                                <div class="text-gray-100 mt-2"><input name="hero_stat3_text" class="w-full border border-gray-300 rounded p-1" value="<?php echo getHomeContent('hero', 'stat3_text'); ?>"></div>
                            </div>
                        </div>
                    </div>
                    <div class="flex-1 flex flex-col gap-8 items-center justify-center">
                        <div class="bg-white shadow-lg rounded-xl p-6 w-80 mb-6">
                            <div class="flex items-center mb-2">
                                <span class="bg-primary-light rounded-full p-2 mr-3">
                                    <!-- Icon static -->
                                </span>
                                <span class="font-semibold text-gray-900 text-lg"><input name="hero_investment_success_title" class="w-full border border-gray-300 rounded p-1" value="<?php echo getHomeContent('hero', 'investment_success_title'); ?>"></span>
                            </div>
                            <div class="flex justify-between text-sm mb-2">
                                <span class="text-primary font-bold"><input name="hero_high_returns" class="w-full border border-gray-300 rounded p-1" value="<?php echo getHomeContent('hero', 'high_returns'); ?>"></span>
                                <span class="text-gray-500"><input name="hero_bus_investment_roi" class="w-full border border-gray-300 rounded p-1" value="<?php echo getHomeContent('hero', 'bus_investment_roi'); ?>"></span>
                            </div>
                            <!-- Progress bar static -->
                        </div>
                        <div class="bg-white shadow-lg rounded-xl p-6 w-80 flex flex-col items-center">
                            <div class="flex items-center mb-2">
                                <span class="bg-primary-light rounded-full p-2 mr-3">
                                    <!-- Icon static -->
                                </span>
                                <span class="font-semibold text-gray-900 text-lg"><input name="hero_customer_satisfaction_title" class="w-full border border-gray-300 rounded p-1" value="<?php echo getHomeContent('hero', 'customer_satisfaction_title'); ?>"></span>
                            </div>
                            <span class="text-3xl font-bold text-primary-light"><input name="hero_customer_satisfaction_percentage" class="w-20 border border-gray-300 rounded p-1 text-center" value="<?php echo getHomeContent('hero', 'customer_satisfaction_percentage'); ?>"></span>
                        </div>
                    </div>
                </div>
                <!-- Scroll note static -->
            </section>

            <!-- What we Offer Section -->
            <section class="flex flex-col items-center py-16 px-4 sm:px-6 lg:px-8">
                <section class="max-w-4xl text-center mb-16">
                    <h1 class="text-3xl sm:text-4xl font-bold text-gray-900 mb-4"><input name="offer_title" class="w-full border border-gray-300 rounded p-2 text-center" value="<?php echo getHomeContent('offer', 'title'); ?>"></h1>
                    <p class="text-sm sm:text-base text-gray-600 max-w-2xl mx-auto">
                        <textarea name="offer_description" class="w-full border border-gray-300 rounded p-2" rows="3" placeholder="Enter offer description"><?php echo getHomeContent('offer', 'description'); ?></textarea>
                    </p>
                </section>

                <!-- Cards Section -->
                <section class="max-w-6xl grid grid-cols-1 md:grid-cols-3 gap-8 mb-16">
                    <!-- Card 1 -->
                    <div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-200 text-center transform transition duration-300 ease-in-out hover:scale-105 hover:shadow-2xl hover:border-2 hover:border-red-600">
                        <!-- Icon static -->
                        <h2 class="text-xl font-semibold text-gray-800 mb-2"><input name="offer_card1_title" class="w-full border border-gray-300 rounded p-1" value="<?php echo getHomeContent('offer', 'card1_title'); ?>"></h2>
                        <p class="text-sm text-gray-500 mb-6">
                            <textarea name="offer_card1_description" class="w-full border border-gray-300 rounded p-2" rows="3"><?php echo getHomeContent('offer', 'card1_description'); ?></textarea>
                        </p>
                        <ul class="text-sm text-left text-gray-600 space-y-2">
                            <li class="flex items-center">
                                <!-- Icon static -->
                                <input name="offer_card1_item1" class="w-full border border-gray-300 rounded p-1 ml-2" value="<?php echo getHomeContent('offer', 'card1_item1'); ?>">
                            </li>
                            <li class="flex items-center">
                                <!-- Icon static -->
                                <input name="offer_card1_item2" class="w-full border border-gray-300 rounded p-1 ml-2" value="<?php echo getHomeContent('offer', 'card1_item2'); ?>">
                            </li>
                            <li class="flex items-center">
                                <!-- Icon static -->
                                <input name="offer_card1_item3" class="w-full border border-gray-300 rounded p-1 ml-2" value="<?php echo getHomeContent('offer', 'card1_item3'); ?>">
                            </li>
                        </ul>
                    </div>

                    <!-- Card 2 (similar structure, omitted for brevity, repeat for card2 and card3) -->
                    <!-- ... Add similar inputs for card2_title, card2_description, card2_item1 etc. ... -->

                </section>
            </section>

            <!-- Investment Opportunities Section (similar to offer, with inputs for titles, descriptions, items) -->
            <!-- ... Omitted for brevity, follow the pattern ... -->

            <!-- Testimonials Section -->
            <!-- ... Add textareas for testimonial1_text, inputs for author, title, rating, repeat for 2 and 3 ... -->

            <!-- Why Choose Us Section (similar, with inputs for title, description, feature1_title etc.) -->
            <!-- ... Omitted for brevity ... -->

            <!-- Featured Buses Section (title only, since content is from DB) -->
            <section class="py-16 bg-gray-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <h2 class="text-3xl font-bold text-center text-gray-900 mb-12"><input name="featured_buses_title" class="w-full border border-gray-300 rounded p-2 text-center" value="<?php echo getHomeContent('featured_buses', 'title'); ?>"></h2>
                    <!-- Buses display static, as from DB -->
                </div>
            </section>

            <div class="text-center mt-8">
                <button type="submit" class="bg-primary hover:bg-primary-dark text-white px-8 py-3 rounded-lg font-semibold transition-colors">Save Changes</button>
            </div>
        </form>
    </main>

    <!-- Footer - same as index.php, omitted -->

</body>
</html>