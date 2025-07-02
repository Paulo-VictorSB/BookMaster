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

        function validated(text) {
            const regex = /^.{3,255}$/;
            return typeof text === 'string' && regex.test(text);
        }

        searchBox.addEventListener('submit', (e) => {
            e.preventDefault();
            const text = searchField.value.trim();

            if (text === "") {
                console.error("O campo de busca está vazio.");
                return;
            }

            if (!validated(text)) {
                console.error("A busca deve conter entre 3 e 255 caracteres.");
                return;
            }

            fetch(`/api/book/list?search=${encodeURIComponent(text)}`)
                .then(res => res.json())
                .then(data => console.log(data))
                .catch(err => console.error("Erro:", err));
        });
    });
</script>
