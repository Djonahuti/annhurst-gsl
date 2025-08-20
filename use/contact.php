            <section class="py-16 md:py-24">
                <div class="container mx-auto px-4 md:px-8 max-w-7xl">
            
                    <!-- Header -->
                    <div class="text-center mb-12 md:mb-16">
                        <h1 class="playfair-display text-4xl md:text-5xl font-bold text-gray-900 mb-4">Ready to Start Investing?</h1>
                        <p class="text-base md:text-lg text-gray-600 max-w-2xl mx-auto">
                            Get in touch with our expert team to discuss your investment goals and discover the perfect opportunity for your financial growth.
                        </p>
                    </div>

                    <!-- Contact & Form Section -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 md:gap-12 lg:gap-16">

                        <!-- Get In Touch Card -->
                        <div class="bg-white p-8 md:p-10 rounded-xl shadow-lg border border-gray-200">
                            <h2 class="playfair-display text-2xl md:text-3xl font-bold text-gray-900 mb-6">Get In Touch</h2>
                            <p class="text-sm text-gray-600 mb-8">
                                Our dedicated team is here to help you make informed investment decisions. Contact us today to schedule a consultation.
                            </p>
                            <div class="space-y-6">
                                <!-- Phone -->
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 text-red-600 bg-red-100 rounded-full">
                                        <i class="fa fa-phone text-lg"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-800">Phone</h3>
                                        <p class="text-sm text-gray-500 font-semibold"><?php echo getSetting('contact_phone', '+234 123 456 7890'); ?></p>
                                        <p class="text-xs text-gray-400">Available 24/7 for urgent matters</p>
                                    </div>
                                </div>
                                <!-- Email -->
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 text-red-600 bg-red-100 rounded-full">
                                        <i class="fa fa-envelope text-lg"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-800">Email</h3>
                                        <p class="text-sm text-gray-500 font-semibold"><?php echo getSetting('contact_email', 'info@annhurst.com'); ?></p>
                                        <p class="text-xs text-gray-400">We respond within 24 hours</p>
                                    </div>
                                </div>
                                <!-- Location -->
                                <div class="flex items-start space-x-4">
                                    <div class="flex-shrink-0 flex items-center justify-center h-10 w-10 text-red-600 bg-red-100 rounded-full">
                                        <i class="fa fa-map-marker-alt text-lg"></i>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-medium text-gray-800">Location</h3>
                                        <p class="text-sm text-gray-500 font-semibold"><?php echo getSetting('contact_address', '123 Transport Street, Lagos, Nigeria'); ?></p>
                                        <p class="text-xs text-gray-400">Serving clients nationwide</p>
                                    </div>
                                </div>
                            </div>
                            <!-- Follow Us -->
                            <div class="mt-8">
                                <h4 class="text-sm font-semibold text-gray-800 mb-3">Follow Us</h4>
                                <div class="flex space-x-4">
                                    <a href="#" class="flex items-center justify-center h-8 w-8 text-white bg-red-600 hover:bg-red-700 rounded-full transition-colors duration-200">
                                        <i class="fab fa-facebook-f text-sm"></i>
                                    </a>
                                    <a href="#" class="flex items-center justify-center h-8 w-8 text-white bg-red-600 hover:bg-red-700 rounded-full transition-colors duration-200">
                                        <i class="fab fa-instagram text-sm"></i>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!-- Send Us a Message Form -->
                        <div class="bg-white p-8 md:p-10 rounded-xl shadow-lg border border-gray-200">
                            <h2 class="playfair-display text-2xl md:text-3xl font-bold text-gray-900 mb-6">Send Us a Message</h2>
                            <p class="text-sm text-gray-600 mb-8">
                                Fill out the form below and we'll get back to you within 24 hours.
                            </p>
                            <form action="process_contact.php" method="POST" class="space-y-6">
                                <!-- Full Name -->
                                <div>
                                    <label for="fullName" class="block text-sm font-medium text-gray-700">Full Name *</label>
                                    <input type="text" id="name" name="name" class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm" required>
                                </div>
                                <!-- Email Address -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700">Email Address *</label>
                                    <input type="email" id="email" name="email" class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm" required>
                                </div>
                                <!-- Phone Number -->
                                <div>
                                    <label for="phoneNumber" class="block text-sm font-medium text-gray-700">Phone Number</label>
                                    <input type="tel" id="phone" name="phone" class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                                </div>
                                <!-- Investment Interest -->
                                <div>
                                    <label for="interest" class="block text-sm font-medium text-gray-700">Investment Interest</label>
                                    <select id="subject" name="subject" class="mt-1 block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm">
                                        <option value="">Select an option</option>
                                        <option value="transportation">Transportation</option>
                                        <option value="property">Property Investment</option>
                                        <option value="tailored">Tailored Business Solutions</option>
                                        <option value="other">Other</option>
                                    </select>
                                </div>
                                <!-- Message -->
                                <div>
                                    <label for="message" class="block text-sm font-medium text-gray-700">Message *</label>
                                    <div class="mt-1 relative">
                                        <textarea id="message" name="message" rows="4" class="block w-full px-4 py-3 border border-gray-300 rounded-lg shadow-sm focus:ring-red-500 focus:border-red-500 sm:text-sm" required></textarea>
                                        <div class="absolute right-3 bottom-3 text-gray-400">
                                            <i class="fas fa-pencil-alt"></i>
                                        </div>
                                    </div>
                                </div>
                                <!-- Subscribe Checkbox -->
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="subscribe" name="subscribe" type="checkbox" class="focus:ring-red-500 h-4 w-4 text-red-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="subscribe" class="font-medium text-gray-700">Subscribe to our newsletter for investment updates and opportunities</label>
                                    </div>
                                </div>
                                <!-- Submit Button -->
                                <div>
                                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors duration-200">
                                        Send Message
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </section>