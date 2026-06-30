<?php

require_once __DIR__ . '/../../app/Middleware/auth.php';

class AuthController
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function exibirLogin(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if (usuarioAutenticado()) {
            header('Location: ' . BASE_URL . '?controller=auth&action=dashboard');
            exit;
        }

        // Apenas carrega a view. Deixamos a própria view ler e limpar as mensagens.
        require_once __DIR__ . '/../Views/auth/login.php';
    }

    public function entrar(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '?controller=auth&action=login');
            exit;
        }

        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';
        $erroGenerico = 'E-mail ou senha inválidos.';

        // Validação básica do formato de e-mail
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($senha)) {
            $_SESSION['erro_login'] = $erroGenerico; // Alinhado com o login.php
            header('Location: ' . BASE_URL . '?controller=auth&action=login');
            exit;
        }

        // Consulta segura com PDO (prepared statement)
        $stmt = $this->pdo->prepare(
            'SELECT id, nome, email, senha, perfil, status FROM usuarios WHERE email = :email LIMIT 1'
        );
        $stmt->bindValue(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$usuario) {
            $_SESSION['erro_login'] = $erroGenerico; // Alinhado com o login.php
            header('Location: ' . BASE_URL . '?controller=auth&action=login');
            exit;
        }

        // Bloqueia usuários inativos (RN09)
        if ($usuario['status'] !== 'ativo') {
            $_SESSION['erro_login'] = 'Sua conta está inativa. Contate o administrador.';
            header('Location: ' . BASE_URL . '?controller=auth&action=login');
            exit;
        }

        // Verifica a senha contra o hash armazenado (RNF05)
        if (!password_verify($senha, $usuario['senha'])) {
            $_SESSION['erro_login'] = $erroGenerico; // Alinhado com o login.php
            header('Location: ' . BASE_URL . '?controller=auth&action=login');
            exit;
        }

        // Troca o ID de sessão após autenticação (prevenção de session fixation)
        session_regenerate_id(true);

        // Armazena apenas dados essenciais — senha nunca vai para a sessão
        $_SESSION['usuario'] = [
            'id'    => $usuario['id'],
            'nome'  => $usuario['nome'],
            'email' => $usuario['email'],
            'perfil'=> $usuario['perfil'],
        ];

        header('Location: ' . BASE_URL . '?controller=auth&action=dashboard');
        exit;
    }

    public function dashboard(): void
    {
        exigirAutenticacao();
        $usuario = usuarioAtual();
        require_once __DIR__ . '/../Views/dashboard/index.php';
    }

    public function logout(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $_SESSION = [];

        if (ini_get('session.use_cookies')) {
            $params = session_get_cookie_params();
            setcookie(
                session_name(),
                '',
                time() - 42000,
                $params['path'],
                $params['domain'],
                $params['secure'],
                $params['httponly']
            );
        }

        session_destroy();

        session_start();
        $_SESSION['mensagem'] = 'Sessão encerrada com sucesso.';

        header('Location: ' . BASE_URL . '?controller=auth&action=login');
        exit;
    }
}