<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>AtendeLab — Dashboard</title>
    <link
        rel="stylesheet"
        href="[cdn.jsdelivr.net](https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css)"
    >
</head>
<body>

<nav class="navbar navbar-dark bg-primary mb-4">
    <div class="container">
        <span class="navbar-brand">AtendeLab</span>
        <a href="<?= BASE_URL ?>?controller=auth&action=logout" class="btn btn-outline-light btn-sm">
            Sair
        </a>
    </div>
</nav>

<div class="container">

    <div class="alert alert-success">
        Bem-vindo, <strong><?= htmlspecialchars($usuario['nome'], ENT_QUOTES, 'UTF-8') ?></strong>!
        Perfil: <strong><?= htmlspecialchars($usuario['perfil'], ENT_QUOTES, 'UTF-8') ?></strong>
    </div>

    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title">Área restrita</h5>
            <p class="card-text text-muted">
                Você está autenticado. Esta página só é acessível com sessão válida.
            </p>
            <a
                href="<?= BASE_URL ?>?controller=usuarios&action=index"
                class="btn btn-secondary btn-sm"
            >
                Testar rota protegida de usuários
            </a>
        </div>
    </div>

</div>

<script src="[cdn.jsdelivr.net](https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js)"></script>
</body>
</html>
