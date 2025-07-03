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

                        <label class="checkboxBtn">
                            <input type="checkbox" name="isbn" hidden>
                            <i class="fa-solid fa-hashtag"></i> ISBN
                        </label>

                        <label class="checkboxBtn">
                            <input type="checkbox" name="year" hidden>
                            <i class="fa-solid fa-calendar-days"></i> Ano
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

                </div>

                <div id="order">
                    <p>Ordenar</p>
                    <button class="btnOrder" title="A-z" onclick="order('aZ')"><i
                            class="fa-solid fa-arrow-up-z-a"></i></button>
                    <button class="btnOrder" title="Z-a" onclick="order('zA')"><i
                            class="fa-solid fa-arrow-down-z-a"></i></button>
                    <button class="btnOrder" title="Data de lançamento" onclick="order('date')"><i
                            class="fa-solid fa-calendar-days"></i></button>
                    <button class="btnOrder" title="Categoria" onclick="order('category')"><i
                            class="fa-solid fa-list"></i></button>
                    <button class="btnOrder" title="Autor" onclick="order('autor')"><i
                            class="fa-solid fa-user-tie"></i></button>
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
                    let bookCategories = [];
                    let bookAuthors = [];
                    let bookPublishers = book['publisher']['name'];

                    book['categories'].forEach(element => {
                        bookCategories.push(element['name']);
                    });

                    book['authors'].forEach(element => {
                        bookAuthors.push(element['name']);
                    });

                    createBook(bookTitle, shortBookDescription, bookReleaseYear, bookCategories, bookAuthors, bookPublishers);
                });
            })
            .catch(err => console.error("Erro:", err));

        function createBook(title, description, releaseYear, bookCategories, bookAuthors, bookPublishers) {
            const book = document.createElement('div');
            book.classList.add('book');

            const h3 = document.createElement('h3');
            h3.textContent = `${title} - ${releaseYear}`;

            const bookImg = document.createElement('div');
            bookImg.classList.add('bookImg');

            const bookDescription = document.createElement('div');
            bookDescription.classList.add('bookDescription');

            const smallDesc = document.createElement('small');
            smallDesc.innerHTML = description + '<br>';
            bookDescription.appendChild(smallDesc);

            const smallCategories = document.createElement('small');
            smallCategories.innerHTML = `Categorias: ${bookCategories.join(', ')}` + '<br>';
            bookDescription.appendChild(smallCategories);

            const smallAuthors = document.createElement('small');
            smallAuthors.innerHTML = `Autores: ${bookAuthors.join(', ')}` + '<br>';
            bookDescription.appendChild(smallAuthors);

            const smallPublishers = document.createElement('small');
            smallPublishers.innerHTML = `Publicadoras: ${bookPublishers}`;
            bookDescription.appendChild(smallPublishers)

            const btn = document.createElement('button');
            btn.classList.add('bookDetails');
            btn.textContent = 'Ver mais';

            book.appendChild(h3);
            book.appendChild(bookImg);
            book.appendChild(bookDescription);
            book.appendChild(btn);

            books.appendChild(book);
        }

        function order(arg) {
            const booksContainer = document.querySelector('#books');
            const bookElements = Array.from(document.querySelectorAll('.book'));

            let sortedBooks;

            if (arg === 'aZ') {
                sortedBooks = bookElements.sort((a, b) => {
                    const titleA = a.querySelector('h3').textContent.toLowerCase();
                    const titleB = b.querySelector('h3').textContent.toLowerCase();
                    return titleA.localeCompare(titleB);
                });
            } else if (arg === 'zA') {
                sortedBooks = bookElements.sort((a, b) => {
                    const titleA = a.querySelector('h3').textContent.toLowerCase();
                    const titleB = b.querySelector('h3').textContent.toLowerCase();
                    return titleB.localeCompare(titleA);
                });
            } else if (arg === 'date') {
                sortedBooks = bookElements.sort((a, b) => {
                    const yearA = parseInt(a.querySelector('h3').textContent.split('-').pop().trim());
                    const yearB = parseInt(b.querySelector('h3').textContent.split('-').pop().trim());
                    return yearB - yearA;
                });
            } else if (arg === 'category') {
                sortedBooks = bookElements.sort((a, b) => {
                    const catA = a.querySelector('.bookDescription small:nth-child(2)').textContent.toLowerCase();
                    const catB = b.querySelector('.bookDescription small:nth-child(2)').textContent.toLowerCase();
                    return catA.localeCompare(catB);
                });
            } else if (arg === 'autor') {
                sortedBooks = bookElements.sort((a, b) => {
                    const authA = a.querySelector('.bookDescription small:nth-child(3)').textContent.toLowerCase();
                    const authB = b.querySelector('.bookDescription small:nth-child(3)').textContent.toLowerCase();
                    return authA.localeCompare(authB);
                });
            }

            booksContainer.innerHTML = '';
            sortedBooks.forEach(book => booksContainer.appendChild(book));
        }

        document.querySelectorAll('.filtersWrapper input[type="checkbox"]').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                if (this.checked) {
                    document.querySelectorAll('.filtersWrapper input[type="checkbox"]').forEach(other => {
                        if (other !== this) other.checked = false;
                    });
                }
            });
        });

        const filterForm = document.querySelector('#filterForm');
        filterForm.addEventListener('submit', (e) => {
            e.preventDefault();

            books.innerHTML = "Carregando livros.."

            const filter = document.querySelector('.filtersWrapper input[type="checkbox"]:checked').name;
            const searchFilter = document.querySelector('#searchFilter').value.trim();

            if (!validateSearchField(filter, searchFilter)) {
                books.innerHTML = 'Busca inválida.';
                return;
            }

            let arg;
            switch (filter) {
                case 'categoria':
                    arg = 'category';
                    break;
                case 'autor':
                    arg = 'author';
                    break;
                case 'editora':
                    arg = 'publisher';
                    break;
                case 'isbn':
                    arg = 'isbn';
                    break;
                case 'year':
                    arg = 'year';
                    break;
                default:
                    arg = 'search';
                    break;
            }

            fetch(`/api/book/list/?${arg}=${encodeURIComponent(searchFilter)}`)
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
                        let bookCategories = [];
                        let bookAuthors = [];
                        let bookPublishers = book['publisher']['name'];

                        book['categories'].forEach(element => {
                            bookCategories.push(element['name']);
                        });

                        book['authors'].forEach(element => {
                            bookAuthors.push(element['name']);
                        });

                        createBook(bookTitle, shortBookDescription, bookReleaseYear, bookCategories,
                            bookAuthors, bookPublishers)
                    });
                })
                .catch(err => console.error("Erro:", err));

            function validateSearchField(filter, searchValue) {
                const currentYear = new Date().getFullYear();

                const value = searchValue.trim();

                switch (filter) {
                    case 'categoria':
                    case 'autor':
                    case 'editora':
                        return value.length >= 3 && value.length <= 255;
                    case 'isbn':
                        return value.length >= 10 && value.length <= 13 && /^[0-9\-]+$/.test(value);

                    case 'date':
                        const year = parseInt(value, 10);
                        return /^\d{4}$/.test(value) && year >= 1000 && year <= currentYear;

                    default:
                        return value.length >= 3 && value.length <= 255;
                }
            }
        })
    </script>
@endsection
