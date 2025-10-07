<?php
class Database {
    private static $instance = null;
    private $conn;

    private $host = 'localhost';
    private $db = 'dispensa_escolar';
    private $user = 'root';
    private $pass = '';

    private function __construct() {
        $this->conn = new PDO(
            "mysql:host={$this->host};dbname={$this->db};charset=utf8",
            $this->user,
            $this->pass,
            [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
        );
    }

    public static function getInstance() {
        if (!self::$instance) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->conn;
    }
}

function sendResponse($success, $data = null, $message = '') {
    header('Content-Type: application/json; charset=utf-8');
    echo json_encode(['success' => $success, 'data' => $data, 'message' => $message]);
    exit;
}
?>
