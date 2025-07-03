@extends('layouts.homeLayout')

@section('main')
    <div class="main">
        <div class="container">

            <div id="filters">
                <form class="filterButtons" id="filterForm">

                    <div class="filtersWrapper">
                        <label class="checkboxBtn">
                            <input type="checkbox" name="categoria" hidden>
                            <i class="fa-solid fa-list"></i> Categoria
                        </label>

                        <label class="checkboxBtn">
                            <input type="checkbox" name="autor" hidden>
                            <i class="fa-solid fa-user-tie"></i> Autor
                        </label>

                        <label class="checkboxBtn">
                            <input type="checkbox" name="editora" hidden>
                            <i class="fa-brands fa-cloudflare"></i> Editora
                        </label>
                    </div>

                    <div class="searchWrapper">
                        <input type="text" name="searchFilter" id="searchFilter" class="formControlText"
                            placeholder="Categoria, autor...">
                        <button type="submit" class="bookDetails">Pesquisar</button>
                    </div>

                </form>
                <hr>
            </div>

            <section id="render">

                <div id="books">
                    {{-- Os livros serão inseridos aqui via JS --}}
                </div>

                <div id="order">
                    <p>Ordenar</p>
                    <button class="btnOrder" title="A-z"><i class="fa-solid fa-arrow-up-z-a"></i></button>
                    <button class="btnOrder" title="Z-a"><i class="fa-solid fa-arrow-down-z-a"></i></button>
                    <button class="btnOrder" title="Data de lançamento"><i class="fa-solid fa-calendar-days"></i></button>
                    <button class="btnOrder" title="Categoria"><i class="fa-solid fa-list"></i></button>
                    <button class="btnOrder" title="Autor"><i class="fa-solid fa-user-tie"></i></button>
                </div>

            </section>
        </div>
    </div>

    <script>
        books.innerHTML = "Carregando livros.."
        fetch(`/api/book/list`)
            .then(res => res.json())
            .then(data => {
                books.innerHTML = '';
                if (data.code != 200) {
                    books.innerHTML = data['data'];
                    return;
                }

                data['data'].forEach(book => {
                    const bookTitle = book.title;
                    const bookDescription = book.description;
                    const shortBookDescription = bookDescription.substring(0, 100) + '...';
                    const bookReleaseYear = book.release_year;
                    createBook(bookTitle, shortBookDescription, bookReleaseYear)
                });
            })
            .catch(err => console.error("Erro:", err));

        function createBook(title, description, releaseYear) {
            const book = document.createElement('div');
            book.classList.add('book');

            const h3 = document.createElement('h3');
            h3.textContent = `${title} - ${releaseYear}`;

            const bookImg = document.createElement('div');
            bookImg.classList.add('bookImg');

            const bookDescription = document.createElement('div');
            bookDescription.classList.add('bookDescription');
            const small = document.createElement('small');
            small.textContent = description;
            bookDescription.appendChild(small);

            const btn = document.createElement('button');
            btn.classList.add('bookDetails');
            btn.textContent = 'Ver mais';

            book.appendChild(h3);
            book.appendChild(bookImg);
            book.appendChild(bookDescription);
            book.appendChild(btn);

            books.appendChild(book);
        }

        
    </script>
@endsection
