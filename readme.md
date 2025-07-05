# 📚 API BookMaster - Documentação Completa

## 🔍 Visão Geral

A **API BookMaster** é um sistema de gerenciamento de livraria desenvolvido em Laravel que permite o gerenciamento completo de livros, autores e editoras. O sistema oferece operações CRUD (Create, Read, Update, Delete) para todos os recursos principais.

### 🛠️ Tecnologias Utilizadas

- **PHP 8.x**
- **Laravel Framework**
- **JavaScript/AJAX**
- **HTML/CSS**
- **MySQL**

**Base URL:** `http://localhost:8000/api`

---

## 📋 Estrutura de Resposta Padronizada

Todas as respostas da API seguem uma estrutura padronizada:

### ✅ Resposta de Sucesso

```json
{
  "status": "success",
  "code": 200,
  "timeResponse": "2024-01-15T10:30:00.000000Z",
  "message": "Operação realizada com sucesso",
  "data": {
    // dados retornados
  }
}
```

### ❌ Resposta de Erro

```json
{
  "status": "error",
  "code": 422,
  "timeResponse": "2024-01-15T10:30:00.000000Z",
  "errorMessage": "Erro de validação dos campos.",
  "errors": {
    "field": ["Mensagem de erro específica"]
  }
}
```

### 🔢 Códigos de Status HTTP

| Código | Significado | Descrição |
|--------|-------------|-----------|
| **200** | OK | Operação realizada com sucesso |
| **404** | Not Found | Recurso não encontrado |
| **409** | Conflict | Conflito - recurso possui dependências |
| **422** | Unprocessable Entity | Erro de validação |
| **500** | Internal Server Error | Erro interno do servidor |

---

## 📚 Endpoints de Livros

### 1. 📖 Listar Livros

**`GET /book/list`**

Lista todos os livros com opções de filtro avançadas.

#### Parâmetros de Consulta (Query Parameters)

| Parâmetro | Tipo | Obrigatório | Descrição |
|-----------|------|-------------|-----------|
| `search` | string | Não | Busca por título do livro (min: 3, max: 255) |
| `isbn` | string | Não | Filtro por ISBN (min: 10, max: 13) |
| `year` | integer | Não | Filtro por ano de publicação (4 dígitos) |
| `category` | string | Não | Filtro por categoria (min: 3, max: 255) |
| `author` | string | Não | Filtro por nome do autor (min: 3, max: 255) |
| `publisher` | string | Não | Filtro por nome da editora (min: 3, max: 255) |

#### Exemplo de Requisição

```bash
GET /book/list?search=Dom%20Casmurro&author=Machado
```

#### Exemplo de Resposta

```json
{
  "status": "success",
  "code": 200,
  "timeResponse": "2024-01-15T10:30:00.000000Z",
  "data": [
    {
      "id": 1,
      "title": "Dom Casmurro",
      "isbn": "9788535902779",
      "release_year": 1899,
      "description": "Um dos maiores clássicos da literatura brasileira...",
      "publisher": {
        "id": 1,
        "name": "Ática",
        "country": "Brasil"
      },
      "authors": [
        {
          "id": 1,
          "name": "Machado de Assis",
          "birthdate": "1839-06-21",
          "bio": "Escritor brasileiro..."
        }
      ],
      "categories": [
        {
          "id": 1,
          "name": "Romance"
        }
      ]
    }
  ]
}
```

### 2. ➕ Criar Livro

**`POST /book/store`**

Cria um novo livro no sistema. Automaticamente cria autor, editora e categoria se não existirem.

#### Body da Requisição

```json
{
  "title": "Dom Casmurro",
  "isbn": "9788535902779",
  "publisher": "Ática",
  "publisherCountry": "Brasil",
  "releaseYear": 1899,
  "description": "Um dos maiores clássicos da literatura brasileira...",
  "author": "Machado de Assis",
  "category": "Romance",
  "authorBirthdate": "1839-06-21",
  "authorBio": "Escritor brasileiro, considerado o maior nome da literatura nacional..."
}
```

#### Validações

| Campo | Regras | Mensagem de Erro |
|-------|--------|------------------|
| `title` | required, string, max:255 | "O título do livro é obrigatório" |
| `isbn` | required, string, unique:books | "O ISBN é obrigatório e deve ser único" |
| `publisher` | required, string, max:255 | "O nome da publicadora é obrigatório" |
| `publisherCountry` | nullable, string, max:255 | "O país da publicadora deve ser válido" |
| `releaseYear` | required, digits:4, min:1000, max:ano_atual | "O ano de lançamento deve ser válido" |
| `description` | required, string | "A descrição é obrigatória" |
| `author` | required, string, max:255 | "O nome do autor é obrigatório" |
| `category` | required, string, max:255 | "A categoria é obrigatória" |
| `authorBirthdate` | required, date, before:today | "A data de nascimento do autor deve ser válida" |
| `authorBio` | required, string | "A biografia do autor é obrigatória" |

### 3. ✏️ Atualizar Livro

**`PUT /book/update`**

Atualiza informações de um livro existente.

#### Body da Requisição

```json
{
  "id": 1,
  "title": "Dom Casmurro - Edição Revisada",
  "isbn": "9788535902779",
  "description": "Descrição atualizada...",
  "releaseYear": 1900
}
```

#### Validações

| Campo | Regras | Descrição |
|-------|--------|-----------|
| `id` | required, integer | ID do livro a ser atualizado |
| `title` | string, max:255 | Título do livro (opcional) |
| `isbn` | string | ISBN do livro (opcional) |
| `description` | string | Descrição do livro (opcional) |
| `releaseYear` | digits:4, min:1000, max:ano_atual | Ano de lançamento (opcional) |

### 4. 🗑️ Deletar Livro

**`DELETE /book/delete`**

Remove um livro do sistema.

#### Body da Requisição

```json
{
  "id": 1
}
```

#### Validações

| Campo | Regras | Descrição |
|-------|--------|-----------|
| `id` | required, integer | ID do livro a ser deletado |

---

## 👤 Endpoints de Autores

### 1. 📋 Listar Autores

**`GET /author/list`**

Lista todos os autores com opções de filtro.

#### Parâmetros de Consulta

| Parâmetro | Tipo | Descrição |
|-----------|------|-----------|
| `name` | string | Busca por nome do autor (max: 255) |
| `birthdate` | integer | Filtro por ano de nascimento (4 dígitos) |
| `bio` | string | Busca na biografia (min: 3) |

#### Exemplo de Resposta

```json
{
  "status": "success",
  "code": 200,
  "timeResponse": "2024-01-15T10:30:00.000000Z",
  "data": [
    {
      "id": 1,
      "name": "Machado de Assis",
      "birthdate": "1839-06-21",
      "bio": "Escritor brasileiro, considerado o maior nome da literatura nacional...",
      "created_at": "2024-01-15T10:30:00.000000Z",
      "updated_at": "2024-01-15T10:30:00.000000Z"
    }
  ]
}
```

### 2. ➕ Criar Autor

**`POST /author/store`**

Cria um novo autor no sistema.

#### Body da Requisição

```json
{
  "name": "Machado de Assis",
  "birthdate": "1839-06-21",
  "bio": "Escritor brasileiro, considerado o maior nome da literatura nacional..."
}
```

#### Validações

| Campo | Regras | Mensagem de Erro |
|-------|--------|------------------|
| `name` | required, string, min:3, max:255 | "O campo nome é obrigatório" |
| `birthdate` | required, date, before_or_equal:today | "A data de nascimento não pode ser no futuro" |
| `bio` | required, string, min:3 | "A biografia deve ter no mínimo 3 caracteres" |

### 3. ✏️ Atualizar Autor

**`PUT /author/update`**

Atualiza informações de um autor existente.

#### Body da Requisição

```json
{
  "id": 1,
  "name": "Joaquim Maria Machado de Assis",
  "birthdate": "1839-06-21",
  "bio": "Biografia atualizada..."
}
```

#### Validações

| Campo | Regras | Descrição |
|-------|--------|-----------|
| `id` | required, integer | ID do autor a ser atualizado |
| `name` | string, min:3, max:255 | Nome do autor (opcional) |
| `birthdate` | date, before_or_equal:today | Data de nascimento (opcional) |
| `bio` | string, min:3, max:255 | Biografia do autor (opcional) |

### 4. 🗑️ Deletar Autor

**`DELETE /author/delete`**

Remove um autor do sistema.

#### Body da Requisição

```json
{
  "id": 1
}
```

#### ⚠️ Regras de Negócio

- Não é possível deletar um autor que possua livros cadastrados
- Retorna erro 409 (Conflict) se o autor possuir livros

#### Exemplo de Erro

```json
{
  "status": "error",
  "code": 409,
  "timeResponse": "2024-01-15T10:30:00.000000Z",
  "errorMessage": "Não é possível deletar o autor, pois ele possui livros cadastrados."
}
```

---

## 🏢 Endpoints de Editoras

### 1. 📋 Listar Editoras

**`GET /publisher/list`**

Lista todas as editoras com opções de filtro.

#### Parâmetros de Consulta

| Parâmetro | Tipo | Descrição |
|-----------|------|-----------|
| `name` | string | Busca por nome da editora (max: 255) |
| `country` | string | Filtro por país (max: 255) |

#### Exemplo de Resposta

```json
{
  "status": "success",
  "code": 200,
  "timeResponse": "2024-01-15T10:30:00.000000Z",
  "data": [
    {
      "id": 1,
      "name": "Editora Ática",
      "country": "Brasil",
      "created_at": "2024-01-15T10:30:00.000000Z",
      "updated_at": "2024-01-15T10:30:00.000000Z"
    }
  ]
}
```

### 2. ➕ Criar Editora

**`POST /publisher/store`**

Cria uma nova editora no sistema.

#### Body da Requisição

```json
{
  "name": "Editora Ática",
  "country": "Brasil"
}
```

#### Validações

| Campo | Regras | Mensagem de Erro |
|-------|--------|------------------|
| `name` | required, string, min:3, max:255 | "O campo nome é obrigatório" |
| `country` | required, string, min:3, max:255 | "O campo país é obrigatório" |

### 3. ✏️ Atualizar Editora

**`PUT /publisher/update`**

Atualiza informações de uma editora existente.

#### Body da Requisição

```json
{
  "id": 1,
  "name": "Editora Ática S.A.",
  "country": "Brasil"
}
```

#### Validações

| Campo | Regras | Descrição |
|-------|--------|-----------|
| `id` | required, integer | ID da editora a ser atualizada |
| `name` | string, min:3, max:255 | Nome da editora (opcional) |
| `country` | string, min:3, max:255 | País da editora (opcional) |

### 4. 🗑️ Deletar Editora

**`DELETE /publisher/delete`**

Remove uma editora do sistema.

#### Body da Requisição

```json
{
  "id": 1
}
```

#### ⚠️ Regras de Negócio

- Não é possível deletar uma editora que possua livros cadastrados
- Retorna erro 409 (Conflict) se a editora possuir livros

---

## 📊 Modelos de Dados

### 📚 Modelo Book (Livro)

```json
{
  "id": "integer",
  "title": "string",
  "isbn": "string",
  "publisher_id": "integer",
  "release_year": "integer",
  "description": "text",
  "created_at": "datetime",
  "updated_at": "datetime"
}
```

### 👤 Modelo Author (Autor)

```json
{
  "id": "integer",
  "name": "string",
  "birthdate": "date",
  "bio": "text",
  "created_at": "datetime",
  "updated_at": "datetime"
}
```

### 🏢 Modelo Publisher (Editora)

```json
{
  "id": "integer",
  "name": "string",
  "country": "string",
  "created_at": "datetime",
  "updated_at": "datetime"
}
```

### 🏷️ Modelo Category (Categoria)

```json
{
  "id": "integer",
  "name": "string",
  "created_at": "datetime",
  "updated_at": "datetime"
}
```

---

## 🔗 Relacionamentos

### 📚 Livro (Book)
- `belongsTo` Publisher (publisher_id)
- `belongsToMany` Author (tabela pivot: author_book)
- `belongsToMany` Category (tabela pivot: book_category)

### 👤 Autor (Author)
- `belongsToMany` Book (tabela pivot: author_book)

### 🏢 Editora (Publisher)
- `hasMany` Book

### 🏷️ Categoria (Category)
- `belongsToMany` Book (tabela pivot: book_category)

---

## 🚀 Exemplos de Uso

### Criar um livro completo

```javascript
$.ajax({
  url: '/book/store',
  method: 'POST',
  data: {
    title: 'O Cortiço',
    isbn: '9788508133987',
    publisher: 'Ática',
    publisherCountry: 'Brasil',
    releaseYear: 1890,
    description: 'Romance naturalista de Aluísio Azevedo...',
    author: 'Aluísio Azevedo',
    category: 'Romance',
    authorBirthdate: '1857-04-14',
    authorBio: 'Escritor brasileiro do movimento naturalista...'
  },
  success: function(response) {
    console.log('Livro criado:', response.data);
  },
  error: function(xhr) {
    console.error('Erro:', xhr.responseJSON);
  }
});
```

### Buscar livros com filtros

```javascript
$.ajax({
  url: '/book/list',
  method: 'GET',
  data: {
    search: 'Dom Casmurro',
    author: 'Machado',
    year: 1899
  },
  success: function(response) {
    console.log('Livros encontrados:', response.data);
  }
});
```

### Atualizar um autor

```javascript
$.ajax({
  url: '/author/update',
  method: 'PUT',
  data: {
    id: 1,
    name: 'Joaquim Maria Machado de Assis',
    bio: 'Biografia atualizada com mais detalhes...'
  },
  success: function(response) {
    console.log('Autor atualizado:', response.data);
  }
});
```

---

## 🛠️ Tratamento de Erros

### ❌ Erros de Validação (422)

```json
{
  "status": "error",
  "code": 422,
  "timeResponse": "2024-01-15T10:30:00.000000Z",
  "errorMessage": "Erro de validação dos campos.",
  "errors": {
    "title": ["O título do livro é obrigatório."],
    "isbn": ["Este ISBN já está cadastrado."]
  }
}
```

### 🔍 Recurso Não Encontrado (404)

```json
{
  "status": "error",
  "code": 404,
  "timeResponse": "2024-01-15T10:30:00.000000Z",
  "data": "Não encontramos livros que satisfaçam a sua pesquisa."
}
```

### ⚠️ Conflito de Dependência (409)

```json
{
  "status": "error",
  "code": 409,
  "timeResponse": "2024-01-15T10:30:00.000000Z",
  "errorMessage": "Não é possível deletar o autor, pois ele possui livros cadastrados."
}
```

### 💥 Erro Interno (500)

```json
{
  "status": "error",
  "code": 500,
  "timeResponse": "2024-01-15T10:30:00.000000Z",
  "errorMessage": "Erro interno do servidor: [detalhes do erro]"
}
```
Atualizações futuras: imagens nas capas, função para adicionar imagens no banner, ver detalhes do autor e da editora.
---