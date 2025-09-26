<?php
session_start();
require_once "config.php";

// Verificar se usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: index.php");
    exit();
}

$database = new Database();
$db = $database->getConnection();

// Buscar informações do usuário
$query = "SELECT * FROM usuarios WHERE id = :id";
$stmt = $db->prepare($query);
$stmt->bindParam(":id", $_SESSION['usuario_id']);
$stmt->execute();
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portal Szymanski - Home</title>
    <!-- Incluir o CSS do seu portal original aqui -->
</head>
<body>
    <!-- Header do seu portal original -->
    <header>
        <!-- ... código do header ... -->
        
        <!-- Adicionar informações do usuário logado -->
        <div style="position: absolute; top: 10px; right: 20px; color: white;">
            Olá, <?php echo $_SESSION['nome']; ?> (<?php echo $_SESSION['tipo_usuario']; ?>)
            <a href="logout.php" style="color: white; margin-left: 15px;">Sair</a>
        </div>
    </header>

    <!-- Conteúdo específico baseado no tipo de usuário -->
    <div class="container">
        <?php if ($_SESSION['tipo_usuario'] == 'aluno'): ?>
            <!-- Conteúdo para alunos -->
            <h2>Área do Aluno</h2>
            <p>Bem-vindo, estudante!</p>
            
        <?php elseif ($_SESSION['tipo_usuario'] == 'responsavel'): ?>
            <!-- Conteúdo para responsáveis -->
            <h2>Área do Responsável</h2>
            <p>Acompanhe o desempenho do seu aluno</p>
            
        <?php else: ?>
            <!-- Conteúdo padrão -->
            <h2>Bem-vindo ao Portal Szymanski!</h2>
        <?php endif; ?>
        
        <!-- Resto do conteúdo do portal -->
    </div>
</body>
</html>
