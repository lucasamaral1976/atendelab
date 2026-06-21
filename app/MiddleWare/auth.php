<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function usuarioAutenticado(): bool
{
    return isset($_SESSION['usuario']) && !empty($_SESSION['usuario']);
}

function exigirAutenticacao(): void
{
    if (!usuarioAutenticado()) {
        $_SESSION['mensagem'] = 'Faça login para acessar a área restrita.';
        $_SESSION['tipo_mensagem'] = 'warning';
        header('Location: ' . BASE_URL . '?controller=auth&action=login');
        exit;
    }
}

function usuarioAtual(): array
{
    return $_SESSION['usuario'] ?? [];
}
