window.AtendeLabApi = (() => {
    const baseUrl = '/atendelab/public/';
    async function request(controller, action, method = 'GET', query = {}, body = null) {
        const params = new URLSearchParams({ controller, action, ...query });
        const options = { method, credentials: 'same-origin' };
        if (method !== 'GET' && body !== null) {
            const form = body instanceof FormData ? body : objectToFormData(body);
            options.body = new URLSearchParams([...form.entries()]);
            options.headers = { 'Content-Type': 'application/x-www-form-urlencoded; charset=UTF-8' };
        }
        const response = await fetch(`${baseUrl}?${params.toString()}`, options);
        const text = await response.text();
        let data;
        try { data = text ? JSON.parse(text) : {}; }
        catch { throw new Error(text || 'Resposta inválida.'); }
        if (!response.ok || data.erro) throw new Error(data.erro || data.mensagem || `Erro HTTP`);
        return data;
    }
    function objectToFormData(obj) {
        const form = new FormData();
        for (const [key, value] of Object.entries(obj)) form.append(key, String(value ?? ''));
        return form;
    }
    function toList(data) {
        if (Array.isArray(data)) return data;
        for (const key of ['dados', 'items', 'registros', 'pessoas', 'tipos', 'atendimentos', 'usuarios']) {
            if (Array.isArray(data?.[key])) return data[key];
        }
        return [];
    }
    // Função que faltava adicionada aqui:
    function toObject(data) {
        if (typeof data === 'object' && data !== null && !Array.isArray(data)) return data;
        if (Array.isArray(data) && data.length > 0) return data[0];
        return data || {};
    }
    function escape(value) {
        return String(value ?? '').replace(/[&<>'"]/g, char => ({ '&': '&amp;', '<': '&lt;', '>': '&gt;', "'": '&#039;', '"': '&quot;' }[char]));
    }
    function escapeAttr(value) { return escape(value).replace(/"/g, '&quot;'); }
    function showAlert(id, message, type = 'success') {
        const el = document.getElementById(id);
        if (el) el.innerHTML = `<div class="alert alert-${type} alert-dismissible"><button class="btn-close" data-bs-dismiss="alert"></button>${escape(message)}</div>`;
    }
    // toObject exportado no final:
    return { get: (c, a, q = {}) => request(c, a, 'GET', q), post: (c, a, b = null) => request(c, a, 'POST', {}, b), toList, toObject, escape, escapeAttr, showAlert };
})();