# Wisechats Order Service

## Setup

Esse projeto foi criado utilizando [Laravel Sail](https://laravel.com/docs/12.x/sail)

## Documentação

O Postman foi escolhido para documentar as requisições da API, no diretório raiz existem 2 arquivos:

Wisechats Order Service API.postman_collection.json  
Wisechats - Local.postman_environment.json

## Decisões de Arquitetura

### Autenticação

Para autenticar os endpoints foi utilizado Passport utilizando a estratégia **Password Grant**

Existe um usuário de exemplo, criado via Seed, com a seguinte credencial:

```
email: test@example.com
senha: 123456
```