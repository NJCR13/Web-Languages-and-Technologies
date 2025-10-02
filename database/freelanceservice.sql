PRAGMA foreign_keys = ON;


DROP TABLE IF EXISTS ServiceOrder;
DROP TABLE IF EXISTS Message;
DROP TABLE IF EXISTS Review;
DROP TABLE IF EXISTS Listing;
DROP TABLE IF EXISTS User;


CREATE TABLE User (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name TEXT NOT NULL,
    username TEXT UNIQUE NOT NULL,
    password TEXT NOT NULL, 
    email TEXT UNIQUE NOT NULL,
    phone TEXT UNIQUE DEFAULT NULL,
    -- image_profile TEXT DEFAULT 'adsad' -- PATH TO IMAGE
    is_admin BOOLEAN CHECK (is_admin IN (0, 1)) DEFAULT 0
);

CREATE TABLE Listing (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title TEXT NOT NULL,
    category TEXT NOT NULL,
    -- price DECIMAL NOT NULL (5,2),
    price REAL NOT NULL,
    delivery_time INTEGER NOT NULL,
    description TEXT NOT NULL,
    images TEXT NOT NULL,
    date TEXT NOT NULL,
    provider_id INTEGER NOT NULL,
    FOREIGN KEY (provider_id) REFERENCES User(id) ON DELETE CASCADE
);

CREATE TABLE Review (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    rating INTEGER NOT NULL CHECK (rating >= 1 AND rating <= 5),
    comment TEXT NOT NULL,
    provider_id INTEGER NOT NULL,
    buyer_id INTEGER NOT NULL,
    listing_id INTEGER NOT NULL,
    FOREIGN KEY (provider_id) REFERENCES User(id) ON DELETE CASCADE,
    FOREIGN KEY (buyer_id) REFERENCES User(id) ON DELETE CASCADE,
    FOREIGN KEY (listing_id) REFERENCES Listing(id) ON DELETE CASCADE
);

CREATE TABLE Message (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    date TEXT NOT NULL,
    text TEXT NOT NULL,
    provider_id INTEGER NOT NULL,
    buyer_id INTEGER NOT NULL,
    listing_id INTEGER NOT NULL,
    FOREIGN KEY (provider_id) REFERENCES User(id) ON DELETE CASCADE,
    FOREIGN KEY (buyer_id) REFERENCES User(id) ON DELETE CASCADE,
    FOREIGN KEY (listing_id) REFERENCES Listing(id) ON DELETE CASCADE
);

CREATE TABLE ServiceOrder (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    start_date TEXT NOT NULL,
    status TEXT NOT NULL CHECK(status IN ('Pending', 'In Progress', 'Completed', 'Cancelled')),
    provider_id INTEGER NOT NULL,
    buyer_id INTEGER NOT NULL,
    listing_id INTEGER NOT NULL,
    FOREIGN KEY (provider_id) REFERENCES User(id) ON DELETE CASCADE,
    FOREIGN KEY (buyer_id) REFERENCES User(id) ON DELETE CASCADE,
    FOREIGN KEY (listing_id) REFERENCES Listing(id) ON DELETE CASCADE
);







-- USERS (10 users: 1 admin, 9 regular users)
INSERT INTO User (name, username, password, email, phone, is_admin) VALUES 
('Alice Johnson', 'alicej', 'alicepass', 'alice@example.com', '912 345 001', 0),
('Bob Wilson',   'bobw',    'bobpass',   'bob@example.com',   '913 456 002', 0),
('Eva Martinez','evam',    'evapass',   'eva@example.com',   '914 567 003', 0),
('Admin',       'admin',   'adminpass', 'admin@example.com', '915 678 004', 1),
('Mike Brown',  'mikeb',   'mikepass',  'mike@example.com',  '916 789 005', 0),
('Sarah Lee',   'sarah',   'sarahpass', 'sarah@example.com', '917 890 006', 0),
('David Kim',   'davidk',  'davidpass', 'david@example.com', '918 901 007', 0),
('Luna Chen',   'lunac',   'lunapass',  'luna@example.com',  '919 012 008', 0),
('Tom White',   'tomw',    'tompass',   'tom@example.com',   '961 123 009', 0),
('Grace Hall',  'graceh',  'gracepass', 'grace@example.com', '962 234 010', 0),
('Carlos Mendes', 'carlosm', 'carlospass', 'carlos@example.com', '963 345 011', 0),
('Ana Silva', 'anas', 'anapass', 'ana@example.com', '964 456 012', 0),
('Pedro Costa', 'pedroc', 'pedropass', 'pedro@example.com', '965 567 013', 0),
('Maria Fernandes', 'mariaf', 'mariapass', 'maria@example.com', '966 678 014', 0),
('João Rocha', 'joaor', 'joaopass', 'joao@example.com', '967 789 015', 0);

-- LISTINGS (20 listings across 10 categories)
INSERT INTO Listing (title, category, price, delivery_time, description, images, date, provider_id) VALUES 
('E-commerce Website', 'Web Development', 999.99, 14, 'Full-stack e-commerce site with payment integration.', 'web1.jpg,web2.jpg', '2023-10-01', 1),
('Mobile App UI/UX Design', 'App Development', 799.99, 10, 'Figma/Adobe XD design for mobile apps.', 'app1.jpg', '2023-10-02', 2),
('Brand Identity Package', 'Graphic Design', 299.99, 7, 'Logo + business cards + style guide.', 'brand1.jpg,brand2.jpg', '2023-10-03', 3),
('SEO Optimization', 'Digital Marketing', 199.99, 30, 'Keyword research & on-page SEO.', 'seo1.jpg', '2023-10-04', 5),
('YouTube Video Editing', 'Video Production', 49.99, 3, 'Professional video editing for creators.', 'video1.jpg', '2023-10-05', 6),
('Resume Writing', 'Career Services', 89.99, 2, 'ATS-optimized resume & cover letter.', 'resume1.jpg', '2023-10-06', 7),
('Social Media Ads', 'Digital Marketing', 149.99, 7, 'Facebook/Instagram ad campaigns.', 'ads1.jpg', '2023-10-07', 8),
('3D Product Modeling', '3D Design', 249.99, 5, 'Blender/Maya 3D models for products.', '3d1.jpg', '2023-10-08', 9),
('Voiceover Recording', 'Audio Services', 29.99, 1, 'Professional voiceover in English/Spanish.', 'voice1.jpg', '2023-10-09', 10),
('Data Analysis', 'Data Science', 399.99, 7, 'Python/R analysis & visualization.', 'data1.jpg', '2023-10-10', 1),
('WordPress Plugin Development', 'Web Development', 199.99, 5, 'Custom WordPress plugins.', 'wp1.jpg', '2023-10-11', 2),
('Infographic Design', 'Graphic Design', 129.99, 3, 'Editable infographics in PDF/PNG.', 'infographic1.jpg', '2023-10-12', 3),
('LinkedIn Profile Optimization', 'Career Services', 59.99, 1, 'Boost your LinkedIn visibility.', 'linkedin1.jpg', '2023-10-13', 5),
('TikTok Video Creation', 'Video Production', 99.99, 2, 'Trending TikTok content creation.', 'tiktok1.jpg', '2023-10-14', 6),
('Podcast Editing', 'Audio Services', 79.99, 3, 'Edit & master your podcast episodes.', 'podcast1.jpg', '2023-10-15', 7),
('Landing Page Design', 'Web Development', 299.99, 5, 'Responsive landing page with contact form integration.', 'web3.jpg', '2023-10-16', 1),
('WordPress Theme Customization', 'Web Development', 399.99, 7, 'Custom theme development and existing theme modifications.', 'wp2.jpg', '2023-10-17', 1),
('API Integration Service', 'Web Development', 499.99, 10, 'Third-party API integration for web applications.', 'api1.jpg', '2023-10-18', 1),
('Progressive Web App Development', 'App Development', 899.99, 14, 'Build PWAs with offline functionality.', 'pwa1.jpg', '2023-10-19', 1),
('CMS Setup & Configuration', 'Web Development', 149.99, 3, 'WordPress/Drupal installation and basic setup.', 'cms1.jpg', '2023-10-20', 1),
('Logo Design Package', 'Graphic Design', 150.00, 5, 'Professional logo design with 3 concepts.', 'logo1.jpg,logo2.jpg', '2023-10-21', 11),
('Custom WordPress Theme', 'Web Development', 350.00, 10, 'Responsive WordPress theme tailored to your needs.', 'wp_theme1.jpg', '2023-10-22', 12),
('Social Media Management', 'Digital Marketing', 200.00, 7, 'Manage your social media accounts for a week.', 'social1.jpg', '2023-10-23', 13),
('Product Photography', 'Photography', 300.00, 3, 'High-quality product photos for your catalog.', 'photo1.jpg,photo2.jpg', '2023-10-24', 14),
('Business Plan Writing', 'Writing & Translation', 400.00, 14, 'Comprehensive business plan for startups.', 'bizplan1.jpg', '2023-10-25', 15);

-- ORDERS (20 orders with varied statuses)
INSERT INTO ServiceOrder (start_date, status, provider_id, buyer_id, listing_id) VALUES 
-- Completed orders (can have reviews)
('2023-10-05', 'Completed', 1, 2, 1),   -- Bob hired Alice's e-commerce website
('2023-10-06', 'Completed', 2, 3, 2),   -- Eva hired Bob's app design
('2023-10-07', 'Completed', 3, 5, 3),   -- Mike hired Eva's brand identity
('2023-10-08', 'Completed', 5, 6, 4),   -- Sarah hired Mike's SEO service
-- Pending/In Progress orders
('2023-10-09', 'Pending', 6, 7, 5),     -- David hired Sarah's video editing
('2023-10-10', 'In Progress', 7, 8, 6), -- Luna hired David's resume writing
('2023-10-11', 'Pending', 8, 9, 7),     -- Tom hired Luna's social media ads
('2023-10-12', 'In Progress', 9, 10, 8),-- Grace hired Tom's 3D modeling
-- Cross-category orders
('2023-10-13', 'Completed', 10, 1, 9),  -- Alice hired Grace's voiceover
('2023-10-14', 'Completed', 1, 3, 10),  -- Eva hired Alice's data analysis
('2023-10-15', 'Cancelled', 2, 5, 11),  -- Mike cancelled Bob's WordPress plugin
('2023-10-16', 'Pending', 3, 6, 12),    -- Sarah hired Eva's infographic design
('2023-10-17', 'In Progress', 5, 7, 13),-- David hired Mike's LinkedIn service
('2023-10-18', 'Completed', 6, 8, 14),  -- Luna hired Sarah's TikTok service
('2023-10-19', 'Pending', 7, 9, 15),    -- Tom hired David's podcast editing
('2023-10-26', 'Completed', 11, 12, 21),
('2023-10-27', 'In Progress', 12, 13, 22),
('2023-10-28', 'Pending', 13, 14, 23),
('2023-10-29', 'Cancelled', 14, 15, 24),
('2023-10-30', 'Completed', 15, 11, 25);

-- REVIEWS (15 reviews for completed orders)
INSERT INTO Review (rating, comment, provider_id, buyer_id, listing_id) VALUES 
(5, 'Fantastic e-commerce site!', 1, 2, 1),
(4, 'Good quality but took longer to arrive than expected', 1, 3, 1),
(5, 'Absolutely perfect! Exceeded all my expectations', 1, 8, 1),
(3, 'Decent product but customer service was slow', 1, 5, 1),
(5, 'Fast shipping and excellent packaging', 1, 6, 1),
(4, 'Minor scratches but overall good value', 1, 7, 1),
(4, 'Great app design, but missed a deadline.', 2, 3, 2),
(5, 'Love the brand identity!', 3, 5, 3),
(3, 'SEO results were average.', 5, 6, 4),
(5, 'Voiceover was perfect for our video.', 10, 1, 9),
(2, 'Data analysis lacked depth.', 1, 3, 10),
(5, 'TikTok videos went viral!', 6, 8, 14),
(5, 'Excellent logo designs, very creative!', 11, 12, 21),
(4, 'Theme was good but needed some tweaks.', 12, 13, 22),
(5, 'Great social media content, very engaging.', 13, 14, 23),
(3, 'Photos were okay, expected better lighting.', 14, 15, 24),
(5, 'Business plan exceeded expectations!', 15, 11, 25),
(5, 'Our website traffic tripled after the redesign!', 1, 3, 17),
(4, 'Theme customization took longer than expected.', 1, 5, 17),
(5, 'Flawless API integration with our CRM.', 1, 7, 18),
(3, 'PWA works well but initial setup was confusing.', 1, 9, 19),
(5, 'Logo perfectly captured our brand identity!', 3, 10, 12),
(4, 'Infographic needed more data visualization sections.', 3, 2, 12),
(5, 'Business cards look incredibly professional.', 11, 5, 21),
(2, 'Social ads didn’t generate a single lead.', 13, 15, 23),
(5, 'Instagram followers increased by 200%!', 8, 12, 7),
(4, 'Good SEO audit but lacked implementation guidance.', 5, 11, 4),
(5, 'YouTube video editing made my content shine!', 6, 8, 5),
(1, 'Podcast editor deleted raw files accidentally.', 7, 14, 15),
(5, 'Voiceover artist has amazing tonal variety.', 10, 3, 9),
(5, 'Recruiters started messaging me daily!', 5, 9, 13),
(4, 'Good resume but cover letter felt generic.', 7, 15, 6),
(5, '3D model was print-ready on first try.', 9, 12, 8),
(3, 'Product rendering lacked material details.', 14, 7, 24),
(5, 'Business plan helped secure our funding!', 15, 13, 25),
(4, 'WordPress theme had minor mobile responsiveness issues.', 12, 14, 22),
(5, 'Social manager boosted our TikTok engagement.', 13, 11, 23),
(2, 'Product photos looked washed out.', 14, 10, 24),
(5, 'Custom plugin solved our inventory issues!', 2, 15, 11);

-- MESSAGES (30+ messages across listings)
INSERT INTO Message (date, text, provider_id, buyer_id, listing_id) VALUES 
-- Conversation for Listing 1 (Alice's e-commerce website)
('2023-10-05 09:00:00', 'Can you integrate PayPal?', 1, 2, 1),
('2023-10-05 09:15:00', 'Yes, PayPal and Stripe are supported.', 1, 2, 1),
-- Conversation for Listing 2 (Bob's app design)
('2023-10-06 14:30:00', 'Need dark mode in the design.', 2, 3, 2),
('2023-10-06 15:00:00', 'Sure, I’ll include dark/light themes.', 2, 3, 2),
-- Conversation for Listing 3 (Eva's brand identity)
('2023-10-07 11:00:00', 'Hello Eva!', 3, 1, 3),
('2023-10-07 11:00:00', 'Can you use blue as the primary color?', 3, 5, 3),
('2023-10-07 11:30:00', 'Absolutely! Attached the first draft.', 3, 5, 3),
-- Cancelled order (Listing 11)
('2023-10-15 16:00:00', 'Why did you cancel the order?', 2, 5, 11),
('2023-10-15 16:30:00', 'Found another developer, sorry.', 5, 2, 11),
('2023-10-26 10:00:00', 'Can you include a favicon with the logo?', 11, 12, 21),
('2023-10-26 10:15:00', 'Sure, I will add that.', 11, 12, 21),
('2023-10-27 11:00:00', 'Is the theme compatible with Elementor?', 12, 13, 22),
('2023-10-27 11:30:00', 'Yes, it is fully compatible.', 12, 13, 22),
('2023-10-28 09:00:00', 'Can you schedule posts for weekends?', 13, 14, 23),
('2023-10-28 09:20:00', 'Absolutely, I will set that up.', 13, 14, 23),
('2023-10-29 14:00:00', 'Do you offer indoor photography?', 14, 15, 24),
('2023-10-29 14:30:00', 'Yes, I have a studio setup.', 14, 15, 24),
('2023-10-30 16:00:00', 'Can the business plan include market analysis?', 15, 11, 25),
('2023-10-30 16:30:00', 'Of course, I will include detailed analysis.', 15, 11, 25);