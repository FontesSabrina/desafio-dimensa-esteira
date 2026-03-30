# 🚀 Gestão de Esteira Ativa - Desafio Dimensa

[cite_start]Este sistema foi desenvolvido como parte do processo seletivo para a posição de **Analista I de Desenvolvimento de Software** na Dimensa[cite: 2]. [cite_start]A aplicação foca no processamento de alto volume de dados (50 mil registros) e na gestão eficiente de operações financeiras[cite: 2].

## 🛠️ Tecnologias Utilizadas

* [cite_start]**Framework:** PHP 8 / Laravel 12 [cite: 1]
* [cite_start]**Banco de Dados:** MySQL [cite: 1]
* [cite_start]**Frontend:** Blade Engines, Tailwind CSS e Javascript [cite: 1]
* [cite_start]**Servidor Local:** Xampp (Apache/MySQL) e PHP Artisan [cite: 1]
* **Ferramentas:** Laravel Excel (para importação de alto volume)

## 📥 Como Executar o Projeto

1.  **Clonar o repositório:**
    ```bash
    git clone https://github.com/FontesSabrina/desafio-dimensa-esteira.git
    cd desafio-dimensa-esteira
    ```

2.  **Instalar dependências de Backend e Frontend:**
    ```bash
    composer install
    npm install
    ```

3.  **Compilar os ativos (CSS/JS):**
    * Para desenvolvimento: `npm run dev`
    * Para produção: `npm run build`

4.  **Configurar Ambiente (Xampp):**
    * [cite_start]Inicie os módulos **Apache** e **MySQL** no Painel do Xampp[cite: 1].
    * Crie um banco de dados chamado `dimensa_db` no seu MySQL.
    * Renomeie `.env.example` para `.env` e configure suas credenciais:
        ```env
        DB_CONNECTION=mysql
        DB_HOST=127.0.0.1
        DB_PORT=3306
        DB_DATABASE=dimensa_db
        DB_USERNAME=root
        DB_PASSWORD=
        ```

5.  **Migrar e Popular o Banco (Seed):**
    ```bash
    php artisan migrate --seed
    ```

6.  **Iniciar Servidor Artisan:**
    ```bash
    php artisan serve
