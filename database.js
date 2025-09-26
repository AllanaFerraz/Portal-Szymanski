// Simulação de banco de dados usando LocalStorage

function initDatabase() {
    // Verificar se já existe dados de usuários
    if (!localStorage.getItem('portal_usuarios')) {
        // Criar usuário admin padrão
        const usuarios = [
            {
                id: 1,
                email: "admin@szymanski.com",
                senha: "123456", // Em produção, isso seria hash
                tipo_usuario: "outro",
                nome_completo: "Administrador do Sistema",
                ativo: true,
                data_criacao: new Date().toISOString()
            }
        ];
        localStorage.setItem('portal_usuarios', JSON.stringify(usuarios));
    }
    
    // Inicializar outros dados se necessário
    if (!localStorage.getItem('portal_logs')) {
        localStorage.setItem('portal_logs', JSON.stringify([]));
    }
}

// Funções para gerenciar usuários
function adicionarUsuario(usuario) {
    const usuarios = JSON.parse(localStorage.getItem('portal_usuarios'));
    usuario.id = usuarios.length > 0 ? Math.max(...usuarios.map(u => u.id)) + 1 : 1;
    usuario.data_criacao = new Date().toISOString();
    usuario.ativo = true;
    
    usuarios.push(usuario);
    localStorage.setItem('portal_usuarios', JSON.stringify(usuarios));
    return usuario.id;
}

function buscarUsuarioPorEmail(email) {
    const usuarios = JSON.parse(localStorage.getItem('portal_usuarios'));
    return usuarios.find(usuario => usuario.email === email && usuario.ativo);
}

function buscarUsuarioPorId(id) {
    const usuarios = JSON.parse(localStorage.getItem('portal_usuarios'));
    return usuarios.find(usuario => usuario.id === id && usuario.ativo);
}

function listarUsuarios() {
    return JSON.parse(localStorage.getItem('portal_usuarios'));
}

function adicionarLog(usuarioId, acao) {
    const logs = JSON.parse(localStorage.getItem('portal_logs'));
    logs.push({
        id: logs.length + 1,
        usuario_id: usuarioId,
        acao: acao,
        data: new Date().toISOString(),
        ip: '127.0.0.1' // Simulado
    });
    localStorage.setItem('portal_logs', JSON.stringify(logs));
}
