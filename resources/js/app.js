const slider = document.querySelector('[data-slider-track]');

if (slider) {
    const slides = [...slider.querySelectorAll('.slide')];
    const prevButton = document.querySelector('[data-slider-prev]');
    const nextButton = document.querySelector('[data-slider-next]');
    let activeIndex = 0;
    let timerId = null;

    // Показывает выбранный слайд и скрывает остальные изображения.
    const showSlide = (index) => {
        activeIndex = (index + slides.length) % slides.length;

        slides.forEach((slide, slideIndex) => {
            slide.classList.toggle('is-active', slideIndex === activeIndex);
        });
    };

    // Перезапускает автоматическое переключение после ручного нажатия.
    const restartTimer = () => {
        window.clearInterval(timerId);
        timerId = window.setInterval(() => showSlide(activeIndex + 1), 5000);
    };

    prevButton?.addEventListener('click', () => {
        showSlide(activeIndex - 1);
        restartTimer();
    });

    nextButton?.addEventListener('click', () => {
        showSlide(activeIndex + 1);
        restartTimer();
    });

    restartTimer();
}
