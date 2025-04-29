<?php
// Lógica PHP no topo para processar o formulário ANTES de qualquer HTML

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cadastrar_funcionario'])) {
    // 1. Coleta e Sanitização/Validação de Dados
    // filter_input é mais seguro que acessar $_POST diretamente
    $nome = filter_input(INPUT_POST, 'nome', FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL); // Valida formato
    $departamento = filter_input(INPUT_POST, 'departamento', FILTER_SANITIZE_SPECIAL_CHARS);

    // 2. Validação Adicional no Backend (Regras de Negócio)
    $erros = []; // Array para acumular mensagens de erro
    if (empty($nome)) $erros[] = "Nome é obrigatório."; // Verifica se está vazio
    if (!$email) $erros[] = "Email inválido ou vazio."; // Verifica se a validação falhou
    if (empty($departamento)) $erros[] = "Departamento é obrigatório.";
    // Poderia adicionar mais validações (ex: tamanho máximo/mínimo)

    // 3. Se não houver erros de validação, tenta inserir no banco
    if (empty($erros)) {
        try {
            // Usar Prepared Statements é OBRIGATÓRIO aqui!
            $sql = "INSERT INTO funcionarios (nome, email, departamento) VALUES (:nome, :email, :departamento)";
            $stmt = $pdo->prepare($sql); // Prepara a instrução SQL

            // Associa os valores das variáveis PHP aos placeholders (:nome, etc.)
            // Especificar o tipo (PDO::PARAM_STR) é uma boa prática, mas muitas vezes opcional para strings.
            $stmt->bindParam(':nome', $nome);
            $stmt->bindParam(':email', $email);
            $stmt->bindParam(':departamento', $departamento);

            $stmt->execute(); // Executa a instrução preparada com os dados seguros

            // Feedback de sucesso e redirecionamento (Padrão PRG)
            setMensagemSucesso("Funcionário cadastrado com sucesso!");
            redirecionar('cadastro_funcionario'); // Redireciona para a mesma página (limpa)

        } catch (PDOException $e) {
            // Tratamento de erro específico para email duplicado (código de erro do MySQL)
            if ($e->getCode() == '23000' || (isset($e->errorInfo[1]) && $e->errorInfo[1] == 1062)) {
                setMensagemErro("Erro: Este email já está cadastrado.");
            } else {
                // Erro genérico do banco
                setMensagemErro("Erro ao cadastrar funcionário. Tente novamente.");
                error_log("Erro DB cad funcionário: " . $e->getMessage()); // Loga detalhes
            }
            redirecionar('cadastro_funcionario'); // Redireciona mesmo com erro
        }
    } else {
        // Se houveram erros de validação, mostra-os
        setMensagemErro("Falha na validação:<br>" . implode("<br>", $erros));
        redirecionar('cadastro_funcionario'); // Redireciona para exibir erros
    }
} // Fim do processamento POST
?>

<h2 class="mb-4"><i class="fas fa-user-plus me-2"></i> Cadastro de Funcionário</h2>

<form method="post" action="index.php?pagina=cadastro_funcionario">
    <div class="mb-3">
        <label for="nome" class="form-label">Nome Completo</label>
        <input type="text" class="form-control" id="nome" name="nome" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" class="form-control" id="email" name="email" required placeholder="exemplo@dominio.com">
    </div>
    <div class="mb-3">
        <label for="departamento" class="form-label">Departamento</label>
        <input type="text" class="form-control" id="departamento" name="departamento" required>
    </div>
    <button type="submit" name="cadastrar_funcionario" class="btn btn-primary">
       <i class="fas fa-save me-1"></i> Cadastrar
    </button>
</form>
