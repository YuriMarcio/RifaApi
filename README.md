<h1>🎟️ Projeto de Rifa Online</h1>

<p>Bem-vindo ao <strong>Projeto de Rifa Online</strong>! Este projeto permite a criação e gestão de rifas através de uma aplicação web desenvolvida com Laravel.</p>

<h2>✨ Funcionalidades</h2>
<ul>
  <li>📋 Registro e autenticação de usuários</li>
  <li>🎯 Criação de rifas</li>
  <li>🏆 Participação em rifas</li>
  <li>🔍 Visualização de rifas e vencedores</li>
  <li>📊 Dashboard de administração para gerenciar rifas e participantes</li>
</ul>

<h2>🛠️ Requisitos</h2>
<p>Antes de começar, certifique-se de ter os seguintes softwares instalados em sua máquina:</p>
<ul>
  <li><strong>PHP</strong> >= 8.0</li>
  <li><strong>Composer</strong></li>
  <li><strong>MySQL</strong> ou outro banco de dados compatível com Laravel</li>
  <li><strong>Node.js</strong> com npm ou Yarn</li>
  <li><strong>Laravel</strong> >= 9.0</li>
  <li><strong>Swagger L5</strong> para documentação da API</li>
</ul>

<h2>🚀 Instalação</h2>
<p>Siga os passos abaixo para configurar o projeto localmente:</p>

<ol>
  <li>Clone o repositório para sua máquina local:
    <pre><code>git clone https://github.com/seuusuario/nome-do-projeto.git</code></pre>
  </li>

  <li>Acesse o diretório do projeto:
    <pre><code>cd nome-do-projeto</code></pre>
  </li>

  <li>Instale as dependências do Composer:
    <pre><code>composer install</code></pre>
  </li>

  <li>Instale as dependências do Node.js:
    <pre><code>npm install</code></pre>
  </li>

  <li>Copie o arquivo <code>.env.example</code> para <code>.env</code> e configure suas variáveis de ambiente:
    <pre><code>cp .env.example .env</code></pre>
  </li>

  <li>Gere a chave da aplicação Laravel:
    <pre><code>php artisan key:generate</code></pre>
  </li>

  <li>Configure o banco de dados no arquivo <code>.env</code>. Exemplo:
    <pre><code>
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=rifa
DB_USERNAME=seu_usuario
DB_PASSWORD=sua_senha
    </code></pre>
  </li>

  <li>Execute as migrações para criar as tabelas no banco de dados:
    <pre><code>php artisan migrate</code></pre>
  </li>

  <li>(Opcional) Popule o banco de dados com dados iniciais:
    <pre><code>php artisan db:seed</code></pre>
  </li>

  <li>Inicie o servidor de desenvolvimento:
    <pre><code>php artisan serve</code></pre>
    <p>Agora, o projeto estará disponível em <a href="http://localhost:8000">http://localhost:8000</a>.</p>
  </li>
</ol>

<h2>📄 Gerar a Documentação da API</h2>
<p>Este projeto utiliza <strong>Swagger L5</strong> para documentar a API. Sempre que realizar alterações nas rotas ou na lógica da API, execute o comando abaixo para atualizar a documentação:</p>
<pre><code>php artisan l5-swagger:generate</code></pre>
<p>A documentação gerada ficará disponível em <a href="http://localhost:8000/api/documentation">http://localhost:8000/api/documentation</a>.</p>

<h2>🧰 Comandos Úteis</h2>
<ul>
  <li><strong>Migrar e atualizar o banco de dados:</strong>
    <pre><code>php artisan migrate:fresh --seed</code></pre>
  </li>

  <li><strong>Gerar a documentação da API:</strong>
    <pre><code>php artisan l5-swagger:generate</code></pre>
  </li>

  <li><strong>Compilar assets do frontend (CSS/JS):</strong>
    <pre><code>npm run dev</code></pre>
  </li>

  <li><strong>Rodar o servidor local:</strong>
    <pre><code>php artisan serve</code></pre>
  </li>
</ul>

<h2>🤝 Contribuindo</h2>
<p>Contribuições são bem-vindas! Sinta-se à vontade para abrir <strong>issues</strong> e <strong>pull requests</strong> para melhorias ou correções de bugs.</p>
