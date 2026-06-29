<?php

class UsuariosController
{
    private PDO $pdo;

    public function __construct(PDO $pdo = null)
    {
        if ($pdo === null) {
            require __DIR__ . '/../../config/database.php';
        }
        $this->pdo = $pdo ?? $GLOBALS['pdo'];
    }

    public function index(): void
    {
        echo '<h3>Página de Usuários (Rota antiga da Aula 02)</h3>';
        echo '<a href="?controller=auth&action=dashboard">Voltar para o Dashboard</a>';
    }
    
    // Métodos vazios só para não dar erro no routes.php
    public function criar(){}
    public function salvar(){}
    public function editar(){}
    public function atualizar(){}
    public function excluir(){}
}