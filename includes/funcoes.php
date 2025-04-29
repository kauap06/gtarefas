<?php
// Garante que a sessão seja iniciada se ainda não foi
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

/**
 * Define uma mensagem de sucesso na sessão.
 * @param string $mensagem Mensagem a ser exibida.
 */
function setMensagemSucesso(string $mensagem): void {
    // Armazena a mensagem na sessão, que persiste entre requisições
    $_SESSION['mensagem_sucesso'] = $mensagem;
}

/**
 * Define uma mensagem de erro na sessão.
 * @param string $mensagem Mensagem a ser exibida.
 */
function setMensagemErro(string $mensagem): void {
    $_SESSION['mensagem_erro'] = $mensagem;
}

/**
 * Redireciona o usuário para uma página específica via cabeçalho Location.
 * IMPORTANTE: Deve ser chamada ANTES de qualquer saída HTML.
 * @param string $pagina Nome da página (sem .php, ex: 'home', 'login').
 */
function redirecionar(string $pagina): void {
    // Monta a URL completa para o redirecionamento
    // (Poderia usar $baseUrl calculado no header se fosse passado como parâmetro ou global)
    $url = "index.php?pagina=" . $pagina;
    header("Location: " . $url); // Envia o cabeçalho de redirecionamento
    exit(); // ESSENCIAL: Encerra o script imediatamente após o header.
}

// --- Funções de Banco de Dados (Adicionaremos conforme necessário) ---

/**
 * Busca todos os funcionários ordenados por nome.
 * @param PDO $pdo Conexão PDO com o banco.
 * @return array Lista de funcionários ou array vazio em caso de erro.
 */
function getFuncionarios(PDO $pdo): array {
    try {
        $sql = "SELECT id, nome FROM funcionarios ORDER BY nome";
        $stmt = $pdo->query($sql); // query() é seguro para SQL sem input do usuário
        return $stmt->fetchAll(PDO::FETCH_ASSOC); // Retorna todas as linhas como array associativo
    } catch (PDOException $e) {
        error_log("Erro ao buscar funcionários: " . $e->getMessage()); // Loga o erro
        return []; // Retorna array vazio para não quebrar a página
    }
}

// ... (getTarefasPorStatus, atualizarStatusTarefa, etc. virão aqui depois) ...

?>
