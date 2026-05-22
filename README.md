# 🐳 Projeto Docker — PHP + Apache + MySQL

## Estrutura de pastas

```
C:\Users\carlo\workspace\docker\projeto\
│
├── docker-compose.yml       ← orquestra todos os containers
├── Dockerfile               ← constrói a imagem PHP + Apache
├── composer.json            ← dependências PHP e autoload
│
├── config/
│   └── php-custom.ini       ← configurações personalizadas do PHP
│
├── src/
│   ├── public/
│   │   └── index.php        ← página de entrada do sistema
│   ├── App/                 ← classes, modelos e serviços do sistema
│   ├── Config/              ← configuração de conexão com o banco
│   └── bootstrap.php        ← inicialização da aplicação
│
└── db/
    └── init/
        └── 001_schema.sql   ← scripts SQL executados na criação do banco
```

---

## Como usar

### 1. Pré-requisito
- [Docker Desktop](https://www.docker.com/products/docker-desktop/) instalado e em execução.

### 2. Copiar os arquivos
Extraia ou copie todos os arquivos para:
```
C:\Users\carlo\workspace\docker\projeto\
```

### 3. Subir o ambiente
Abra o **PowerShell** ou **Terminal** nessa pasta e execute:
```bash
docker compose up -d --build
```

### 4. Instalar dependências PHP
Após subir o container, execute:
```bash
docker compose exec app composer install
```

### 5. Acessar o projeto
| Serviço      | URL                        |
|--------------|----------------------------|
| Aplicação    | http://localhost:8080      |
| phpMyAdmin   | http://localhost:8081      |
| MySQL direto | localhost:3307             |

### 5. Parar o ambiente
```bash
docker compose down
```
> Para remover também os dados do banco:
> ```bash
> docker compose down -v
> ```

---

## Credenciais do banco de dados

| Campo    | Valor          |
|----------|----------------|
| Host     | `db`           |
| Porta    | `3306`         |
| Banco    | `projeto_db`   |
| Usuário  | `projeto_user` |
| Senha    | `projeto_pass` |
| Root     | `root_pass`    |

---

## Comandos úteis

```bash
# Ver logs em tempo real
docker compose logs -f

# Acessar o container PHP via terminal
docker exec -it projeto_app bash

# Acessar o MySQL via terminal
docker exec -it projeto_db mysql -u projeto_user -p
```

---

## Extensões PHP incluídas
`pdo`, `pdo_mysql`, `mysqli`, `gd`, `zip`, `mbstring`, `xml`, `bcmath`, `opcache`
