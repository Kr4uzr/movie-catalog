```markdown
# Movie Catalog

Este é um projeto de catálogo de filmes que utiliza Laravel no backend e Vue.js no frontend. Ele permite listar, buscar e gerenciar filmes favoritos, integrando-se à API do TMDB para obter informações sobre os filmes.

---

## 📋 Tecnologias Utilizadas

| Tecnologia         | Versão         | Descrição                                                                 |
|--------------------|----------------|---------------------------------------------------------------------------|
| **Laravel**        | 12.x           | Framework PHP utilizado para o backend e gerenciamento da API.           |
| **PHP**            | 8.3.x          | Linguagem de programação para o backend.                                 |
| **Vue.js**         | 3.x            | Framework JavaScript utilizado para o frontend.                          |
| **MySQL**          | 8.x            | Banco de dados relacional utilizado para armazenar os dados.             |
| **Docker**         | 28.x           | Plataforma para criar e gerenciar containers.                            |
| **PHPUnit**        | 11.x           | Framework de testes para o backend.                                      |
| **Swagger**        | 9.x            | Ferramenta para documentação e teste da API.                             |
| **Node.js**        | 23.x           | Ambiente de execução JavaScript para o frontend.                         |
| **NPM**            | 10.x           | Gerenciador de pacotes para o Node.js.                                   |

---

## 🚀 Como rodar o projeto localmente com Docker

Siga os passos abaixo para configurar e rodar o projeto localmente utilizando Docker:

1. **Clone o repositório**:
   ```bash
   git clone https://github.com/seu-usuario/movie-catalog.git
   cd movie-catalog
   ```

2. **Configure o arquivo `.env`**:
   - Copie o arquivo `.env.example` para `.env`:
     ```bash
     cp .env.example .env
     ```
   - Configure as variáveis de ambiente, como o banco de dados e a chave da API do TMDB:
     ```
     DB_CONNECTION=mysql
     DB_HOST=host
     DB_PORT=3306
     DB_DATABASE=database_name
     DB_USERNAME=user_name
     DB_PASSWORD=user_password

     TMDB_API_KEY=sua_chave_da_api
     ```

3. **Suba os containers com Docker Compose**:
   ```bash
   docker-compose up -d
   ```

4. **Execute as migrações e seeders**:
   ```bash
   docker exec -it movie-catalog-app php artisan migrate --seed
   ```

5. **Acesse a aplicação**:
   - Backend (Laravel): [http://localhost:8000](http://localhost:8000)
   - Frontend (Vue.js): [http://localhost:5173](http://localhost:5173)

---

## 🛠️ Indicação de onde está implementado o CRUD

O CRUD de filmes favoritos está implementado nos seguintes arquivos e diretórios:

- **Rotas**:
  - Arquivo: `server/routes/api.php`
  - Rotas principais:
    - `GET /api/favorites` - Lista os filmes favoritos.
    - `POST /api/favorites` - Adiciona um filme aos favoritos.
    - `DELETE /api/favorites/{id}` - Remove um filme dos favoritos.

- **Controllers**:
  - Arquivo: `server/app/Http/Controllers/Api/FavoritesController.php`
  - Métodos principais:
    - `index` - Lista os favoritos.
    - `store` - Adiciona um favorito.
    - `delete` - Remove um favorito.

- **Models**:
  - Arquivo: `server/app/Models/Favorite.php`
  - Representa o modelo de dados para os filmes favoritos.

- **Views**:
  - Arquivo: `client/src/pages/FavoritesPage.vue`
  - Exibe a interface para listar e gerenciar os filmes favoritos.

---

## ✅ Instruções sobre como testar a aplicação

### Testando manualmente:
1. Acesse a interface web:
   - Backend: [http://localhost:8000](http://localhost:8000)
   - Frontend: [http://localhost:5173](http://localhost:5173)
2. Use a interface para:
   - Listar os filmes mais bem avaliados.
   - Buscar filmes por nome.
   - Adicionar ou remover filmes dos favoritos.
   - Visualizar os detalhes de um filme em um modal.

### Testando automaticamente:
1. Execute os testes do backend com PHPUnit:
   ```bash
   docker exec -it movie-catalog-app php artisan test
   ```
2. Os testes validarão:
   - Rotas de filmes (`/api/movies`).
   - Rotas de favoritos (`/api/favorites`).
   - Estrutura de resposta da API.

---

📜 Documentação da API via Swagger
A documentação da API foi gerada utilizando o Swagger. Para acessá-la:

Certifique-se de que o backend está rodando.
Acesse a URL da documentação no navegador:
http://localhost:8000/api/documentation
Utilize a interface do Swagger para explorar e testar as rotas disponíveis.

---

## 🔑 Link para obter a chave da API do TMDB

Para utilizar a API do TMDB, você precisa de uma chave de API. Siga os passos abaixo para obtê-la:

1. Acesse o site oficial do TMDB: [https://www.themoviedb.org/](https://www.themoviedb.org/).
2. Crie uma conta ou faça login.
3. Vá para a seção **API** no seu perfil.
4. Clique em **"Create API Key"** e siga as instruções.
5. Copie a chave gerada e adicione-a ao arquivo `.env`:
   ```
   TMDB_API_KEY=sua_chave_da_api
   ```

---

## 🌐 Como subir o frontend separado (Vue.js)

Se o frontend estiver separado do backend, siga os passos abaixo para rodá-lo:

1. **Acesse o diretório do frontend**:
   ```bash
   cd client
   ```

2. **Instale as dependências**:
   ```bash
   npm install
   ```

3. **Inicie o servidor de desenvolvimento**:
   ```bash
   npm run dev
   ```

4. **Acesse o frontend**:
   - URL: [http://localhost:5173](http://localhost:5173)

---

## 📂 Estrutura do projeto

```
movie-catalog/
├── client/                # Código do frontend (Vue.js)
│   ├── src/
│   │   ├── pages/         # Páginas principais (App.vue, FavoritesPage.vue e HomePage.vue)
|   ├── assets/            # Onde está main.css e svg da aba do navegador
│   └── package.json       # Dependências do frontend
├── server/                # Código do backend (Laravel)
│   ├── app/
│   │   ├── Http/
│   │   │   └── Controllers/Api/  # Controllers da API
│   │   ├── Models/        # Modelos do Eloquent
│   └── routes/api.php     # Rotas da API
├── docker-compose.yml     # Configuração do Docker
└── README.md              # Documentação do projeto
```