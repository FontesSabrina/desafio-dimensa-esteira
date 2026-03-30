# 🚀 Gestão de Esteira Ativa - Desafio Dimensa

Este sistema foi desenvolvido como parte do processo seletivo para a posição de **Analista I de Desenvolvimento de Software na Dimensa**.  
A aplicação foca no processamento de alto volume de dados (**50 mil registros**) e na gestão eficiente de operações financeiras.

---

## 🛠️ Tecnologias Utilizadas

- **Framework:** PHP 8 / Laravel 12  
- **Banco de Dados:** MySQL  
- **Frontend:** Blade Engines, Tailwind CSS e Javascript  
- **Servidor Local:** Xampp (Apache/MySQL) e PHP Artisan  
- **Ferramentas:** Laravel Excel (para importação de alto volume)  

---

## 📥 Como Executar o Projeto

### 1. Clonar o repositório
```bash
git clone [https://github.com/FontesSabrina/desafio-dimensa-esteira.git](https://github.com/FontesSabrina/desafio-dimensa-esteira.git)
cd desafio-dimensa-esteira
2. Instalar dependências
Bash
composer install
npm install
3. Compilar os ativos (CSS/JS)
Bash
# Desenvolvimento
npm run dev

# Produção
npm run build
4. Configurar Ambiente (Xampp)
Inicie os módulos Apache e MySQL no Xampp.

Crie o banco de dados: dimensa_db.

Configure o arquivo .env:

Snippet de código
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=dimensa_db
DB_USERNAME=root
DB_PASSWORD=
5. Migrar e popular o banco
Bash
php artisan migrate --seed
6. Iniciar servidor
Bash
php artisan serve
🔐 Credenciais de Acesso (Seed)
Usuário/E-mail: admin@admin.com

Senha: 12345678

📊 Como Executar a Importação
O sistema foi otimizado para suportar a carga de 50 mil registros:

Acesse a plataforma com as credenciais acima.

No dashboard "Gestão de Esteira Ativa", localize o card de importação.

Selecione um arquivo .xlsx ou .csv.

Clique em "Processar Dados".

✔️ O sistema utiliza leitura em blocos (chunks) para garantir performance.

🧠 Decisões Técnicas Tomadas
Cálculo de Valor Presente (VP): Implementado seguindo rigorosamente fórmulas de atraso e adiantamento.

Logs de Auditoria: Registro de todas as mudanças de status para rastreabilidade.

Regras de Status: O status "PAGO AO CLIENTE" só ocorre se a proposta estiver APROVADA e já tiver passado por ASSINATURA CONCLUÍDA.

Performance: Uso de paginação e consultas otimizadas para lidar com os 50 mil registros.

⚠️ Limitações da Solução
Processamento Síncrono: A importação ocorre de forma síncrona. Em produção, o ideal seria utilizar Queues (filas).

Interface: O foco principal foi a lógica de negócio e os critérios técnicos do desafio.

📈 Melhorias Futuras
[ ] Testes automatizados (principalmente cálculo de VP).

[ ] Dashboard com gráficos e indicadores.

[ ] Dockerização com Docker Compose.

👩‍💻 Autora
Desenvolvido por Sabrina Fontes
