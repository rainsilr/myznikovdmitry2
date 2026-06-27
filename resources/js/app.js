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

const forms = document.querySelectorAll('[data-client-form]');

forms.forEach((form) => {
    form.addEventListener('submit', (event) => {
        let hasErrors = false;

        form.querySelectorAll('.client-error').forEach((error) => error.remove());

        form.querySelectorAll('input[required], textarea[required], select[required]').forEach((field) => {
            field.classList.remove('is-invalid');

            const value = field.value.trim();
            let message = '';

            // Проверяем обязательные поля до отправки формы на сервер.
            if (!value) {
                message = 'Заполните это поле.';
            } else if (field.type === 'email' && !/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(value)) {
                message = 'Введите корректный email.';
            } else if (field.minLength > 0 && value.length < field.minLength) {
                message = `Минимум символов: ${field.minLength}.`;
            } else if (field.dataset.pattern === 'fio' && !/^[А-ЯЁа-яё]+(?:-[А-ЯЁа-яё]+)?\s+[А-ЯЁа-яё]+(?:-[А-ЯЁа-яё]+)?\s+[А-ЯЁа-яё]+(?:-[А-ЯЁа-яё]+)?$/.test(value)) {
                message = 'ФИО должно состоять ровно из 3 слов кириллицей.';
            } else if (field.dataset.pattern === 'login' && !/^[A-Za-z0-9]+$/.test(value)) {
                message = 'Логин может содержать только латиницу и цифры.';
            } else if (field.dataset.pattern === 'phone' && !/^\+7\(\d{3}\)\d{3}-\d{2}-\d{2}$/.test(value)) {
                message = 'Телефон должен быть в формате +7(XXX)XXX-XX-XX.';
            }

            if (message) {
                hasErrors = true;
                field.classList.add('is-invalid');

                const error = document.createElement('small');
                error.className = 'client-error';
                error.textContent = message;
                field.insertAdjacentElement('afterend', error);
            }
        });

        if (hasErrors) {
            event.preventDefault();
        }
    });
});
