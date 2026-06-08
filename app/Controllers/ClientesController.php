<?php

class ClientesController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function listar()
    {
        header('Content-Type: application/json');

        $sql = "SELECT * FROM clientes";
        $stmt = $this->pdo->query($sql);

        echo json_encode(
            $stmt->fetchAll(PDO::FETCH_ASSOC),
            JSON_PRETTY_PRINT
        );
    }

    public function cadastrar()
    {
        $nome = $_POST['nome'] ?? '';
        $telefone = $_POST['telefone'] ?? '';

        $sql = "INSERT INTO clientes(nome, telefone)
                VALUES(:nome, :telefone)";

        $stmt = $this->pdo->prepare($sql);

        $stmt->execute([
            ':nome' => $nome,
            ':telefone' => $telefone
        ]);

        echo json_encode([
            "mensagem" => "Cliente cadastrado com sucesso"
        ]);
    }
}