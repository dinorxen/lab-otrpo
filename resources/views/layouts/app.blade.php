<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Глобус')</title>
    <link rel="stylesheet" href="{{ mix('css/app.css') }}">
    <script defer src="{{ mix('js/app.js') }}"></script>
</head>
<body>
    <header class="site-header">
        <nav class="navbar" data-bs-theme="dark" aria-label="Главная навигация">
            <div class="container">
                <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('countries.index') }}">
                    <span class="logo-icon" aria-hidden="true">G</span>
                    <span>Глобус</span>
                </a>
                <button type="button" id="downloadButton" class="btn download-button">Загрузить</button>
            </div>
        </nav>
    </header>

    <main class="container py-4 py-md-5">
        @if (session('success'))
            <div class="alert alert-success" role="alert">{{ session('success') }}</div>
        @endif

        @yield('content')
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

    {{-- Модальное окно и уведомление нужны скрипту app.js на каждой странице --}}
    <div class="modal fade" id="countryModal" tabindex="-1" aria-labelledby="countryModalTitle" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h2 class="modal-title fs-4" id="countryModalTitle"></h2>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Закрыть"></button>
                </div>
                <div class="modal-body">
                    <div id="countryModalText"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Закрыть</button>
                </div>
            </div>
        </div>
    </div>

    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="downloadToast" class="toast" role="status">
            <div class="toast-header">
                <i class="fa-solid fa-spinner fa-spin me-2" aria-hidden="true"></i>
                <strong class="me-auto">Уведомление</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Закрыть"></button>
            </div>
            <div class="toast-body">
                На текущий момент этот функционал недоступен.
            </div>
        </div>
    </div>
</body>
</html>