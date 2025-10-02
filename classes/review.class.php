<?php
    declare(strict_types = 1);


    class Review {
        public int $id;
        public int $rating;
        public string $comment;
        public int $provider_id;
        public int $buyer_id;
        public int $listing_id;
        public ?string $buyer_name = null;

        public function __construct(int $id, int $rating, string $comment, int $provider_id, int $buyer_id, int $listing_id, ?string $buyer_name) {
            $this->id = $id;
            $this->rating = $rating;
            $this->comment = $comment;
            $this->provider_id = $provider_id;
            $this->buyer_id = $buyer_id;
            $this->listing_id = $listing_id;
            $this->buyer_name = $buyer_name;
        }

        static function getReviewsByListing(PDO $db, int $listing_id) {
            $stmt = $db->prepare('SELECT Review.*, User.name AS buyer_name 
                                            FROM Review 
                                            JOIN User ON Review.buyer_id = User.id 
                                            WHERE listing_id = ?
                                ');
            $stmt->execute(array($listing_id));

            $listing = $stmt->fetch();

            $reviews = array();

            while($review = $stmt->fetch()) {
                $reviews[] = new Review(
                    $review['id'],
                    $review['rating'],
                    $review['comment'],
                    $review['provider_id'],
                    $review['buyer_id'],
                    $review['listing_id'],
                    $review['buyer_name']
                );
            }

            return $reviews;
        }

        public static function createReview(PDO $db, int $rating, string $comment, int $provider_id, int $buyer_id, int $listing_id) {
            $stmt = $db->prepare('INSERT INTO Review (rating, comment, provider_id, buyer_id, listing_id) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$rating, $comment, $provider_id, $buyer_id, $listing_id]);
        }
    }
?>