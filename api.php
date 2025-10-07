<?php
/**
 * api.php - API REST para Sistema de Pedidos
 * Gerencia todas as operações CRUD
 */

// Constante para permitir acesso ao config.php
define('API_ACCESS', true);

// Configurações de erro para desenvolvimento
error_reporting(E_ALL);
ini_set('display_errors', 0);
ini_set('log_errors', 1);
ini_set('error_log', __DIR__ . '/php-errors.log');

// Incluir arquivo de configuração
require_once 'config.php';

// Tratamento de requisições OPTIONS (CORS preflight)
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

try {
    // Obter instância do banco de dados
    $pdo = Database::getInstance()->getConnection();
    
    // Obter ação da URL
    $action = $_GET['action'] ?? '';
    
    // Roteamento de ações
    switch ($action) {
        case 'listar':
            listarPedidos($pdo);
            break;
            
        case 'criar':
            criarPedido($pdo);
            break;
            
        case 'deletar':
            deletarPedido($pdo);
            break;
            
        case 'buscar':
            buscarPedido($pdo);
            break;
            
        default:
            sendResponse(false, null, 'Ação inválida ou não especificada');
    }
    
} catch (Exception $e) {
    error_log('Erro na API: ' . $e->getMessage());
    sendResponse(false, null, 'Erro interno do servidor: ' . $e->getMessage());
}

/**
 * LISTAR todos os pedidos
 */
function listarPedidos($pdo) {
    try {
        // Buscar todos os pedidos ordenados por data
        $stmt = $pdo->query("
            SELECT 
                id, 
                solicitante, 
                data_pedido, 
                unidade, 
                observacoes,
                data_criacao
            FROM pedidos 
            ORDER BY id DESC
        ");
        
        $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Para cada pedido, buscar seus itens
        foreach ($pedidos as &$pedido) {
            $stmtItens = $pdo->prepare("
                SELECT 
                    nome_item, 
                    label_item,
                    quantidade 
                FROM pedido_itens 
                WHERE pedido_id = ?
                ORDER BY id ASC
            ");
            
            $stmtItens->execute([$pedido['id']]);
            $pedido['itens'] = $stmtItens->fetchAll(PDO::FETCH_ASSOC);
        }
        
        sendResponse(true, $pedidos, 'Pedidos carregados com sucesso');
        
    } catch (PDOException $e) {
        error_log('Erro ao listar pedidos: ' . $e->getMessage());
        sendResponse(false, null, 'Erro ao buscar pedidos no banco de dados');
    }
}

/**
 * CRIAR novo pedido
 */
function criarPedido($pdo) {
    try {
        // Obter dados JSON do corpo da requisição
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input) {
            sendResponse(false, null, 'Dados inválidos ou JSON malformado');
        }
        
        // Validar campos obrigatórios
        $required = ['solicitante', 'data_pedido', 'unidade', 'itens'];
        $error = validateInput($input, $required);
        
        if ($error) {
            sendResponse(false, null, $error);
        }
        
        // Validar se há itens no pedido
        if (empty($input['itens']) || !is_array($input['itens'])) {
            sendResponse(false, null, 'O pedido deve conter pelo menos um item');
        }
        
        // Sanitizar dados de entrada
        $solicitante = sanitizeString($input['solicitante']);
        $dataPedido = $input['data_pedido'];
        $unidade = sanitizeString($input['unidade']);
        $observacoes = isset($input['observacoes']) ? sanitizeString($input['observacoes']) : '';
        
        // Validar formato da data
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $dataPedido)) {
            sendResponse(false, null, 'Formato de data inválido. Use YYYY-MM-DD');
        }
        
        // Iniciar transação
        $pdo->beginTransaction();
        
        try {
            // Inserir pedido principal
            $stmt = $pdo->prepare("
                INSERT INTO pedidos (solicitante, data_pedido, unidade, observacoes, data_criacao) 
                VALUES (?, ?, ?, ?, NOW())
            ");
            
            $stmt->execute([
                $solicitante,
                $dataPedido,
                $unidade,
                $observacoes
            ]);
            
            // Obter ID do pedido inserido
            $pedidoId = $pdo->lastInsertId();
            
            // Preparar statement para inserir itens
            $stmtItem = $pdo->prepare("
                INSERT INTO pedido_itens (pedido_id, nome_item, label_item, quantidade) 
                VALUES (?, ?, ?, ?)
            ");
            
            // Inserir cada item do pedido
            foreach ($input['itens'] as $item) {
                if (!isset($item['nome']) || !isset($item['quantidade'])) {
                    throw new Exception('Item inválido: campos nome e quantidade são obrigatórios');
                }
                
                $nomeItem = sanitizeString($item['nome']);
                $labelItem = isset($item['label']) ? sanitizeString($item['label']) : $nomeItem;
                $quantidade = sanitizeString($item['quantidade']);
                
                $stmtItem->execute([
                    $pedidoId,
                    $nomeItem,
                    $labelItem,
                    $quantidade
                ]);
            }
            
            // Confirmar transação
            $pdo->commit();
            
            sendResponse(true, ['id' => $pedidoId], 'Pedido criado com sucesso!');
            
        } catch (Exception $e) {
            // Reverter transação em caso de erro
            $pdo->rollBack();
            throw $e;
        }
        
    } catch (PDOException $e) {
        error_log('Erro ao criar pedido: ' . $e->getMessage());
        sendResponse(false, null, 'Erro ao salvar pedido no banco de dados');
    } catch (Exception $e) {
        error_log('Erro ao criar pedido: ' . $e->getMessage());
        sendResponse(false, null, $e->getMessage());
    }
}

/**
 * DELETAR pedido
 */
function deletarPedido($pdo) {
    try {
        // Obter dados JSON
        $input = json_decode(file_get_contents('php://input'), true);
        
        if (!$input || !isset($input['id'])) {
            sendResponse(false, null, 'ID do pedido não fornecido');
        }
        
        $id = filter_var($input['id'], FILTER_VALIDATE_INT);
        
        if ($id === false || $id <= 0) {
            sendResponse(false, null, 'ID inválido');
        }
        
        // Iniciar transação
        $pdo->beginTransaction();
        
        try {
            // Deletar itens do pedido (CASCADE deve funcionar, mas garantimos aqui)
            $stmtItens = $pdo->prepare("DELETE FROM pedido_itens WHERE pedido_id = ?");
            $stmtItens->execute([$id]);
            
            // Deletar pedido
            $stmt = $pdo->prepare("DELETE FROM pedidos WHERE id = ?");
            $stmt->execute([$id]);
            
            if ($stmt->rowCount() === 0) {
                throw new Exception('Pedido não encontrado');
            }
            
            // Confirmar transação
            $pdo->commit();
            
            sendResponse(true, null, 'Pedido excluído com sucesso!');
            
        } catch (Exception $e) {
            $pdo->rollBack();
            throw $e;
        }
        
    } catch (PDOException $e) {
        error_log('Erro ao deletar pedido: ' . $e->getMessage());
        sendResponse(false, null, 'Erro ao excluir pedido do banco de dados');
    } catch (Exception $e) {
        sendResponse(false, null, $e->getMessage());
    }
}

/**
 * BUSCAR pedido específico por ID
 */
function buscarPedido($pdo) {
    try {
        $id = filter_var($_GET['id'] ?? 0, FILTER_VALIDATE_INT);
        
        if ($id === false || $id <= 0) {
            sendResponse(false, null, 'ID inválido');
        }
        
        // Buscar pedido
        $stmt = $pdo->prepare("
            SELECT 
                id, 
                solicitante, 
                data_pedido, 
                unidade, 
                observacoes,
                data_criacao
            FROM pedidos 
            WHERE id = ?
        ");
        
        $stmt->execute([$id]);
        $pedido = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (!$pedido) {
            sendResponse(false, null, 'Pedido não encontrado');
        }
        
        // Buscar itens do pedido
        $stmtItens = $pdo->prepare("
            SELECT 
                nome_item, 
                label_item,
                quantidade 
            FROM pedido_itens 
            WHERE pedido_id = ?
            ORDER BY id ASC
        ");
        
        $stmtItens->execute([$id]);
        $pedido['itens'] = $stmtItens->fetchAll(PDO::FETCH_ASSOC);
        
        sendResponse(true, $pedido, 'Pedido encontrado');
        
    } catch (PDOException $e) {
        error_log('Erro ao buscar pedido: ' . $e->getMessage());
        sendResponse(false, null, 'Erro ao buscar pedido no banco de dados');
    }
}
?>