Gerenciador de tarefas desenvolvido em sala de aula no curso de Técnico em desenvolvimento de sistemas.

- Possui autenticação de registro e login.

- Registro de tarefas baseado em prioridade

- Registro de funcionários.

- Quadro de gerenciamento de tarefas (Kanban)


Banco de dados utilizando o XAMPP no phpMyAdmin(LOCAL).

Instruções da funcionalidade do banco de dados (Criação):

<strong>CRIAR A TABELA DE FUNCIONÁRIOS:</strong>

CREATE TABLE funcionarios (
    id INT AUTO_INCREMENT PRIMARY KEY, -- ID único, auto-incrementado (boa prática!)
    nome VARCHAR(255) NOT NULL,       -- Nome (obrigatório)
    email VARCHAR(255) NOT NULL UNIQUE, -- Email (obrigatório e único)
    departamento VARCHAR(100) NOT NULL -- Departamento (obrigatório)
);

<strong>GUARDAR AS TAREFAS:</strong>

CREATE TABLE tarefas (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    descricao TEXT NULL,                     -- Descrição (pode ser longa, opcional)
    prioridade ENUM('baixa', 'media', 'alta') NOT NULL DEFAULT 'media', -- Prioridade (valores definidos, padrão 'media')
  status ENUM('a_fazer', 'em_andamento', 'concluido') NOT NULL DEFAULT 'a_fazer', -- Status (valores definidos, padrão 'a_fazer')
    data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Data/hora de criação (automática)
    data_conclusao TIMESTAMP NULL,           -- Data/hora de conclusão (preenchida depois)
    funcionario_id INT NULL,                 -- Chave estrangeira (pode ser nula)
   FOREIGN KEY (funcionario_id) REFERENCES funcionarios(id) ON DELETE SET NULL -- Relacionamento!
);

<strong>CRIAR A TABELA DE USUÁRIOS (Responsável pelo login):</strong>

CREATE TABLE usuarios (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL, -- IMPORTANTE: Vai guardar o HASH da senha
    data_cadastro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
