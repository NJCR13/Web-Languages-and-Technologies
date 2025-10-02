<?php
    declare(strict_types = 1);


    class Order {
        public int $id;
        public string $start_date;
        public string $status;
        public int $provider_id;
        public int $buyer_id;
        public int $listing_id;

        public function __construct(int $id, string $start_date, string $status, int $provider_id, int $buyer_id, int $listing_id) {
            $this->id = $id;
            $this->start_date = $start_date;
            $this->status = $status;
            $this->provider_id = $provider_id;
            $this->buyer_id = $buyer_id;
            $this->listing_id = $listing_id;
        }

        

        static function getByListingAndUser(PDO $db, int $listing_id, ?int $user_id): ?Order {
            $stmt = $db->prepare('SELECT * FROM ServiceOrder WHERE listing_id = ? and (provider_id = ? OR buyer_id = ?)');
            $stmt->execute([$listing_id,$user_id,$user_id]);
            $order = $stmt->fetch();
            if ($order) {
                return new Order(
                    $order['id'],
                    $order['start_date'],
                    $order['status'],
                    $order['provider_id'],
                    $order['buyer_id'],
                    $order['listing_id']
                );
            }
            return null;
        }

        static function createOrder(PDO $db, string $start_date, string $status, int $provider_id, int $buyer_id, int $listing_id): void {
            $stmt = $db->prepare('INSERT INTO ServiceOrder (start_date, status, provider_id, buyer_id, listing_id) VALUES (?, ?, ?, ?, ?)');
            $stmt->execute([$start_date, $status, $provider_id, $buyer_id, $listing_id]);
        }

        function save(PDO $db): void {
            $stmt = $db->prepare('UPDATE ServiceOrder SET start_date = ?, status = ?, provider_id = ?, buyer_id = ?, listing_id = ? WHERE id = ?');
            $stmt->execute([$this->start_date, $this->status, $this->provider_id, $this->buyer_id, $this->listing_id, $this->id]);
        }
    }
?>