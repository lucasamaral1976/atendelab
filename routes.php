<?php

require_once __DIR__ . '/app/Middleware/auth.php';
require_once __DIR__ . '/config/database.php';

$controller = $_GET['controller'] ?? 'auth';
$action     = $_GET['action']     ?? 'login';

if ($controller === 'auth') {

    require_once __DIR__ . '/app/Controllers/AuthController.php';
    $ctrl = new AuthController($pdo);

    match ($action) {
        'login'     => $ctrl->exibirLogin(),
        'entrar'    => $ctrl->entrar(),
        'dashboard' => $ctrl->dashboard(),
        'logout'    => $ctrl->logout(),
        default     => $ctrl->exibirLogin(),
    };

} elseif ($controller === 'usuarios') {

    // Protege todo o CRUD de usuários — exige sessão ativa
    exigirAutenticacao();

    require_once __DIR__ . '/app/Controllers/UsuariosController.php';
    $ctrl = new UsuariosController($pdo);

    match ($action) {
        'index'   => $ctrl->index(),
        'criar'   => $ctrl->criar(),
        'salvar'  => $ctrl->salvar(),
        'editar'  => $ctrl->editar(),
        'atualizar' => $ctrl->atualizar(),
        'excluir' => $ctrl->excluir(),
        default   => $ctrl->index(),
    };

} else {
    http_response_code(404);
    echo '<h1>404 — Rota não encontrada</h1>';
}
