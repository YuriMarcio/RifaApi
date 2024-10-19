Projeto de Rifa Online
Bem-vindo ao projeto de Rifa Online! Este projeto permite a criação e gestão de rifas através de uma aplicação web desenvolvida com Laravel.

Funcionalidades
Registro e autenticação de usuários
Criação de rifas
Participação em rifas
Visualização de rifas e vencedores
Dashboard de administração para gerenciar rifas e participantes
Requisitos
Antes de começar, certifique-se de ter os seguintes softwares instalados em sua máquina:

PHP >= 8.0
Composer
MySQL ou qualquer outro banco de dados compatível com Laravel
Node.js com npm ou Yarn
Laravel >= 9.0
Swagger L5 para documentação da API
Instalação
Siga os passos abaixo para configurar o projeto localmente:

Clone o repositório para sua máquina local:

bash
Copiar código
git clone https://github.com/seuusuario/nome-do-projeto.git
Acesse o diretório do projeto:

bash
Copiar código
cd nome-do-projeto
Instale as dependências do Composer:

bash
Copiar código
composer install
Instale as dependências do Node.js:

bash
Copiar código
npm install
Copie o arquivo .env.example para .env e configure suas variáveis de ambiente:

bash
Copiar código
cp .env.example .env
Gere a chave da aplicação Laravel:

bash
Copiar código
php artisan key:generate
Configure o banco de dados no arquivo .env. Por exemplo:

makefile
Copiar código
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rifa
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
Execute as migrações para criar as tabelas no banco de dados:

bash
Copiar código
php artisan migrate
(Opcional) Popule o banco de dados com dados iniciais:

bash
Copiar código
php artisan db:seed
Inicie o servidor de desenvolvimento:

bash
Copiar código
php artisan serve
Agora, o projeto estará disponível em http://localhost:8000.

Gerar a Documentação da API
Este projeto utiliza Swagger L5 para documentar a API. Sempre que realizar alterações nas rotas ou na lógica da API, execute o comando abaixo para atualizar a documentação:

bash
Copiar código
php artisan l5-swagger:generate
A documentação gerada ficará disponível em http://localhost:8000/api/documentation.

Comandos Úteis
Migrar e atualizar o banco de dados:

bash
Copiar código
php artisan migrate:fresh --seed
Gerar a documentação da API:

bash
Copiar código
php artisan l5-swagger:generate
Compilar assets do frontend (CSS/JS):

bash
Copiar código
npm run dev
Rodar o servidor local:

bash
Copiar código
php artisan serve
Contribuindo
Contribuições são bem-vindas! Sinta-se à vontade para abrir issues e pull requests para melhorias ou correções de bugs.

Esse README fornece as informações básicas para que qualquer pessoa consiga rodar o projeto localmente e atualizar a documentação da API quando necessário.
