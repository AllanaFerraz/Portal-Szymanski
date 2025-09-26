<?php
session_start();
require_once "config.php";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $tipo_usuario = $_POST['tipo_usuario'];

    $database = new Database();
    $db = $database->getConnection();

    // Buscar usuário
    $query = "SELECT * FROM usuarios WHERE email = :email AND tipo_usuario = :tipo AND ativo = 1";
    $stmt = $db->prepare($query);
    $stmt->bindParam(":email", $email);
    $stmt->bindParam(":tipo", $tipo_usuario);
    $stmt->execute();

    if ($stmt->rowCount() == 1) {
        $usuario = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if (password_verify($senha, $usuario['senha_hash'])) {
            // Login bem-sucedido
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['email'] = $usuario['email'];
            $_SESSION['tipo_usuario'] = $usuario['tipo_usuario'];
            $_SESSION['nome'] = $usuario['nome_completo'];

            // Atualizar último login
            $update_query = "UPDATE usuarios SET ultimo_login = NOW() WHERE id = :id";
            $update_stmt = $db->prepare($update_query);
            $update_stmt->bindParam(":id", $usuario['id']);
            $update_stmt->execute();

            // Registrar log de acesso
            $ip = $_SERVER['REMOTE_ADDR'];
            $user_agent = $_SERVER['HTTP_USER_AGENT'];
            
            $log_query = "INSERT INTO logs_acesso (usuario_id, tipo_usuario, ip_address, user_agent) 
                         VALUES (:usuario_id, :tipo, :ip, :agent)";
            $log_stmt = $db->prepare($log_query);
            $log_stmt->bindParam(":usuario_id", $usuario['id']);
            $log_stmt->bindParam(":tipo", $usuario['tipo_usuario']);
            $log_stmt->bindParam(":ip", $ip);
            $log_stmt->bindParam(":agent", $user_agent);
            $log_stmt->execute();

            // Redirecionar para home
            header("Location: home.php");
            exit();
        } else {
            $erro = "Senha incorreta!";
        }
    } else {
        $erro = "Usuário não encontrado!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Portal Szymanski</title>
    <style>
        .login-container {
            max-width: 400px;
            margin: 50px auto;
            padding: 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        
        input, select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }
        
        .btn-login {
            width: 100%;
            padding: 12px;
            background: linear-gradient(to right, #1a2a6c, #b21f1f);
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        
        .error {
            color: red;
            text-align: center;
            margin-bottom: 15px;
        }
        
        .user-type-selector {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
            margin-bottom: 20px;
        }
        
        .user-type-option {
            padding: 15px;
            text-align: center;
            border: 2px solid #ddd;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .user-type-option.selected {
            border-color: #1a2a6c;
            background-color: #f0f8ff;
        }
        
        .user-type-option input {
            display: none;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2 style="text-align: center; color: #1a2a6c;">Login Portal Szymanski</h2>
        
        <?php if (isset($erro)): ?>
            <div class="error"><?php echo $erro; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="user-type-selector">
                <label class="user-type-option">
                    <input type="radio" name="tipo_usuario" value="aluno" required>
                    <div>👨‍🎓 Aluno</div>
                </label>
                <label class="user-type-option">
                    <input type="radio" name="tipo_usuario" value="responsavel">
                    <div>👨‍👩‍👧‍👦 Responsável</div>
                </label>
                <label class="user-type-option">
                    <input type="radio" name="tipo_usuario" value="visitante">
                    <div>👤 Visitante</div>
                </label>
                <label class="user-type-option">
                    <input type="radio" name="tipo_usuario" value="outro">
                    <div>🔧 Outro</div>
                </label>
            </div>
            
            <div class="form-group">
                <label>Email:</label>
                <input type="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label>Senha:</label>
                <input type="password" name="senha" required>
            </div>
            
            <button type="submit" class="btn-login">Entrar</button>
        </form>
        
        <p style="text-align: center; margin-top: 20px;">
            <a href="cadastro.php">Não tem conta? Cadastre-se</a>
        </p>
    </div>

    <script>
        // Script para seleção visual dos tipos de usuário
        document.querySelectorAll('.user-type-option').forEach(option => {
            option.addEventListener('click', function() {
                document.querySelectorAll('.user-type-option').forEach(opt => {
                    opt.classList.remove('selected');
                });
                this.classList.add('selected');
                this.querySelector('input').checked = true;
            });
        });
    </script>
</body>
</html>
