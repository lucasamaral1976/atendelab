<?php
$tituloPagina = 'Pessoas atendidas';
require __DIR__ . '/../layouts/header.php';
?>
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <h1 class="h3 mb-1">Pessoas atendidas</h1>
        <p class="text-secondary mb-0">Cadastro, edição e inativação sem excluir o histórico.</p>
    </div>
    <button class="btn btn-success" type="button" onclick="novaPessoa()">Nova pessoa</button>
</div>

<div id="alerta"></div>

<div class="card border-0 shadow-sm mb-4 d-none" id="cardFormulario">
    <div class="card-body">
        <h2 class="h5" id="tituloFormulario">Nova pessoa</h2>
        <form id="formPessoa">
            <input type="hidden" name="id" id="pessoaId">
            <div class="row g-3">
                <div class="col-md-6"><label class="form-label">Nome *</label><input class="form-control" name="nome" id="campoNome" required></div>
                <div class="col-md-3"><label class="form-label">Documento *</label><input class="form-control" name="documento" id="campoDocumento" required></div>
                <div class="col-md-3"><label class="form-label">Telefone</label><input class="form-control" name="telefone" id="campoTelefone"></div>
                <div class="col-md-6"><label class="form-label">E-mail *</label><input class="form-control" type="email" name="email" id="campoEmail" required></div>
                <div class="col-md-3"><label class="form-label">Curso</label><input class="form-control" name="curso" id="campoCurso"></div>
                <div class="col-md-3"><label class="form-label">Período</label><input class="form-control" name="periodo" id="campoPeriodo"></div>
                <div class="col-12"><label class="form-label">Observações</label><textarea class="form-control" name="observacoes" id="campoObservacoes" rows="2"></textarea></div>
                <div class="col-md-3"><label class="form-label">Status</label><select class="form-select" name="status" id="campoStatus">
                    <option value="ativo">Ativo</option>
                    <option value="inativo">Inativo</option>
                </select></div>
            </div>
            <div class="d-flex gap-2 mt-3">
                <button class="btn btn-success" type="submit">Salvar</button>
                <button class="btn btn-outline-secondary" type="button" onclick="fecharFormulario()">Cancelar</button>
            </div>
        </form>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="table-responsive">
        <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr><th>Nome</th><th>Documento</th><th>E-mail</th><th>Curso</th><th>Período</th><th>Status</th><th class="text-end">Ações</th></tr>
            </thead>
            <tbody id="tabelaPessoas">
                <tr><td colspan="7" class="text-center py-4">Carregando...</td></tr>
            </tbody>
        </table>
    </div>
</div>

<script>
const formPessoa = document.getElementById('formPessoa');
const cardFormulario = document.getElementById('cardFormulario');

function abrirFormulario() { cardFormulario.classList.remove('d-none'); window.scrollTo({ top: 0, behavior: 'smooth' }); }
function fecharFormulario() { cardFormulario.classList.add('d-none'); formPessoa.reset(); document.getElementById('pessoaId').value = ''; }

function novaPessoa() { 
    fecharFormulario(); 
    document.getElementById('tituloFormulario').textContent = 'Nova pessoa'; 
    document.getElementById('pessoaId').value = ''; 
    abrirFormulario(); 
}

async function carregarPessoas() {
    try {
        const dados = AtendeLabApi.toList(await AtendeLabApi.get('pessoas', 'listar'));
        const tbody = document.getElementById('tabelaPessoas');
        if (!dados.length) { tbody.innerHTML = '<tr><td colspan="7" class="text-center py-4">Nenhuma pessoa cadastrada.</td></tr>'; return; }
        tbody.innerHTML = dados.map(p => `<tr>
            <td>${AtendeLabApi.escape(p.nome)}</td>
            <td>${AtendeLabApi.escape(p.documento)}</td>
            <td>${AtendeLabApi.escape(p.email)}</td>
            <td>${AtendeLabApi.escape(p.curso || '')}</td>
            <td>${AtendeLabApi.escape(p.periodo || '')}</td>
            <td><span class="badge ${p.status === 'ativo' ? 'text-bg-success' : 'text-bg-secondary'}">${AtendeLabApi.escape(p.status)}</span></td>
            <td class="text-end">
                <button class="btn btn-sm btn-outline-primary" onclick="editarPessoa(${Number(p.id)})">Editar</button> 
                <button class="btn btn-sm btn-outline-danger" onclick="inativarPessoa(${Number(p.id)})">Inativar</button>
            </td>
        </tr>`).join('');
    } catch (error) { AtendeLabApi.showAlert('alerta', error.message, 'danger'); }
}

async function editarPessoa(id) {
    try {
        const resposta = await AtendeLabApi.get('pessoas', 'buscar', {id});
        const lista = AtendeLabApi.toList(resposta);
        const p = lista.length > 0 ? lista[0] : {}; 
        
        document.getElementById('tituloFormulario').textContent = 'Editar pessoa'; 
        abrirFormulario();
        
        // Garante o preenchimento manual do ID oculto e dos inputs
        document.getElementById('pessoaId').value = id;
        if(p.nome) document.getElementById('campoNome').value = p.nome;
        if(p.documento) document.getElementById('campoDocumento').value = p.documento;
        if(p.telefone) document.getElementById('campoTelefone').value = p.telefone;
        if(p.email) document.getElementById('campoEmail').value = p.email;
        if(p.curso) document.getElementById('campoCurso').value = p.curso;
        if(p.periodo) document.getElementById('campoPeriodo').value = p.periodo;
        if(p.observacoes) document.getElementById('campoObservacoes').value = p.observacoes;
        if(p.status) document.getElementById('campoStatus').value = p.status;
        
    } catch (error) { AtendeLabApi.showAlert('alerta', error.message, 'danger'); }
}

formPessoa.addEventListener('submit', async event => {
    event.preventDefault();
    const id = document.getElementById('pessoaId').value;
    // Se tem ID armazendado, vai para a rota 'atualizar', senão cria uma nova
    const acao = id ? 'atualizar' : 'criar';
    try {
        await AtendeLabApi.post('pessoas', acao, new FormData(formPessoa));
        AtendeLabApi.showAlert('alerta', id ? 'Pessoa atualizada com sucesso.' : 'Pessoa cadastrada com sucesso.');
        fecharFormulario(); 
        await carregarPessoas();
    } catch (error) { AtendeLabApi.showAlert('alerta', error.message, 'danger'); }
});

async function inativarPessoa(id) {
    if (!confirm('Deseja inativar esta pessoa?')) return;
    try { 
        await AtendeLabApi.post('pessoas', 'inativar', {id});
        AtendeLabApi.showAlert('alerta', 'Pessoa inativada com sucesso.'); await carregarPessoas(); 
    } catch (error) { AtendeLabApi.showAlert('alerta', error.message, 'danger'); }
}

document.addEventListener('DOMContentLoaded', carregarPessoas);
</script>
<?php require __DIR__ . '/../layouts/footer.php'; ?>