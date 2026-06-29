<?php

class DashboardController
{
    private PDO $pdo;

    public function __construct(PDO $pdo = null)
    {
        if ($pdo === null) {
            require __DIR__ . '/../../config/database.php';
        }
        $this->pdo = $pdo ?? $GLOBALS['pdo'];
    }

    public function resumo(): void
    {
        // Conta quantos registos existem em cada tabela
        $totalPessoas = $this->pdo->query("SELECT COUNT(*) FROM pessoas")->fetchColumn();
        $totalTipos = $this->pdo->query("SELECT COUNT(*) FROM tipos_atendimentos")->fetchColumn();
        $totalAtendimentos = $this->pdo->query("SELECT COUNT(*) FROM atendimentos")->fetchColumn();

        http_response_code(200);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([
            'indicadores' => [
                'total_pessoas' => $totalPessoas,
                'total_tipos' => $totalTipos,
                'total_atendimentos' => $totalAtendimentos
            ]
        ]);
    }
}