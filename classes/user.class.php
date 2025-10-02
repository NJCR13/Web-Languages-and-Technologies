<?php
    declare(strict_types = 1);

    class User {

        public int $id;
        public string $name;
        public string $username;
        public string $password;
        public string $email;
        public ?string $phone;
        public ?float $rating = null;
        public int $is_admin;

        public function __construct(int $id, string $name, string $username, string $password, string $email, ?string $phone = null, ?int $is_admin = 0) {
            $this->id = $id;
            $this->name = $name;
            $this->username = $username;
            $this->password = $password;
            $this->email = $email;
            $this->phone = $phone;
            $this->is_admin = $is_admin;
        }

    
        static function getUser(PDO $db, int $user_id): User {
            $stmt = $db->prepare('SELECT id, name, username, password, email, phone, is_admin FROM User WHERE id = ?');
            $stmt->execute(array($user_id));

            $user = $stmt->fetch();

            return new User(
                $user['id'],
                $user['name'],
                $user['username'],
                $user['password'],
                $user['email'],
                $user['phone'],
                $user['is_admin'],
            );
        }

        static function getUserByNameAndPassword(PDO $db, $username ,$password): User {
            $stmt = $db->prepare('SELECT id, name, username, password, email, phone, is_admin FROM User WHERE username = ? AND password = ?');
            $stmt->execute([$username,$password]);

            $user = $stmt->fetch();

            return new User(
                $user['id'],
                $user['name'],
                $user['username'],
                $user['password'],
                $user['email'],
                $user['phone'],
                (int)$user['is_admin'],
            );
        }

        function getRating(PDO $db): float {
            $stmt = $db->prepare('SELECT AVG(rating) FROM Review WHERE provider_id = ?');
            $stmt->execute([$this->id]);
            $avgRating = $stmt->fetchColumn();

            $this->rating = $avgRating !== false ? round((float)$avgRating, 1) : 0.0;

            return $this->rating;
        }

        static function createUser(PDO $db, $name, $username, $password, $email, ?string $phone = null, int $is_admin = 0) {
            $stmt = $db->prepare('INSERT INTO User (name, username, password, email, phone, is_admin) VALUES (?, ?, ?, ?, ?, ?)');
            $stmt->execute([$name, $username, $password, $email, $phone, $is_admin]);
        }

        function save($db) {
            $stmt = $db->prepare('UPDATE User SET name = ?, username = ?, password = ?, email = ?, phone = ?, is_admin = ? WHERE id = ?');
            $stmt->execute([$this->name, $this->username, $this->password, $this->email, $this->phone, $this->is_admin, $this->id]);
        }
        static function promoteToAdmin(PDO $db, string $username): bool {
            $stmt = $db->prepare('UPDATE User SET is_admin = 1 WHERE username = ?');
            return $stmt->execute([$username]);
        }

        static function deleteUser(PDO $db, string $username): bool {
            $stmt = $db->prepare('DELETE FROM User WHERE username = ?');
            return $stmt->execute([$username]);
        }
    }
?>