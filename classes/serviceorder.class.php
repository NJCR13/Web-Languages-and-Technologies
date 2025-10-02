
<?php
class ServiceOrder {
    public int $id;
    public string $start_date;
    public string $status;
    public int $provider_id;
    public int $buyer_id;
    public int $listing_id;

    public function __construct(int $id, string $start_date, string $status, 
                               int $provider_id, int $buyer_id, int $listing_id) {
        $this->id = $id;
        $this->start_date = $start_date;
        $this->status = $status;
        $this->provider_id = $provider_id;
        $this->buyer_id = $buyer_id;
        $this->listing_id = $listing_id;
    }

    static function getOrdersForListing(PDO $db, int $listing_id): array {
        $stmt = $db->prepare('SELECT * FROM ServiceOrder WHERE listing_id = ?');
        $stmt->execute([$listing_id]);
        
        $orders = [];
        while ($order = $stmt->fetch()) {
            $orders[] = new ServiceOrder(
                (int)$order['id'],
                $order['start_date'],
                $order['status'],
                (int)$order['provider_id'],
                (int)$order['buyer_id'],
                (int)$order['listing_id']
            );
        }
        return $orders;
    }

    static function updateStatus(PDO $db, int $order_id, string $status): bool {
        $stmt = $db->prepare('UPDATE ServiceOrder SET status = ? WHERE id = ?');
        return $stmt->execute([$status, $order_id]);
    }
}
?>