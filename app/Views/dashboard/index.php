<?php
$tituloPagina = 'Dashboard';
require __DIR__ . '/../layouts/header.php';
?>

<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <h1 class="h3 mb-1">Dashboard</h1>
        <p class="text-secondary mb-0">Resumo simples para validar a integração com o backend.</p>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-secondary small">Pessoas cadastradas</div>
                <div class="display-6 fw-semibold" id="totalPessoas">...</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-secondary small">Tipos de atendimento</div>
                <div class="display-6 fw-semibold" id="totalTipos">...</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card border-0 shadow-sm h-100">
            <div class="card-body">
                <div class="text-secondary small">Atendimentos registrados</div>
                <div class="display-6 fw-semibold" id="totalAtendimentos">...</div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    const targets = {
        pessoas: document.getElementById('totalPessoas'),
        tipos: document.getElementById('totalTipos'),
        atendimentos: document.getElementById('totalAtendimentos')
    };
    for (const [controller, element] of Object.entries(targets)) {
        try {
            const response = await AtendeLabApi.get(controller, 'listar');
            element.textContent = AtendeLabApi.toList(response).length;
        } catch (error) {
            element.textContent = '!';
        }
    }
});
</script>

<?php require __DIR__ . '/../layouts/footer.php'; ?>