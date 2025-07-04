@extends('layouts.storeLayout')

@section('content')
    <div class="createBook">
        <div class="container">
            <h2>Dados do livro</h2>
            <form id="createBookForm" method="POST">
                @csrf
                <fieldset>
                    <legend>Livro</legend>
                    <label for="titulo">Título</label>
                    <input type="text" name="titulo" id="titulo">

                    <label for="isbn">ISBN</label>
                    <input type="text" name="isbn" id="isbn">

                    <label for="description">descrição</label>
                    <textarea name="description" id="description" rows="4"></textarea>

                    <label for="releaseYear">Ano de publicação</label>
                    <input type="number" name="releaseYear" id="releaseYear">
                </fieldset>

                <fieldset>
                    <legend>Autor</legend>
                    <select name="nameAuthorSelect" id="nameAuthorSelect">
                        <option>Autor</option>
                        @foreach ($authors as $author)
                            <option value="{{ $author->name }}">{{ $author->name }}</option>
                        @endforeach
                    </select>
                    <label for="name">Nome</label>
                    <input type="text" name="name" id="name">

                    <label for="authorBitrhdate">Aniversário</label>
                    <input type="date" name="authorBitrhdate" id="authorBitrhdate">

                    <label for="authorBio">Biografia</label>
                    <textarea name="authorBio" id="authorBio" rows="6"></textarea>
                </fieldset>

                <fieldset>
                    <legend>Categoria</legend>
                    <select name="categorySelect" id="categorySelect">
                        <option>Categoria</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->name }}">{{ $category->name }}</option>
                        @endforeach
                    </select>
                    <label for="category">Nome</label>
                    <input type="text" name="category" id="category">
                </fieldset>

                <fieldset>
                    <legend>Editora</legend>
                    <select name="publisherSelect" id="publisherSelect">
                        <option>Editora</option>
                        @foreach ($publishers as $publisher)
                            <option value="{{ $publisher->name }}">{{ $publisher->name }}</option>
                        @endforeach
                    </select>
                    <label for="publisher">Nome</label>
                    <input type="text" name="publisher" id="publisher">

                    <label for="country">País</label>
                    <input type="text" name="country" id="country">
                </fieldset>
                <button type="submit" class="btn"><i class="fa-solid fa-plus"></i> Criar</button>
            </form>
            <a href="/" class="back"><i class="fa-solid fa-arrow-left"></i> Voltar</a>
        </div>
    </div>

    <script>
        const authors = @json($authors);
        const publishers = @json($publishers);
        const categories = @json($categories);

        const authorSelect = document.getElementById('nameAuthorSelect');
        const publisherSelect = document.getElementById('publisherSelect');
        const categorySelect = document.getElementById('categorySelect');

        authorSelect.addEventListener('change', () => {
            const selectedName = authorSelect.value;
            const selectedAuthor = authors.find(author => author.name === selectedName);

            if (selectedAuthor) {
                document.getElementById('name').value = selectedAuthor.name || '';
                document.getElementById('authorBitrhdate').value = selectedAuthor.birthdate || '';
                document.getElementById('authorBio').value = selectedAuthor.bio || '';
            }
        });

        publisherSelect.addEventListener('change', () => {
            const selectedName = publisherSelect.value;
            const selectedPublisher = publishers.find(p => p.name === selectedName);

            if (selectedPublisher) {
                document.getElementById('publisher').value = selectedPublisher.name || '';
                document.getElementById('country').value = selectedPublisher.country || '';
            }
        });

        categorySelect.addEventListener('change', () => {
            const selectedName = categorySelect.value;
            const selectedCategory = categories.find(c => c.name === selectedName);

            if (selectedCategory) {
                document.getElementById('category').value = selectedCategory.name || '';
            }
        });

        const createBookForm = document.querySelector('#createBookForm');
        createBookForm.addEventListener('submit', (e) => {
            e.preventDefault();

            let errors = [];

            const isEmpty = val => !val.trim();
            const isMinLength = (val, min) => val.trim().length >= min;
            const isMaxLength = (val, max) => val.trim().length <= max;
            const isDigits = (val, digits) => /^\d+$/.test(val) && val.length === digits;
            const isValidYear = (val) => parseInt(val) >= 1000 && parseInt(val) <= new Date().getFullYear();
            const isDateBeforeToday = (dateStr) => {
                const today = new Date().toISOString().split('T')[0];
                return dateStr < today;
            };

            const title = document.getElementById('titulo').value;
            const isbn = document.getElementById('isbn').value;
            const description = document.getElementById('description').value;
            const releaseYear = document.getElementById('releaseYear').value;

            const author = document.getElementById('name').value;
            const authorBirthdate = document.getElementById('authorBitrhdate').value;
            const authorBio = document.getElementById('authorBio').value;

            const category = document.getElementById('category').value;

            const publisher = document.getElementById('publisher').value;
            const publisherCountry = document.getElementById('country').value;

            if (isEmpty(title) || !isMaxLength(title, 255)) errors.push(
                "Título é obrigatório e deve ter até 255 caracteres.");
            if (isEmpty(isbn)) errors.push("ISBN é obrigatório.");
            if (isEmpty(description)) errors.push("Descrição é obrigatória.");
            if (!isDigits(releaseYear, 4) || !isValidYear(releaseYear)) errors.push("Ano de publicação inválido.");

            if (isEmpty(author) || !isMinLength(author, 3) || !isMaxLength(author, 255)) errors.push(
                "Nome do autor deve ter entre 3 e 255 caracteres.");
            if (isEmpty(authorBirthdate) || !isDateBeforeToday(authorBirthdate)) errors.push(
                "Data de nascimento inválida.");
            if (isEmpty(authorBio) || !isMinLength(authorBio, 3)) errors.push(
                "Biografia do autor deve ter no mínimo 3 caracteres.");

            if (isEmpty(category) || !isMinLength(category, 3) || !isMaxLength(category, 255)) errors.push(
                "Nome da categoria deve ter entre 3 e 255 caracteres.");

            if (isEmpty(publisher) || !isMinLength(publisher, 3) || !isMaxLength(publisher, 255)) errors.push(
                "Nome da editora deve ter entre 3 e 255 caracteres.");
            if (!isEmpty(publisherCountry) && (!isMinLength(publisherCountry, 3) || !isMaxLength(publisherCountry,
                    255))) {
                errors.push("País da editora deve ter entre 3 e 255 caracteres se preenchido.");
            }

            if (errors.length > 0) {
                alert("Erros encontrados:\n\n" + errors.join("\n"));
                return;
            }

            const body = {
                title: title,
                isbn: isbn,
                publisher: publisher,
                publisherCountry: publisherCountry,
                releaseYear: releaseYear,
                description: description,
                author: author,
                category: category,
                authorBirthdate: authorBirthdate,
                authorBio: authorBio,
            }

            fetch('api/book/store', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/json'},
                    body: JSON.stringify(body)
                })
                .then(response => response.json())
                .then(data => {
                    if (data.code == 422) {
                        alert(data.aditionalFields);
                        return;
                    }

                    if (data.code == 500) {
                        alert('erro interno do servidor');
                        return;
                    }

                    alert(data.message);
                    window.location.href = `/details/?id=${data.data.id}`
                })
        })
    </script>
@endsection
