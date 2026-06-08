<?php

class UsuariosController
{
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function listar()
    {
        header('Content-Type: application/json');

        $sql = "SELECT * FROM usuarios";
        $stmt = $this->pdo->query($sql);
        $usuarios = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode($usuarios, JSON_PRETTY_PRINT);
    }

    public function cadastrar()
    {
        header('Content-Type: application/json; charset=utf-8');

        $nome = $_POST['nome'] ?? '';
        $email = $_POST['email'] ?? '';
        $senha = $_POST['senha'] ?? '';

        if (empty($nome) || empty($email) || empty($senha)) {
            echo json_encode([
                "erro" => "Nome, email e senha são obrigatórios"
            ]);
            return;
        }

        $senhaHash = password_hash($senha, PASSWORD_DEFAULT);

        $sql = "INSERT INTO usuarios (nome, email, senha)
                VALUES (:nome, :email, :senha)";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', $senhaHash);

        if ($stmt->execute()) {
            echo json_encode([
                "mensagem" => "Usuário cadastrado com sucesso",
                "id" => $this->pdo->lastInsertId()
            ]);
        } else {
            echo json_encode([
                "erro" => "Erro ao cadastrar usuário"
            ]);
        }
    }

    public function atualizar()
    {
        header('Content-Type: application/json');

        $id = $_POST['id'] ?? '';
        $nome = $_POST['nome'] ?? '';
        $email = $_POST['email'] ?? '';

        if (empty($id) || empty($nome) || empty($email)) {
            echo json_encode([
                "erro" => "ID, nome e email são obrigatórios"
            ]);
            return;
        }

        $sql = "UPDATE usuarios
                SET nome = :nome,
                    email = :email
                WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);

        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);

        if ($stmt->execute()) {
            echo json_encode([
                "mensagem" => "Usuário atualizado com sucesso"
            ]);
        } else {
            echo json_encode([
                "erro" => "Erro ao atualizar usuário"
            ]);
        }
    }

    public function excluir()
{
    header('Content-Type: application/json');

    $id = $_POST['id'] ?? '';

    if (empty($id)) {
        echo json_encode([
            "erro" => "ID é obrigatório"
        ]);
        return;
    }

    $sql = "DELETE FROM usuarios WHERE id = :id";

    $stmt = $this->pdo->prepare($sql);

    $stmt->bindParam(':id', $id);

    if ($stmt->execute()) {
        echo json_encode([
            "mensagem" => "Usuário excluído com sucesso"
        ]);
    } else {
        echo json_encode([
            "erro" => "Erro ao excluir usuário"
        ]);
    }
}
}