<?php

class FrontendController
{
    public function pessoas(): void
    {
        // Abre a tela visual de gerenciamento de pessoas
        require_once __DIR__ . '/../Views/pessoas/index.php';
    }

    public function tiposAtendimentos(): void
    {
        // Abre a tela visual de tipos de atendimento
        require_once __DIR__ . '/../Views/tipos-atendimentos/index.php';
    }

    public function atendimentos(): void
    {
        // Abre a tela visual de registro de atendimentos
        require_once __DIR__ . '/../Views/atendimentos/index.php';
    }
}