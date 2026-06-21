<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AtendeLab — Login</title>
    <link
        rel="stylesheet"
        href="[cdn.jsdelivr.net](https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css)"
    >
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card shadow" style="width: 100%; max-width: 400px;">
        <div class="card-body p-4">

            <h4 class="card-title text-center mb-4">AtendeLab</h4>

            <?php if (!empty($mensagem)): ?>
                <div class="alert alert-<?= htmlspecialchars($tipoMensagem, ENT_QUOTES, 'UTF-8') ?> alert-dismissible fade show" role="alert">
                    <?= htmlspecialchars($mensagem, ENT_QUOTES, 'UTF-8') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <form method="POST" action="<?= BASE_URL ?>?controller=auth&action=entrar" novalidate>

                <div class="mb-3">
                    <label for="email" class="form-label">E-mail</label>
                    <input
                        type="email"
                        class="form-control"
                        id="email"
                        name="email"
                        required
                        autofocus
                        placeholder="admin@atendelab.com"
                    >
                </div>

                <div class="mb-4">
                    <label for="senha" class="form-label">Senha</label>
                    <input
                        type="password"
                        class="form-control"
                        id="senha"
                        name="senha"
                        required
                        placeholder="••••••"
                    >
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-primary">Entrar</button>
                </div>

            </form>

        </div>
    </div>
</div>

<script src="[cdn.jsdelivr.net](https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js)"></script>
</body>
</html>
