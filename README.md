# Teste PHP - Enterprize

Para que seja possível executar esse projeto. É preciso inicialmente instalar todas as tecnologias necessárias.

Dependênias:
 - [PHP](https://www.php.net/)
 - [Composer](https://getcomposer.org/) 
 - [Node & NPM](https://nodejs.org/en/)
 - [Docker](https://docs.docker.com/engine/install/) & [Docker-compose](https://docs.docker.com/compose/install/)

## Iniciar Projeto

1. Primeiro é preciso instalar/atualizar todas as dependências do projeto:
    - Execute o comando no terminal: ```composer update```;
    - Execute o comando em um outro terminal: ``` npm install ```;
2. Segundo esse projeto utilizou postgres como banco de dados, para que fosse fácil 
instalar o banco foi adicionado um arquivo docker-compose, se vc não teve nenhum problema 
ao instalar o docker e o docker-compose basta executar o comando (em um outro terminal) 
``` docker-compose up ``` ou ```docker-compose up -d``` , dessa forma o banco estará 
executando em background. 

3. Após isso precisamos adicionar a estrutra de tabelas no banco de dados, para isso foi desenvolvido
as migrações, que são responsaveis por criar o schema do banco. Basta executar o comando 
```php artisan migrate```.

4. Dessa forma para finalizar basta apenas executar mais dois comandos.
 - Execute em um terminal a parte o camando ```npm run watch```.
 - Execute o camando ```php artisan serve```.
 
 Acesse a URL http://localhost:8000/.
 
 
 
 ## TDD
 
 O desenvolvimento desse projeto foi todo orientado pela metodologia Test Driven Developmenet(TDD). Por
  isso foram adicionados testes de integração. Para executar os testes, basta digitar esse comando
   ```php artisan test```.
