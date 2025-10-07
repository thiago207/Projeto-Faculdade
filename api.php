<?php
require_once 'config.php';

$pdo = Database::getInstance()->getConnection();
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'listar':
        $stmt = $pdo->query("SELECT * FROM pedidos ORDER BY id DESC");
        $pedidos = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($pedidos as &$pedido) {
            $stmtItens = $pdo->prepare("SELECT nome_item, quantidade FROM pedido_itens WHERE pedido_id = ?");
            $stmtItens->execute([$pedido['id']]);
            $pedido['itens'] = $stmtItens->fetchAll(PDO::FETCH_ASSOC);
        }

        sendResponse(true, $pedidos);
        break;

    case 'criar':
        $input = json_decode(file_get_contents('php://input'), true);
        if (!$input) sendResponse(false, null, 'Dados inválidos.');

        $pdo->beginTransaction();
        try {
            $stmt = $pdo->prepare("INSERT INTO pedidos (solicitante, data_pedido, unidade, observacoes) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $input['solicitante'],
                $input['data_pedido'],
                $input['unidade'],
                $input['observacoes']
            ]);

            $pedidoId = $pdo->lastInsertId();

            $stmtItem = $pdo->prepare("INSERT INTO pedido_itens (pedido_id, nome_item, label_item, quantidade) VALUES (?, ?, ?, ?)");
            foreach ($input['itens'] as $item) {
                $stmtItem->execute([$pedidoId, $item['nome'], $item['nome'], $item['quantidade']]);
            }

            $pdo->commit();
            sendResponse(true, null, 'Pedido criado com sucesso!');
        } catch (Exception $e) {
            $pdo->rollBack();
            sendResponse(false, null, 'Erro ao criar pedido: ' . $e->getMessage());
        }
        break;

    case 'deletar':
        $input = json_decode(file_get_contents('php://input'), true);
        $id = $input['id'] ?? 0;

        if (!$id) sendResponse(false, null, 'ID inválido.');

        try {
            $stmt = $pdo->prepare("DELETE FROM pedidos WHERE id = ?");
            $stmt->execute([$id]);
            sendResponse(true, null, 'Pedido excluído com sucesso.');
        } catch (Exception $e) {
            sendResponse(false, null, 'Erro ao excluir: ' . $e->getMessage());
        }
        break;

    default:
        sendResponse(false, null, 'Ação inválida.');
}
?>
