@extends('layouts.bookLayout')

@section('content')
    <div id="bookDetails">
        <div class="container">
            <div class="infos">
                <h1>{{ $book->title }}</h1>
                <p><strong>Descrição:</strong> {{ $book->description }}</p>
                <p><strong>Ano de lançamento:</strong> {{ $book->release_year }}</p>
                <p><strong>Autores:</strong>
                    {{ $book->authors->pluck('name')->join(', ') }}
                </p>
                <p><strong>Categorias:</strong>
                    {{ $book->categories->pluck('name')->join(', ') }}
                </p>
                <p><strong>Editora:</strong> {{ $book->publisher->name }}</p>
                <p><strong>ISBN:</strong> {{ $book->isbn }}</p>
                <a href="/" class="back"><i class="fa-solid fa-arrow-left"></i> Voltar</a>
                <div class="back"><i class="fa-solid fa-pencil"></i> Editar</div>
            </div>
            <div class="img">
                <img src="{{ asset('storage/capas/' . $book->cover_image) }}" alt="Capa do livro {{ $book->title }}">
            </div>
        </div>
    </div>

    <div id="editModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Editar Livro</h2>
            <form enctype="multipart/form-data" id="editForm">
                @csrf

                <label>Título</label>
                <input type="text" name="title" value="{{ $book->title }}">

                <label>Descrição</label>
                <textarea name="description" rows="4">{{ $book->description }}</textarea>

                <label>Ano de Lançamento</label>
                <input type="number" name="release_year" value="{{ $book->release_year }}">

                <label>Autores</label>
                <input type="text" name="author" value="{{ $book->authors[0]->name }}">

                <label>Categoria</label>
                <input type="text" name="category" value="{{ $book->categories[0]->name }}">

                <label>Editora</label>
                <input type="text" name="publisher" value="{{ $book->publisher->name }}">

                <label>ISBN</label>
                <input type="text" name="isbn" value="{{ $book->isbn }}">

                <button type="submit"><i class="fa-solid fa-floppy-disk"></i> Salvar</button>
                <button href="/api/book/delete/?id={{ $book->id }}"><i class="fa-solid fa-trash"></i> Apagar</button>
            </form>
        </div>
    </div>

    <script>
        document.querySelector('.back .fa-pencil').closest('.back').addEventListener('click', () => {
            document.getElementById('editModal').style.display = 'flex';
        });

        document.querySelector('.modal .close').addEventListener('click', () => {
            document.getElementById('editModal').style.display = 'none';
        });

        window.addEventListener('click', function(event) {
            if (event.target.classList.contains('modal')) {
                event.target.style.display = 'none';
            }
        });

        const editForm = document.querySelector('#editForm');
        editForm.addEventListener('submit', async (e) => {
            e.preventDefault();

            const formData = {
                id: {{$book->id}},
                title: editForm.title.value,
                isbn: editForm.isbn?.value,
                description: editForm.description.value,
                releaseYear: editForm.release_year.value
            };

            const publisherData = {
                id: {{$book->publisher->id}},
                name: editForm.publisher?.value
            };

            const authorData = {
                id: {{$book->authors[0]->id}},
                name: editForm.author?.value
            };

            const errors = validateBookForm({
                ...formData,
                author: authorData.name,
                publisher: publisherData.name
            });

            if (errors.length > 0) {
                alert("Corrija os seguintes erros:\n\n" + errors.join('\n'));
                return;
            }

            try {
                const bookResponse = await fetch('/api/book/update', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(formData)
                });

                if (!bookResponse.ok) throw new Error('Erro ao atualizar o livro');

                const publisherResponse = await fetch('/api/publisher/update', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(publisherData)
                });

                if (!publisherResponse.ok) throw new Error('Erro ao atualizar a editora');

                const authorResponse = await fetch('/api/author/update', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(authorData)
                });

                if (!authorResponse.ok) throw new Error('Erro ao atualizar o autor');

                alert('Atualização realizada com sucesso!');
                window.location.reload();

            } catch (error) {
                console.error('Erro ao atualizar:', error);
                alert('Erro durante a atualização. Verifique os dados e tente novamente.');
            }

        })

        function validateBookForm(formData) {
            const errors = [];

            const currentYear = new Date().getFullYear();

            const title = formData.title?.trim() || '';
            if (title.length < 3 || title.length > 255) {
                errors.push("Título deve ter entre 3 e 255 caracteres.");
            }

            const isbn = formData.isbn?.trim() || '';
            if (!/^[0-9\-]{10,13}$/.test(isbn)) {
                errors.push("ISBN deve ter entre 10 e 13 caracteres numéricos (com ou sem hífen).");
            }

            const description = formData.description?.trim() || '';
            if (description.length === 0 || description.length > 255) {
                errors.push("Descrição é obrigatória e deve ter no máximo 255 caracteres.");
            }

            const releaseYear = parseInt(formData.releaseYear, 10);
            if (!/^\d{4}$/.test(formData.releaseYear) || releaseYear < 1000 || releaseYear > currentYear) {
                errors.push(`Ano de lançamento deve ter 4 dígitos entre 1000 e ${currentYear}.`);
            }

            const author = formData.author?.trim();
            if (author && (author.length < 3 || author.length > 255)) {
                errors.push("Nome do autor deve ter entre 3 e 255 caracteres.");
            }

            const publisher = formData.publisher?.trim();
            if (publisher && (publisher.length < 3 || publisher.length > 255)) {
                errors.push("Nome da editora deve ter entre 3 e 255 caracteres.");
            }

            return errors;
        }
    </script>
@endsection
