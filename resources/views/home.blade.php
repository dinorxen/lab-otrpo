<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Глобус</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <script defer src="{{ mix('js/app.js') }}"></script>
</head>
<body>
    <header class="site-header">
        <nav class="navbar" data-bs-theme="dark" aria-label="Главная навигация">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center gap-2" href="#countries">
                    <span class="logo-icon" aria-hidden="true">G</span>
                    <span>Глобус</span>
                </a>
                <button type="button" id="downloadButton" class="btn download-button">Загрузить</button>
            </div>
        </nav>
    </header>

    <main class="container py-4 py-md-5">
        <h1 class="mb-4" id="countries">Страны</h1>
        <div class="row g-4 country-grid">
            <div class="col-12 col-md-6 col-lg-4 col-xl-4 col-xxl-4 country-column">
                <div class="card country-card h-100">
                    <div class="flag-area">
                        <img class="card-img-top img-fluid" src="https://thumb.wikimedia.org/wikipedia/commons/thumb/c/c3/Flag_of_France.svg/1280px-Flag_of_France.svg.png?utm_source=ru.wikipedia.org&amp;utm_campaign=imageinfo&amp;utm_content=thumbnail" alt="Флаг Франции">
                        <span class="country-label">Страна</span>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h2 class="card-title h4">Франция</h2>
                        <p class="card-text mb-0 pb-4">Форма правления: Президентско-парламентская республика.<br>Площадь: 643 801 км²</p>
                        <button class="btn btn-primary mt-auto align-self-center details-button" type="button">Подробнее</button>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-4 col-xl-4 col-xxl-4 country-column">
                <div class="card country-card h-100">
                    <div class="flag-area">
                        <img class="card-img-top img-fluid" src="https://thumb.wikimedia.org/wikipedia/commons/thumb/4/41/Flag_of_Austria.svg/330px-Flag_of_Austria.svg.png?utm_source=ru.wikipedia.org&amp;utm_campaign=parser&amp;utm_content=thumbnail" alt="Флаг Австрии">
                        <span class="country-label">Страна</span>
                    </div>
                    <div class="card-body d-flex flex-column"">
                        <h2 class="card-title h4">Австрия</h2>
                        <p class="card-text mb-0 pb-4">Форма правления: Федеративная парламентская республика.<br>Площадь: 83 879 км²</p>
                        <button class="btn btn-primary mt-auto align-self-center details-button" type="button">Подробнее</button>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-4 col-xl-4 col-xxl-4 country-column">
                <div class="card country-card h-100">
                    <div class="flag-area">
                        <img class="card-img-top img-fluid" src="https://thumb.wikimedia.org/wikipedia/commons/thumb/2/20/Flag_of_the_Netherlands.svg/1280px-Flag_of_the_Netherlands.svg.png?utm_source=ru.wikipedia.org&amp;utm_campaign=imageinfo&amp;utm_content=thumbnail" alt="Флаг Нидерландов">
                        <span class="country-label">Страна</span>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h2 class="card-title h4">Нидерланды</h2>
                        <p class="card-text mb-0 pb-4">Форма правления: Конституционная монархия.<br>Площадь: 41 543 км²</p>
                        <button class="btn btn-primary mt-auto align-self-center details-button" type="button">Подробнее</button>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-4 col-xl-4 col-xxl-4 country-column">
                <div class="card country-card h-100">
                    <div class="flag-area">
                        <img class="card-img-top img-fluid" src="https://thumb.wikimedia.org/wikipedia/commons/thumb/9/9e/Flag_of_Japan.svg/250px-Flag_of_Japan.svg.png?utm_source=ru.wikipedia.org&amp;utm_campaign=parser&amp;utm_content=thumbnail" alt="Флаг Японии">
                        <span class="country-label">Страна</span>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h2 class="card-title h4">Япония</h2>
                        <p class="card-text mb-0 pb-4">Форма правления: Парламентская монархия.<br>Площадь: 372,8 тыс. км²</p>
                        <button class="btn btn-primary mt-auto align-self-center details-button" type="button">Подробнее</button>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-4 col-xl-4 col-xxl-4 country-column">
                <div class="card country-card h-100">
                    <div class="flag-area">
                        <img class="card-img-top img-fluid" src="https://thumb.wikimedia.org/wikipedia/commons/thumb/0/03/Flag_of_Italy.svg/1920px-Flag_of_Italy.svg.png?utm_source=ru.wikipedia.org&amp;utm_campaign=imageinfo&amp;utm_content=thumbnail" alt="Флаг Италии">
                        <span class="country-label">Страна</span>
                    </div>
                    <div class="card-body d-flex flex-column">
                        <h2 class="card-title h4">Италия</h2>
                        <p class="card-text mb-0 pb-4">Форма правления: Парламентская республика.<br>Площадь: 302 073 км²</p>
                        <button class="btn btn-primary mt-auto align-self-center details-button" type="button">Подробнее</button>
                    </div>
                </div>
            </div>

            <div class="col-12 col-sm-12 col-md-6 col-lg-4 col-xl-4 col-xxl-4">
                <div class="coming-soon h-100 d-flex align-items-center justify-content-center"
                    tabindex="0">
                    <span>Скоро появится</span>
                </div>
            </div>
        </div>
    </main>

    <footer class="site-footer py-4">
        <div class="container d-flex flex-wrap justify-content-between align-items-center gap-3">
            <p class="mb-0">Автор: Ибраев Д.Б.</p>
            <div class="d-flex align-items-center gap-3">
                <a href="https://vk.ru/" target="_blank" rel="noopener noreferrer"><img class="img-fluid social-icon" src="https://img.icons8.ru/?size=100&amp;id=Mgea1VSzCVcS&amp;format=png&amp;color=000000" alt="VK"></a>
                <a href="https://t.me/" target="_blank" rel="noopener noreferrer"><img class="img-fluid social-icon" src="https://img.icons8.ru/?size=100&amp;id=85428&amp;format=png&amp;color=000000" alt="Telegram"></a>
                <a href="https://youtube.com/" target="_blank" rel="noopener noreferrer"><img class="img-fluid social-icon" src="https://img.icons8.ru/?size=100&amp;id=62852&amp;format=png&amp;color=000000" alt="YouTube"></a>
            </div>
        </div>
    </footer>

<div
    class="modal fade"
    id="countryModal"
    tabindex="-1"
    aria-labelledby="countryModalTitle"
    aria-hidden="true">

    <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title fs-4" id="countryModalTitle"></h2>
                    <button
                        type="button"
                        class="btn-close"
                        data-bs-dismiss="modal"
                        aria-label="Закрыть">
                    </button>
                </div>
                <div class="modal-body">
                    <img
                    id="countryModalImage"
                    class="img-fluid w-75 d-block mx-auto my-3"
                    alt="Столица">
                    <div id="countryModalText"></div>
                </div>

                <div class="modal-footer">
                    <button
                        type="button"
                        class="btn btn-secondary"
                        data-bs-dismiss="modal">
                        Закрыть
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="downloadToast" class="toast" role="status">
            <div class="toast-header">
                <i class="fa-solid fa-spinner fa-spin me-2" aria-hidden="true"></i>
                <strong class="me-auto">Уведомление</strong>
                <button
                    type="button"
                    class="btn-close"
                    data-bs-dismiss="toast"
                    aria-label="Закрыть">
                </button>
            </div>

            <div class="toast-body">
                На текущий момент этот функционал недоступен.
            </div>
        </div>
    </div>
</body>
</html> 