<?php

require_once 'config/database.php';
require_once 'app/Controllers/UsuariosController.php';

$controller = new UsuariosController($pdo);

$acao = $_GET['acao'] ?? '';
switch ($acao) {

    case 'listar':
        $controller->listar();
        break;

    case 'cadastrar':
        $controller->cadastrar();
        break;

        case 'atualizar':
    $controller->atualizar();
    break;

    case 'excluir':
    $controller->excluir();
    break;

    default:
        echo json_encode([
            "mensagem" => "Rota não encontrada"
        ]);
}