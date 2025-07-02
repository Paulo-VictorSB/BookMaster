<div class="hero">
    <div class="container">
        <div class="carousel">
            <div class="slider" id="slider">
                <div class="slide clone">Livro 4</div>
                <div class="slide">Livro 1</div>
                <div class="slide">Livro 2</div>
                <div class="slide">Livro 3</div>
                <div class="slide">Livro 4</div>
                <div class="slide clone">Livro 1</div>
            </div>
        </div>
    </div>
</div>


<script>
    const slider = document.getElementById('slider');
    const slides = document.querySelectorAll('.slide');
    let index = 1;
    const slideWidth = slides[0].clientWidth;

    slider.style.transform = `translateX(-${index * slideWidth}px)`;

    function goToSlide(i) {
        slider.style.transition = 'transform 0.5s ease';
        slider.style.transform = `translateX(-${i * slideWidth}px)`;
        index = i;
    }

    function jumpToSlide(i) {
        slider.style.transition = 'none';
        slider.style.transform = `translateX(-${i * slideWidth}px)`;
        index = i;
    }

    setInterval(() => {
        goToSlide(index + 1);

        setTimeout(() => {
            if (index === slides.length - 1) {
                jumpToSlide(1);
            }
            if (index === 0) {
                jumpToSlide(slides.length - 2);
            }
        }, 500);
    }, 5000);
</script>
