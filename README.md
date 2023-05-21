### LiberFly API

# Requerimentos
* Docker
* Docker compose

# Instalação

1. Instalar as dependências do projeto
```
docker compose up -d
```

2. Acessar o container da aplicação
```
docker exec -it liberfly-api ash
```

3. No container da aplicação instalar as dependencias do projeto
```
composer install
```

4. Execute as migrations
```
php artisan migrate
```

5. Execute o seeder para criar um usuario
```
php artisan db:seed
```

** Credenciais de usuario
```
email: liberfly-test@auth.com,
password: password
```

# Documentação
1. Acesse a documentação em
```
http://localhost/docs
```

2. Acesse o YAML da OPENAPI em
```
http://localhost/docs.openapi
```

## Testes
1. Acessar o container da aplicação
```
docker exec -it liberfly-api ash
```

2. Executar o comando de testes
```
php artisan test --env=testing
```
