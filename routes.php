<?php

require_once 'config/database.php';

require_once 'app/Controllers/UsuariosController.php';
require_once 'app/Controllers/ClientesController.php';

$acao = $_GET['acao'] ?? '';

switch ($acao) {

    // USUÁRIOS

    case 'listar':
        $controller = new UsuariosController($pdo);
        $controller->listar();
        break;

    case 'cadastrar':
        $controller = new UsuariosController($pdo);
        $controller->cadastrar();
        break;

    case 'atualizar':
        $controller = new UsuariosController($pdo);
        $controller->atualizar();
        break;

    case 'excluir':
        $controller = new UsuariosController($pdo);
        $controller->excluir();
        break;

    // CLIENTES

    case 'listar_clientes':
        $controller = new ClientesController($pdo);
        $controller->listar();
        break;

    case 'cadastrar_cliente':
        $controller = new ClientesController($pdo);
        $controller->cadastrar();
        break;

    default:
        echo json_encode([
            "mensagem" => "Rota não encontrada"
        ]);
}