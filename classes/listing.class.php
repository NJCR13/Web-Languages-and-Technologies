<?php
    declare(strict_types = 1);

    class Listing{

        public int $id;
        public string $title;
        public string $category;
        public float $price;
        public int $delivery_time;
        public string $description;
        public string $images;
        public string $date;
        public int $provider_id;

        public function __construct(int $id, string $title, string $category, float $price, int $delivery_time, string $description, string $date, int $provider_id) {
            $this->id = $id;
            $this->title = $title;
            $this->category = $category;
            $this->price = $price;
            $this->delivery_time = $delivery_time;
            $this->description = $description;
            $this->date = $date;
            $this->provider_id = $provider_id;
        }

        static function getAllListings(PDO $db): array {
            $stmt = $db->prepare('SELECT * FROM Listing');
            $stmt->execute();

            $listings = array();

            while($listing = $stmt->fetch()) {
                $listings[] = new Listing(
                    $listing['id'],
                    $listing['title'],
                    $listing['category'],
                    $listing['price'],
                    $listing['delivery_time'],
                    $listing['description'],
                    $listing['date'],
                    $listing["provider_id"]
                );
            }

            return $listings;
        }

        public function update(PDO $db): bool {
            $stmt = $db->prepare('
                UPDATE Listing 
                SET 
                    title = ?,
                    category = ?,
                    price = ?,
                    delivery_time = ?,
                    description = ?
                WHERE id = ?
            ');
            
            return $stmt->execute([
                $this->title,
                $this->category,
                $this->price,
                $this->delivery_time,
                $this->description,
                $this->id
            ]);
        }

        static function getListingsByUser(PDO $db, int $user_id): array {
            $stmt = $db->prepare('SELECT * FROM Listing WHERE provider_id = ?');
            $stmt->execute(array($user_id));

            $listings = array();

            while($listing = $stmt->fetch()) {
                $listings[] = new Listing(
                    $listing['id'],
                    $listing['title'],
                    $listing['category'],
                    $listing['price'],
                    $listing['delivery_time'],
                    $listing['description'],
                    $listing['date'],
                    $listing["provider_id"]
                );
            }

            return $listings;
        }

        static function getTopListings(PDO $db): array {
            $stmt = $db->prepare('SELECT 
                                        Listing.id,
                                        Listing.title,
                                        Listing.category,
                                        Listing.price,
                                        Listing.delivery_time,
                                        Listing.description,
                                        Listing.date,
                                        Listing.provider_id,
                                        ROUND(AVG(Review.rating), 1) AS avg_rating,
                                        COUNT(DISTINCT ServiceOrder.id) AS total_orders,
                                        COUNT(DISTINCT Review.id) AS total_reviews
                                    FROM Listing
                                    LEFT JOIN Review ON Listing.id = Review.listing_id
                                    LEFT JOIN ServiceOrder ON Listing.id = ServiceOrder.listing_id
                                    GROUP BY Listing.id
                                    ORDER BY 
                                        avg_rating DESC,
                                        total_orders DESC,
                                        total_reviews DESC
                                    LIMIT 12;
            ');
            $stmt->execute();

            $listings = array();

            while($listing = $stmt->fetch()) {
                $listings[] = new Listing(
                    $listing['id'],
                    $listing['title'],
                    $listing['category'],
                    $listing['price'],
                    $listing['delivery_time'],
                    $listing['description'],
                    $listing['date'],
                    $listing["provider_id"]
                );
            }

            return $listings;
        }

        static function getCategories(PDO $db): array {
            $stmt = $db->prepare('SELECT category FROM Listing GROUP BY category');
            $stmt->execute();

            $categories = array();

            while($listing = $stmt->fetch()) {
                $categories[] = array(
                    'category' => $listing['category']
                );
            }

            return $categories;
        }

        static function getListingById(PDO $db, int $listing_id): Listing {
            $stmt = $db->prepare('SELECT * FROM Listing WHERE id = ?');
            $stmt->execute(array($listing_id));

            $listing = $stmt->fetch();

            return new Listing(
                $listing['id'],
                $listing['title'],
                $listing['category'],
                $listing['price'],
                $listing['delivery_time'],
                $listing['description'],
                $listing['date'],
                $listing["provider_id"]
            );
        }
        
        static function getListingsByBuyer(PDO $db, int $user_id): array {
            $stmt = $db->prepare('
                SELECT Listing.*
                FROM ServiceOrder
                JOIN Listing ON ServiceOrder.listing_id = Listing.id
                WHERE ServiceOrder.buyer_id = ?
            ');
            $stmt->execute(array($user_id));

            $listings = array();

            while($listing = $stmt->fetch()) {
                $listings[] = new Listing(
                    $listing['id'],
                    $listing['title'],
                    $listing['category'],
                    $listing['price'],
                    $listing['delivery_time'],
                    $listing['description'],
                    $listing['date'],
                    $listing["provider_id"]
                );
            }

            return $listings;
        }

        // GET DIFFERENT IMAGES FOR LISTINGS
        function getImages() : string {
            // $default = "/images/listings";
            $image = "/images/listings/listing$this->id.png";
            return dirname(__DIR__).$image;
        }

        static function createListing(PDO $db, string $title, string $category, float $price, int $delivery_time, string $description, string $date, int $provider_id) {
            $stmt = $db->prepare('INSERT INTO Listing (title, category, price, delivery_time, description, images, date, provider_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
            return $stmt->execute([$title, $category, $price, $delivery_time, $description,"a", $date, $provider_id]);
        }

        function save($db) {
            $stmt = $db->prepare('UPDATE Listing SET title = ?, category = ?, price = ?, delivery_time = ?, description = ?, date = ?, provider_id = ? WHERE id = ?');
            $stmt->execute([$this->title, $this->category, $this->price, $this->delivery_time, $this->description, $this->date, $this->provider_id, $this->id]);
        }
        static function deleteListing(PDO $db, int $listing_id): bool {
            $stmt = $db->prepare('DELETE FROM Listing WHERE id = ?');
            return $stmt->execute([$listing_id]);
        }

        static function getListingsBySearch(PDO $db, ?string $searchTerm = null, string $sortBy = 'date_desc'): array {
            $query = "SELECT id, title, category, price, delivery_time, description, images, date, provider_id FROM Listing";
            $params = [];
            if ($searchTerm !== null && trim($searchTerm) !== '') {
                $searchTermWildcard = '%' . trim(strtolower($searchTerm)) . '%';
                $query .= " WHERE (LOWER(title) LIKE :searchTerm OR LOWER(description) LIKE :searchTerm)";
                $params[':searchTerm'] = $searchTermWildcard;
            }
            $orderByClause = " ORDER BY ";
            switch ($sortBy) {
                case 'price_asc': $orderByClause .= "price ASC"; break;
                case 'price_desc': $orderByClause .= "price DESC"; break;
                case 'title_asc': $orderByClause .= "LOWER(title) ASC"; break;
                case 'title_desc': $orderByClause .= "LOWER(title) DESC"; break;
                case 'date_asc': $orderByClause .= "date ASC"; break;
                case 'date_desc': default: $orderByClause .= "date DESC"; break;
            }
            $query .= $orderByClause;
            $stmt = $db->prepare($query);
            $stmt->execute($params);
            $listings = array();
            while($row = $stmt->fetch()) {
                $listings[] = new Listing(
                    (int)$row['id'], $row['title'], $row['category'], (float)$row['price'],
                    (int)$row['delivery_time'], $row['description'],
                    $row['date'], (int)$row["provider_id"]
                );
            }
            return $listings;
        }
        public function getFirstImageURL(string $baseImagePath, string $hardcodedImageFilename = 'default-service-image.jpg'): string { // This is fine
            $baseImagePath = rtrim($baseImagePath, '/') . '/';
            return htmlspecialchars($baseImagePath . $hardcodedImageFilename);
        }
    }
?>