<?php
$host = 'localhost'; // Ou o endereço do seu servidor de banco de dados
$dbname = 'gerenciador_tarefas'; // Nome do banco que criamos
$username = 'root'; // Usuário do banco (padrão em XAMPP, use um seguro em produção!)
$password = '';     // Senha do banco (padrão em XAMPP, use uma segura em produção!)

try {
    // Cria a conexão PDO
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);

    // Configura o PDO para lançar exceções em caso de erro
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // (Opcional, mas útil) Define o modo de busca padrão para array associativo
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    // Em caso de erro na conexão, exibe uma mensagem e encerra o script
    // Em produção: Logar o erro e mostrar mensagem genérica ao usuário
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}
?>


<!-- 

PDO	                        ----- Cria a conexão com o banco de dados usando PHP e MySQL
setAttribute()	            ----- Define comportamentos do PDO (erros, retorno de dados, etc)
try { ... } catch { ... }	----- Tenta algo e captura erros para evitar que o site "quebre"
die()	                    ----- Encerra o script e mostra uma mensagem 

-->