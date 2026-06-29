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
        if (usuarioAutenticado()) {
            header('Location: ' . BASE_URL . '?controller=auth&action=dashboard');
            exit;
        }

        $mensagem     = $_SESSION['mensagem']     ?? null;
        $tipoMensagem = $_SESSION['tipo_mensagem'] ?? 'danger';

        unset($_SESSION['mensagem'], $_SESSION['tipo_mensagem']);

        require_once __DIR__ . '/../Views/auth/login.php';
    }

    public function entrar(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '?controller=auth&action=login');
            exit;
        }

        $email = trim($_POST['email'] ?? '');
        $senha = $_POST['senha'] ?? '';

        // Validação básica do formato de e-mail
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || empty($senha)) {
            $_SESSION['mensagem']     = 'E-mail ou senha inválidos.';
            $_SESSION['tipo_mensagem'] = 'danger';
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

        // Mensagem genérica para não expor quais e-mails existem
        $erroGenerico = 'E-mail ou senha inválidos.';

        if (!$usuario) {
            $_SESSION['mensagem']     = $erroGenerico;
            $_SESSION['tipo_mensagem'] = 'danger';
            header('Location: ' . BASE_URL . '?controller=auth&action=login');
            exit;
        }

        // Bloqueia usuários inativos (RN09)
        if ($usuario['status'] !== 'ativo') {
            $_SESSION['mensagem']     = $erroGenerico;
            $_SESSION['tipo_mensagem'] = 'danger';
            header('Location: ' . BASE_URL . '?controller=auth&action=login');
            exit;
        }

        // Verifica a senha contra o hash armazenado (RNF05)
        if (!password_verify($senha, $usuario['senha'])) {
            $_SESSION['mensagem']     = $erroGenerico;
            $_SESSION['tipo_mensagem'] = 'danger';
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
            'perfil' => $usuario['perfil'],
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
        // Limpa todos os dados da sessão
        $_SESSION = [];

        // Remove o cookie de sessão do navegador
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

        // Reinicia a sessão para poder enviar a mensagem de feedback
        session_start();
        $_SESSION['mensagem']     = 'Sessão encerrada com sucesso.';
        $_SESSION['tipo_mensagem'] = 'success';

        header('Location: ' . BASE_URL . '?controller=auth&action=login');
        exit;
    }
}
