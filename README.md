# Open Food Facts API

Este projeto é uma REST API desenvolvida para utilizar os dados do projeto Open Food Facts, que é um banco de dados aberto com informação nutricional de diversos produtos alimentícios.
A API foi criada para dar suporte à equipe de nutricionistas da Fitness Foods LC, permitindo uma revisão rápida e eficiente das informações nutricionais dos alimentos que os usuários publicam através da aplicação móvel.

## Tecnologias Utilizadas

- **Linguagem:** PHP
- **Framework:** Laravel
- **Banco de Dados:** MySQL
- **Outras Tecnologias:** Composer

## Descrição

Esta API REST foi desenvolvida seguindo as melhores práticas de desenvolvimento. Ela se integra com um banco de dados MySQL para persistir os dados do Open Food Facts e inclui um sistema de CRON para atualização diária dos dados importados. A API oferece endpoints CRUD para gerenciar os produtos alimentícios, com suporte a paginação e controle de status (draft, trash, published).

## Instalação e Uso

### Pré-requisitos

- **PHP 8.0 ou superior**
- **Composer versão 2.7.7 ou superior**
- **MySQL versão 8.0.33 ou superior**

### Passos para Instalação

1. **Clone o repositório:**
   ```bash
   git clone https://github.com/seu-usuario/nome-do-repositorio.git
   cd nome-do-repositorio
   ```

2. **Instale as dependências.**
   ```bash
   composer install
   ```

3. **Configuração do arquivo .env .**
   Copie e cole o arquivo ".env.example", renomeie para ".env" e configure as variáveis de ambiente,
   por exemplo as variáveis do seu banco de dados.

4. **Executar as migrations do Banco de dados.**
   ```bash
   php artisan migrate
   ```

5. **Importação para o seu banco de dados via CRON.**
   ```bash
   php artisan process:database-import
   ```

### Utilização da API

**EndPoints**

º GET api/ 
    - Detalhes da API verifciação de status.

º PUT api/products/{code} 
    - Atualizar informações de um produto.

º DELETE api/products/{code}
    - Altera status do produto para 'trash'.

º GET api/products/{code}
    - Obter detalhes do produto especificado.

º GET api/products
    - Listar todos os produtos com paginação de 10 por página.