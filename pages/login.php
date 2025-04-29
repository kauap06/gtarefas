<?php
// Se o usuário já estiver logado, redireciona para a home
if (isset($_SESSION['usuario_id'])) {
    redirecionar('home');
}

// Lógica de processamento do formulário de login
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['login'])) {
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
    $senha = $_POST['senha'];

    $erros = [];
    if (!$email) $erros[] = "Email inválido ou vazio.";
    if (empty($senha)) $erros[] = "Senha é obrigatória.";

    if (empty($erros)) {
        try {
            $sql = "SELECT id, nome, email, senha FROM usuarios WHERE email = :email";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':email', $email);
            $stmt->execute();
            $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

            // Verifica se o usuário existe E se a senha está correta
            if ($usuario && password_verify($senha, $usuario['senha'])) {
                // ---- LOGIN BEM-SUCEDIDO ----
                // Armazena informações do usuário na SESSÃO
                $_SESSION['usuario_id'] = $usuario['id'];
                $_SESSION['usuario_nome'] = $usuario['nome'];
                $_SESSION['usuario_email'] = $usuario['email'];

                // Redireciona para a página principal após o login
                redirecionar('home');

            } else {
                // Usuário não encontrado ou senha incorreta
                setMensagemErro("Email ou senha inválidos.");
                redirecionar('login');
            }

        } catch (PDOException $e) {
            setMensagemErro("Erro ao tentar fazer login: " . $e->getMessage());
            error_log("Erro DB login: " . $e->getMessage());
            redirecionar('login');
        }
    } else {
        setMensagemErro(implode("<br>", $erros));
        redirecionar('login');
    }
}
?>

<div class="row justify-content-center">
    <div class="col-md-6 col-lg-4">
        <h2 class="mb-4 text-center"><i class="fas fa-sign-in-alt me-2"></i> Login</h2>

        <form method="post" action="index.php?pagina=login">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="senha" class="form-label">Senha</label>
                <input type="password" class="form-control" id="senha" name="senha" required>
            </div>
            <div class="d-grid">
                 <button type="submit" name="login" class="btn btn-primary">
                   <i class="fas fa-sign-in-alt me-1"></i> Entrar
                 </button>
            </div>
             <p class="mt-3 text-center">Não tem uma conta? <a href="index.php?pagina=registro">Registre-se aqui!</a></p>
        </form>
    </div>
</div>
