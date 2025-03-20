# Open Food Facts API

## Sobre o Projeto
Este projeto é uma API REST para importação e gerenciamento de produtos da Open Food Facts, utilizando Laravel e seguindo boas práticas de desenvolvimento.

## Tecnologias Utilizadas
- **Linguagem**: PHP
- **Framework**: Laravel 12
- **Banco de Dados**: MySQL
- **ORM**: Eloquent
- **Cache**: Redis (para armazenar a última execução do cron)
- **Documentação**: OpenAPI 3.0 (Swagger)
- **Docker + Laravel Sail**: Para ambiente isolado

## Instalação e Uso

### **Pré-requisitos**
- Docker
- Docker Compose

### **1 Clonar o Repositório**
```sh
git clone https://github.com/riverofernandes/api-food-laravel.git
cd open-food-api
```

### **2 Instalar Dependências**
```sh
composer install
```

### **3 Configurar Variáveis de Ambiente**
Copie o arquivo `.env.example` e renomeie para `.env`:
```sh
cp .env.example .env
```
Edite o arquivo `.env` para configurar o banco de dados.

### **4 Gerar a Chave da Aplicação**
```sh
php artisan key:generate
```

### **5 Subir os Containers com Laravel Sail**
```sh
./vendor/bin/sail up -d
```

### **6 Rodar as Migrations**
```sh
php artisan migrate
```

### **7 Rodar o Servidor**
```sh
php artisan serve
```
A API estará disponível em `http://localhost`

### **8️ Executar o Cron Manualmente** (Opcional)
```sh
php artisan products:import
```
O Cron foi configurado para rodar todos os dias as 00:00:00
Configurado no arquivo /routes/console.php

## .gitignore
Incluímos um `.gitignore` para evitar o envio de arquivos sensíveis:
```
/vendor
/node_modules
.env
/storage
/public/temp
```

## Challenge by Coodesh
Este projeto foi desenvolvido como parte de um desafio técnico da Coodesh.

---

Caso tenha dúvidas, entre em contato!

