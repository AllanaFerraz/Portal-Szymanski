<?php
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $database = new Database();
    $db = $database->getConnection();

    $nome = $_POST['nome_completo'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $tipo_usuario = $_POST['tipo_usuario'];
    $confirmar_senha = $_POST['confirmar_senha'];

    // Validações
    if ($senha !== $confirmar_senha) {
        $erro = "As senhas não coincidem!";
    } else {
        // Verificar se email já existe
        $check_query = "SELECT id FROM usuarios WHERE email = :email";
        $check_stmt = $db->prepare($check_query);
        $check_stmt->bindParam(":email", $email);
        $check_stmt->execute();

        if ($check_stmt->rowCount() > 0) {
            $erro = "Este email já está cadastrado!";
        } else {
            // Inserir novo usuário
            $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
            
            $insert_query = "INSERT INTO usuarios (nome_completo, email, senha_hash, tipo_usuario) 
                            VALUES (:nome, :email, :senha, :tipo)";
            $insert_stmt = $db->prepare($insert_query);
            $insert_stmt->bindParam(":nome", $nome);
            $insert_stmt->bindParam(":email", $email);
            $insert_stmt->bindParam(":senha", $senha_hash);
            $insert_stmt->bindParam(":tipo", $tipo_usuario);

            if ($insert_stmt->execute()) {
                $sucesso = "Cadastro realizado com sucesso!";
                
                // Se for aluno ou responsável, inserir na tabela específica
                $usuario_id = $db->lastInsertId();
                
                if ($tipo_usuario == 'aluno') {
                    $matricula = $_POST['matricula'];
                    $insert_aluno = "INSERT INTO alunos (usuario_id, matricula) VALUES (:usuario_id, :matricula)";
                    $stmt_aluno = $db->prepare($insert_aluno);
                    $stmt_aluno->bindParam(":usuario_id", $usuario_id);
                    $stmt_aluno->bindParam(":matricula", $matricula);
                    $stmt_aluno->execute();
                } elseif ($tipo_usuario == 'responsavel') {
                    $insert_resp = "INSERT INTO responsaveis (usuario_id) VALUES (:usuario_id)";
                    $stmt_resp = $db->prepare($insert_resp);
                    $stmt_resp->bindParam(":usuario_id", $usuario_id);
                    $stmt_resp->execute();
                }
            } else {
                $erro = "Erro ao cadastrar usuário!";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Portal Szymanski</title>
    <style>
        /* Estilos similares ao login */
        .cadastro-container {
            max-width: 500px;
            margin: 30px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .campo-dinamico {
            display: none;
        }
    </style>
</head>
<body>
    <div class="cadastro-container">
        <h2 style="text-align: center; color: #1a2a6c;">Cadastro Portal Szymanski</h2>
        
        <?php if (isset($erro)): ?>
            <div class="error"><?php echo $erro; ?></div>
        <?php endif; ?>
        
        <?php if (isset($sucesso)): ?>
            <div style="color: green; text-align: center; margin-bottom: 15px;">
                <?php echo $sucesso; ?>
                <br><a href="index.php">Fazer login</a>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="form-group">
                <label>Tipo de Usuário:</label>
                <select name="tipo_usuario" id="tipo_usuario" required onchange="mostrarCamposEspecificos()">
                    <option value="">Selecione...</option>
                    <option value="aluno">Aluno</option>
                    <option value="responsavel">Responsável</option>
                    <option value="visitante">Visitante</option>
                    <option value="outro">Outro</option>
                </select>
            </div>
            
            <div id="campo-aluno" class="campo-dinamico">
                <div class="form-group">
                    <label>Matrícula:</label>
                    <input type="text" name="matricula">
                </div>
            </div>
            
            <div class="form-group">
                <label>Nome Completo:</label>
                <input type="text" name="nome_completo" required>
            </div>
            
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label>Senha:</label>
                <input type="password" name="senha" required minlength="6">
            </div>
            
            <div class="form-group">
                <label>Confirmar Senha:</label>
                <input type="password" name="confirmar_senha" required>
            </div>
            
            <button type="submit" class="btn-login">Cadastrar</button>
        </form>
    </div>

    <script>
        function mostrarCamposEspecificos() {
            const tipo = document.getElementById('tipo_usuario').value;
            
            // Esconder todos os campos dinâmicos
            document.querySelectorAll('.campo-dinamico').forEach(campo => {
                campo.style.display = 'none';
            });
            
            // Mostrar campo específico se necessário
            if (tipo === 'aluno') {
                document.getElementById('campo-aluno').style.display = 'block';
            }
        }
    </script>
</body>
</html>
