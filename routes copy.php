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

    exigirAutenticacao();
    require_once __DIR__ . '/app/Controllers/UsuariosController.php';
    $ctrl = new UsuariosController($pdo);

    match ($action) {
        'index'     => $ctrl->index(),
        'criar'     => $ctrl->criar(),
        'salvar'    => $ctrl->salvar(),
        'editar'    => $ctrl->editar(),
        'atualizar' => $ctrl->atualizar(),
        'excluir'   => $ctrl->excluir(),
        default     => $ctrl->index(),
    };

} elseif ($controller === 'pessoas') {

    exigirAutenticacao();
    require_once __DIR__ . '/app/Controllers/PessoasController.php';
    $ctrl = new PessoasController($pdo);

    match ($action) {
        'listar'    => $ctrl->listar(),
        'buscar', 'buscarPorId' => $ctrl->buscar(),
        'criar'     => $ctrl->criar(),
        'atualizar' => $ctrl->atualizar(),
        'inativar'  => $ctrl->inativar(),
        default     => http_response_code(404),
    };

} elseif ($controller === 'tipos') {

    exigirAutenticacao();
    require_once __DIR__ . '/app/Controllers/TiposAtendimentosController.php';
    $ctrl = new TiposAtendimentosController($pdo);

    match ($action) {
        'listar'    => $ctrl->listar(),
        'buscar', 'buscarPorId' => $ctrl->buscar(),
        'criar'     => $ctrl->criar(),
        'atualizar' => $ctrl->atualizar(),
        'inativar'  => $ctrl->inativar(),
        default     => http_response_code(404),
    };

} elseif ($controller === 'atendimentos') {

    exigirAutenticacao();
    require_once __DIR__ . '/app/Controllers/AtendimentosController.php';
    $ctrl = new AtendimentosController($pdo);

    match ($action) {
        'listar'          => $ctrl->listar(),
        'criar'           => $ctrl->criar(),
        'alterarStatus', 'atualizarStatus' => $ctrl->alterarStatus(),
        'visualizar'      => $ctrl->visualizar(),
        default           => http_response_code(404),
    };

} elseif ($controller === 'dashboard') {

    exigirAutenticacao();
    require_once __DIR__ . '/app/Controllers/DashboardController.php';
    $ctrl = new DashboardController($pdo);

    match ($action) {
        'resumo' => $ctrl->resumo(),
        default  => http_response_code(404),
    };

} elseif ($controller === 'frontend') {

    exigirAutenticacao();
    require_once __DIR__ . '/app/Controllers/FrontendController.php';
    $ctrl = new FrontendController(); // Geralmente o frontend não precisa de PDO direto

    match ($action) {
        'pessoas'      => $ctrl->pessoas(),
        'tipos'        => $ctrl->tiposAtendimentos(),
        'atendimentos' => $ctrl->atendimentos(),
        default        => http_response_code(404),
    };

} else {
    http_response_code(404);
    echo '<h1>404 — Rota não encontrada</h1>';
}