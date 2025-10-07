<?php
/**
 * config.php - Configuração do Sistema de Pedidos
 * Singleton Pattern para conexão com banco de dados
 */

// Previne acesso direto
if (!defined('API_ACCESS')) {
    http_response_code(403);
    die('Acesso negado');
}

class Database {
    private static $instance = null;
    private $conn;
    
    // Configurações do banco de dados
    private $host = 'localhost';
    private $db = 'dispensa_escolar';
    private $user = 'root';
    private $pass = '';
    private $charset = 'utf8mb4';

    /**
     * Construtor privado - Singleton
     */
    private function __construct() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->db};charset={$this->charset}";
            
            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES {$this->charset}"
            ];

            $this->conn = new PDO($dsn, $this->user, $this->pass, $options);
            
        } catch (PDOException $e) {
            error_log('Erro na conexão com banco de dados: ' . $e->getMessage());
            throw new Exception('Erro ao conectar com o banco de dados');
        }
    }

    /**
     * Retorna instância única da classe (Singleton)
     */
    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }