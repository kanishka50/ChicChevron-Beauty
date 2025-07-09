<?php

namespace Database\Seeders;

use App\Models\Page;
use App\Models\Faq;
use App\Models\Banner;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        // Create static pages
        $this->createPages();
        
        // Create FAQs
        $this->createFaqs();
        
        // Create sample banners
        $this->createBanners();

        $this->command->info('✅ Pages, FAQs, and Banners seeded successfully!');
    }

    private function createPages()
    {
        $pages = [
            [
                'title' => 'About Us',
                'slug' => 'about-us',
                'content' => '<h1>About ChicChevron Beauty</h1>
                <p>Welcome to ChicChevron Beauty, Sri Lanka\'s premier destination for authentic beauty products. Founded with a passion for beauty and wellness, we are committed to bringing you the finest selection of skincare, haircare, and beauty essentials from trusted international brands.</p>
                
                <h2>Our Mission</h2>
                <p>To make premium beauty products accessible to everyone in Sri Lanka, while ensuring authenticity, quality, and exceptional customer service. We believe that everyone deserves to feel confident and beautiful in their own skin.</p>
                
                <h2>Why Choose Us?</h2>
                <ul>
                <li><strong>100% Authentic Products:</strong> We source directly from authorized distributors and brand partners.</li>
                <li><strong>Expert Curation:</strong> Our team of beauty experts carefully selects each product in our collection.</li>
                <li><strong>Fast Delivery:</strong> Island-wide delivery with free shipping on orders over Rs. 5,000.</li>
                <li><strong>Customer Support:</strong> Our beauty consultants are here to help you find the perfect products for your needs.</li>
                </ul>
                
                <h2>Our Promise</h2>
                <p>At ChicChevron Beauty, we promise to provide you with authentic products, honest advice, and exceptional service. Your beauty journey is our priority, and we\'re here to support you every step of the way.</p>',
                'meta_title' => 'About ChicChevron Beauty - Your Premier Beauty Destination in Sri Lanka',
                'meta_description' => 'Learn about ChicChevron Beauty, Sri Lanka\'s trusted source for authentic beauty products. Discover our mission, values, and commitment to your beauty journey.',
                'is_active' => true,
            ],
            [
                'title' => 'Terms & Conditions',
                'slug' => 'terms-conditions',
                'content' => '<h1>Terms & Conditions</h1>
                <p><em>Last updated: ' . now()->format('F d, Y') . '</em></p>
                
                <h2>1. Acceptance of Terms</h2>
                <p>By accessing and using the ChicChevron Beauty website, you accept and agree to be bound by the terms and provision of this agreement.</p>
                
                <h2>2. Product Information</h2>
                <p>We strive to provide accurate product information, including ingredients, usage instructions, and pricing. However, we do not warrant that product descriptions or other content is accurate, complete, reliable, or error-free.</p>
                
                <h2>3. Pricing and Payment</h2>
                <p>All prices are listed in Sri Lankan Rupees (LKR) and are subject to change without notice. Payment must be completed before order processing begins.</p>
                
                <h2>4. Shipping and Delivery</h2>
                <p>We deliver island-wide across Sri Lanka. Delivery times may vary based on location and product availability. Free shipping is available for orders over Rs. 5,000.</p>
                
                <h2>5. Returns and Exchanges</h2>
                <p>We accept returns within 7 days of delivery for unopened, unused products in original packaging. Some restrictions may apply to certain products for hygiene reasons.</p>
                
                <h2>6. Privacy Policy</h2>
                <p>Your privacy is important to us. Please review our Privacy Policy to understand how we collect, use, and protect your personal information.</p>
                
                <h2>7. Contact Information</h2>
                <p>If you have any questions about these Terms & Conditions, please contact us at hello@chicchevron.com or +94 11 234 5678.</p>',
                'meta_title' => 'Terms & Conditions - ChicChevron Beauty',
                'meta_description' => 'Read our terms and conditions for shopping at ChicChevron Beauty. Learn about our policies, shipping, returns, and more.',
                'is_active' => true,
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'content' => '<h1>Privacy Policy</h1>
                <p><em>Last updated: ' . now()->format('F d, Y') . '</em></p>
                
                <h2>1. Information We Collect</h2>
                <p>We collect information you provide directly to us, such as when you create an account, make a purchase, or contact us for support.</p>
                
                <h2>2. How We Use Your Information</h2>
                <p>We use the information we collect to:</p>
                <ul>
                <li>Process and fulfill your orders</li>
                <li>Communicate with you about your account or transactions</li>
                <li>Provide customer support</li>
                <li>Send promotional emails (with your consent)</li>
                <li>Improve our website and services</li>
                </ul>
                
                <h2>3. Information Sharing</h2>
                <p>We do not sell, trade, or otherwise transfer your personal information to third parties without your consent, except as described in this policy.</p>
                
                <h2>4. Data Security</h2>
                <p>We implement appropriate security measures to protect your personal information against unauthorized access, alteration, disclosure, or destruction.</p>
                
                <h2>5. Cookies</h2>
                <p>We use cookies to enhance your browsing experience, analyze site traffic, and personalize content. You can choose to disable cookies in your browser settings.</p>
                
                <h2>6. Your Rights</h2>
                <p>You have the right to access, update, or delete your personal information. You can manage your account settings or contact us for assistance.</p>
                
                <h2>7. Contact Us</h2>
                <p>If you have questions about this Privacy Policy, please contact us at privacy@chicchevron.com or +94 11 234 5678.</p>',
                'meta_title' => 'Privacy Policy - ChicChevron Beauty',
                'meta_description' => 'Learn how ChicChevron Beauty protects your privacy and handles your personal information. Read our comprehensive privacy policy.',
                'is_active' => true,
            ],
            [
                'title' => 'Contact Us',
                'slug' => 'contact-us',
                'content' => '<h1>Contact ChicChevron Beauty</h1>
                <p>We\'d love to hear from you! Get in touch with our team for any questions, beauty advice, or support.</p>
                
                <h2>Get in Touch</h2>
                <div class="contact-info">
                <p><strong>Phone:</strong> +94 11 234 5678</p>
                <p><strong>Email:</strong> hello@chicchevron.com</p>
                <p><strong>Customer Support:</strong> support@chicchevron.com</p>
                <p><strong>Business Hours:</strong> Monday - Friday, 9:00 AM - 6:00 PM</p>
                </div>
                
                <h2>Visit Our Store</h2>
                <p><strong>Address:</strong><br>
                123 Beauty Street<br>
                Colombo 03<br>
                Sri Lanka</p>
                
                <h2>Beauty Consultation</h2>
                <p>Need help choosing the right products for your skin type or beauty concerns? Our expert beauty consultants are here to help! Book a free consultation by calling us or visiting our store.</p>
                
                <h2>Wholesale Inquiries</h2>
                <p>Interested in carrying ChicChevron Beauty products in your store? Contact our wholesale team at wholesale@chicchevron.com for partnership opportunities.</p>',
                'meta_title' => 'Contact ChicChevron Beauty - Get Beauty Advice & Support',
                'meta_description' => 'Contact ChicChevron Beauty for expert beauty advice, customer support, and product information. Phone, email, and store location details.',
                'is_active' => true,
            ],
        ];

        foreach ($pages as $pageData) {
            Page::create($pageData);
        }
    }

    private function createFaqs()
    {
        $faqs = [
            [
                'question' => 'Are all your products authentic?',
                'answer' => 'Yes, absolutely! We source all our products directly from authorized distributors and brand partners. Every product comes with authenticity guarantees and proper documentation.',
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'question' => 'Do you offer free shipping?',
                'answer' => 'We offer free island-wide shipping for orders over Rs. 5,000. For orders below this amount, standard shipping charges apply based on your location.',
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'question' => 'How long does delivery take?',
                'answer' => 'Delivery typically takes 2-5 business days depending on your location. Colombo and surrounding areas usually receive orders within 1-2 days, while other areas may take 3-5 days.',
                'sort_order' => 3,
                'is_active' => true,
            ],
            [
                'question' => 'Can I return or exchange products?',
                'answer' => 'Yes, we accept returns within 7 days of delivery for unopened, unused products in original packaging. For hygiene reasons, certain products like lip products and skincare may have restrictions.',
                'sort_order' => 4,
                'is_active' => true,
            ],
            [
                'question' => 'What payment methods do you accept?',
                'answer' => 'We accept all major credit and debit cards through our secure PayHere payment gateway. We also offer Cash on Delivery (COD) for orders up to Rs. 10,000.',
                'sort_order' => 5,
                'is_active' => true,
            ],
            [
                'question' => 'Do you offer beauty consultations?',
                'answer' => 'Yes! Our beauty experts are available to help you choose the right products for your skin type and concerns. You can call us, visit our store, or use our online chat for personalized advice.',
                'sort_order' => 6,
                'is_active' => true,
            ],
            [
                'question' => 'How do I track my order?',
                'answer' => 'Once your order is shipped, you\'ll receive a tracking number via email and SMS. You can also track your order status by logging into your account on our website.',
                'sort_order' => 7,
                'is_active' => true,
            ],
            [
                'question' => 'Do you have a physical store?',
                'answer' => 'Yes, we have a physical store located at 123 Beauty Street, Colombo 03. You can visit us Monday-Friday, 9:00 AM - 6:00 PM for product testing and consultations.',
                'sort_order' => 8,
                'is_active' => true,
            ],
            [
                'question' => 'Can I cancel my order?',
                'answer' => 'You can cancel your order within 2 hours of placing it by contacting our customer support. Once the order is processed and shipped, cancellation may not be possible.',
                'sort_order' => 9,
                'is_active' => true,
            ],
            [
                'question' => 'Do you offer wholesale pricing?',
                'answer' => 'Yes, we offer wholesale pricing for registered businesses and salons. Please contact our wholesale team at wholesale@chicchevron.com for pricing and minimum order requirements.',
                'sort_order' => 10,
                'is_active' => true,
            ],
        ];

        foreach ($faqs as $faqData) {
            Faq::create($faqData);
        }
    }

    private function createBanners()
    {
        $banners = [
            [
                'title' => 'Welcome to ChicChevron Beauty',
                'image_desktop' => null, // You can add actual image paths later
                'image_mobile' => null,
                'link_type' => 'category',
                'link_value' => '1', // Skin Care category
                'sort_order' => 1,
                'is_active' => true,
            ],
            [
                'title' => 'Premium Hair Care Collection',
                'image_desktop' => null,
                'image_mobile' => null,
                'link_type' => 'category',
                'link_value' => '2', // Hair Care category
                'sort_order' => 2,
                'is_active' => true,
            ],
            [
                'title' => 'Gentle Baby Care Products',
                'image_desktop' => null,
                'image_mobile' => null,
                'link_type' => 'category',
                'link_value' => '3', // Baby Care category
                'sort_order' => 3,
                'is_active' => true,
            ],
        ];

        foreach ($banners as $bannerData) {
            Banner::create($bannerData);
        }
    }
}