<?php
declare(strict_types=1);

class Message {
    public int $id;
    public string $date;
    public string $text;
    public int $provider_id;
    public int $buyer_id;
    public int $listing_id;

    public function __construct(int $id, string $date, string $text, int $provider_id, int $buyer_id, int $listing_id) {
        $this->id = $id;
        $this->date = $date;
        $this->text = $text;
        $this->provider_id = $provider_id;
        $this->buyer_id = $buyer_id;
        $this->listing_id = $listing_id;
    }

    static function sendMessage(PDO $db, string $text, int $provider_id, int $buyer_id, int $listing_id): bool {
        $stmt = $db->prepare('
            INSERT INTO Message (date, text, provider_id, buyer_id, listing_id)
            VALUES (datetime("now"), ?, ?, ?, ?)
        ');
        return $stmt->execute([$text, $provider_id, $buyer_id, $listing_id]);
    }

    static function getMessages(PDO $db, int $provider_id, int $buyer_id, int $listing_id): array {
        $stmt = $db->prepare('
            SELECT * FROM Message
            WHERE ((provider_id = ? AND buyer_id = ? AND listing_id = ?) OR (buyer_id = ? AND provider_id = ? AND listing_id = ?))
            ORDER BY date ASC
        ');
        $stmt->execute([$provider_id, $buyer_id, $listing_id, $provider_id, $buyer_id, $listing_id]);

        $messages = [];
        while ($row = $stmt->fetch()) {
            $messages[] = new Message(
                intval($row['id']),
                $row['date'],
                $row['text'],
                intval($row['provider_id']),
                intval($row['buyer_id']),
                intval($row['listing_id'])
            );
        }
        return $messages;
    }
}
?>
