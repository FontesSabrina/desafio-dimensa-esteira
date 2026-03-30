🚀 Gestão de Esteira Ativa - Desafio Dimensa

Este sistema foi desenvolvido como parte do processo seletivo para a posição de Analista I de Desenvolvimento de Software na Dimensa. A aplicação foca no processamento de alto volume de dados (50 mil registros) e na gestão eficiente de operações financeiras.

🛠️ Tecnologias Utilizadas

Framework: PHP 8 / Laravel 12

Banco de Dados: MySQL

Frontend: Blade Engines, Tailwind CSS e Javascript

Servidor Local: Xampp (Apache/MySQL) e PHP Artisan

Ferramentas: Laravel Excel (para importação de alto volume)

📥 Como Executar o Projeto

Clonar o repositório:

git clone [https://github.com/FontesSabrina/desafio-dimensa-esteira.git](https://github.com/FontesSabrina/desafio-dimensa-esteira.git)
cd desafio-dimensa-esteira


Instalar dependências de Backend e Frontend:

composer install
npm install


Compilar os ativos (CSS/JS):

Para desenvolvimento: npm run dev

Para produção: npm run build

Configurar Ambiente (Xampp):

Inicie os módulos Apache e MySQL no Painel do Xampp.

Crie um banco de dados chamado dimensa_db no seu MySQL.

Renomeie .env.example para .env e configure suas credenciais:

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dimensa_db
DB_USERNAME=root
DB_PASSWORD=


Migrar e Popular o Banco (Seed):

php artisan migrate --seed


Iniciar Servidor Artisan:

php artisan serve


🔐 Credenciais de Acesso (Padrão Seed)

Para acessar a plataforma após rodar o comando db:seed, utilize as credenciais configuradas:

Usuário/E-mail: admin@admin.com

Senha: 12345678

📊 Como Executar a Importação

O sistema foi otimizado para suportar a carga de 50 mil registros exigida pelo desafio:

Acesse a plataforma com as credenciais acima.

No Dashboard de "Gestão de Esteira Ativa", localize o card de importação.

Selecione o arquivo .xlsx ou .csv contendo os dados das operações.

Clique em "Processar Dados". O sistema utiliza leitura em blocos (chunks) para garantir a performance.

🧠 Decisões Técnicas Tomadas

Cálculo de Valor Presente (VP): Implementado seguindo rigorosamente as fórmulas de atraso e adiantamento para cada parcela.

Logs de Auditoria: Cada mudança de status de uma operação é registrada em um histórico, garantindo a rastreabilidade.

Regras de Status: Travas de segurança garantem que "PAGO AO CLIENTE" só ocorra se a operação estiver "APROVADA" e já tiver passado por "ASSINATURA CONCLUÍDA".

Performance: Uso de paginação e consultas otimizadas para a listagem das 50 mil linhas.

⚠️ Limitações da Solução

Processamento Síncrono: A importação ocorre de forma síncrona para este desafio. Em produção, seria recomendado o uso de filas (Queues) para evitar timeouts em volumes ainda maiores.

Interface: O foco foi a funcionalidade técnica, clareza de dados e critérios de aceitação do edital.

📈 O que melhoraria com mais tempo

Testes Automatizados: Cobertura de testes unitários para a lógica financeira (Cálculo de VP).

Dashboard: Visualização gráfica da saúde da esteira de crédito e volumetria por status.

Dockerização: Ambiente completo via Docker Compose.

Desenvolvido por Sabrina Fontes.
