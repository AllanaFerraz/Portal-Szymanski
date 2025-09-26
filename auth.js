// Sistema de autenticação usando SessionStorage

function loginUsuario(email, senha, tipoUsuario) {
    const usuario = buscarUsuarioPorEmail(email);
    
    if (usuario && usuario.senha === senha && usuario.tipo_usuario === tipoUsuario) {
        // Salvar sessão (não usar localStorage para sessão!)
        sessionStorage.setItem('usuario_logado', JSON.stringify({
            id: usuario.id,
            email: usuario.email,
            nome: usuario.nome_completo,
            tipo: usuario.tipo_usuario,
            login_time: new Date().toISOString()
        }));
        
        // Registrar log
        adicionarLog(usuario.id, 'Login realizado');
        
        return true;
    }
    return false;
}

function logoutUsuario() {
    const usuario = getUsuarioLogado();
    if (usuario) {
        adicionarLog(usuario.id, 'Logout realizado');
    }
    sessionStorage.removeItem('usuario_logado');
    window.location.href = 'index.html';
}

function getUsuarioLogado() {
    const usuarioData = sessionStorage.getItem('usuario_logado');
    return usuarioData ? JSON.parse(usuarioData) : null;
}

function verificarAutenticacao() {
    const usuario = getUsuarioLogado();
    if (!usuario) {
        window.location.href = 'index.html';
        return false;
    }
    return usuario;
}

function cadastrarUsuario(dados) {
    // Verificar se email já existe
    if (buscarUsuarioPorEmail(dados.email)) {
        return { success: false, message: 'Email já cadastrado!' };
    }
    
    // Adicionar usuário
    const usuarioId = adicionarUsuario({
        email: dados.email,
        senha: dados.senha, // Em app real, faríamos hash aqui
        tipo_usuario: dados.tipo_usuario,
        nome_completo: dados.nome_completo
    });
    
    adicionarLog(usuarioId, 'Cadastro realizado');
    
    return { success: true, message: 'Cadastro realizado com sucesso!' };
}
