<div class="header">
    <div class="container between">
        <h1><a href="/" id="home">BookMaster</a></h1>
        <form class="searchBox">
            <input type="text" name="searchField" id="searchField" class="formControlText" min="3" max="255"
                placeholder="Pesquisar..">
            <button type="submit" class="btn" id="searchSubmit"><i
                    class="fa-solid fa-magnifying-glass"></i></button>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const searchBox = document.querySelector('.searchBox');
        const searchField = document.querySelector('#searchField');
        const books = document.querySelector('#books');

        function validated(text) {
            const regex = /^.{3,255}$/;
            return typeof text === 'string' && regex.test(text);
        }

        searchBox.addEventListener('submit', (e) => {
            e.preventDefault();
            const text = searchField.value.trim();

            if (text === "") {
                books.innerHTML = 'O campo de busca está vazio.';
                return;
            }

            if (!validated(text)) {
                books.innerHTML = 'A busca deve conter entre 3 e 255 caracteres.';
                return;
            }

            fetch(`/api/book/list?search=${encodeURIComponent(text)}`)
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
                        const shortBookDescription = bookDescription.substring(0, 100) +
                            '...';
                        const bookReleaseYear = book.release_year;
                        let bookCategories = []
                        let bookAuthors = []

                        book['categories'].forEach(element => {
                            bookCategories.push(element['name']);
                        });

                        book['authors'].forEach(element => {
                            bookAuthors.push(element['name']);
                        });

                        createBook(bookTitle, shortBookDescription, bookReleaseYear,
                            bookCategories, bookAuthors)
                    });
                })
                .catch(err => console.error("Erro:", err));
        });

        function createBook(title, description, releaseYear, bookCategories, bookAuthors) {
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
            smallAuthors.innerHTML = `Autores: ${bookAuthors.join(', ')}`;
            bookDescription.appendChild(smallAuthors);

            const btn = document.createElement('button');
            btn.classList.add('bookDetails');
            btn.textContent = 'Ver mais';

            book.appendChild(h3);
            book.appendChild(bookImg);
            book.appendChild(bookDescription);
            book.appendChild(btn);

            books.appendChild(book);
        }
    });
</script>
