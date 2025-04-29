<?php
// Lógica de processamento do formulário de registro
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['registrar'])) {
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $senha = $_POST['senha']; // Pegamos a senha pura por enquanto
    $confirmar_senha = $_POST['confirmar_senha'];

    $erros = [];
    if (empty($nome)) $erros[] = "Nome é obrigatório.";
    if (!$email) $erros[] = "Email inválido ou vazio.";
    if (empty($senha)) $erros[] = "Senha é obrigatória.";
    if ($senha !== $confirmar_senha) $erros[] = "As senhas não coincidem.";
    if (strlen($senha) < 6) $erros[] = "A senha deve ter pelo menos 6 caracteres."; // Validação básica de força

    // Verificar se o email já existe (precisa da conexão $pdo)
    if (empty($erros)) {
        try {
            $sqlCheck = "SELECT id FROM usuarios WHERE email = :email";
            $stmtCheck = $pdo->prepare($sqlCheck);
            $stmtCheck->bindParam(':email', $email);
            $stmtCheck->execute();
            if ($stmtCheck->fetch()) {
                $erros[] = "Este email já está cadastrado.";
            }
        } catch (PDOException $e) {
             setMensagemErro("Erro ao verificar email: " . $e->getMessage());
             redirecionar('registro');
        }
    }


    if (empty($erros)) {
        // ---- HASHING DA SENHA ----
        // ESSENCIAL para segurança!
        $senha_hash = password_hash($senha, PASSWORD_DEFAULT);
        // --------------------------

        try {
            $sql = "INSERT INTO usuarios (nome, email, senha) VALUES (:nome, :email, :senha)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':senha', $senha_hash); // Salva o HASH, não a senha original!
            $stmt->execute();

            setMensagemSucesso("Usuário registrado com sucesso! Faça o login.");
            redirecionar('login'); // Redireciona para a página de login

        } catch (PDOException $e) {
            setMensagemErro("Erro ao registrar usuário: " . $e->getMessage());
            error_log("Erro DB reg usuário: " . $e->getMessage());
            redirecionar('registro');
        }
    } else {
        setMensagemErro(implode("<br>", $erros));
        redirecionar('registro');
    }
}
?>

<h2 class="mb-4"><i class="fas fa-user-plus me-2"></i> Registrar Novo Usuário</h2>

<form method="post" action="index.php?pagina=registro">
    <div class="mb-3">
        <label for="nome" class="form-label">Nome Completo</label>
        <input type="text" class="form-control" id="nome" name="nome" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email (será seu login)</label>
        <input type="email" class="form-control" id="email" name="email" required placeholder="seuemail@dominio.com">
    </div>
    <div class="mb-3">
        <label for="senha" class="form-label">Senha</label>
        <input type="password" class="form-control" id="senha" name="senha" required>
    </div>
     <div class="mb-3">
        <label for="confirmar_senha" class="form-label">Confirmar Senha</label>
        <input type="password" class="form-control" id="confirmar_senha" name="confirmar_senha" required>
    </div>
    <button type="submit" name="registrar" class="btn btn-primary">
       <i class="fas fa-check-circle me-1"></i> Registrar
    </button>
     <p class="mt-3">Já tem uma conta? <a href="index.php?pagina=login">Faça login aqui!</a></p>
</form>
