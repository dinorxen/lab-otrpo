import './country-form';
import { Modal, Toast } from 'bootstrap';
import '@fortawesome/fontawesome-free/js/all.js';
import $ from 'jquery';

window.$ = window.jQuery = $;

for (const form of document.querySelectorAll('form[data-confirm]')) {
    form.addEventListener('submit', function (event) {
        if (!window.confirm(form.dataset.confirm)) {
            event.preventDefault();
        }
    });
}

const downloadButton = document.getElementById('downloadButton');
const downloadToast = document.getElementById('downloadToast');

const toast = new Toast(downloadToast, {
    delay: 3000
});

downloadButton.onclick = function () {
    toast.show();
};

const modalElement = document.getElementById('countryModal');
const modal = new Modal(modalElement);

const title = document.getElementById('countryModalTitle');
const text = document.getElementById('countryModalText');
const buttons = document.querySelectorAll('.details-button');

let currentIndex = 0;
let detailRequest;

async function openCountry(index) {
    detailRequest?.abort();
    const request = new AbortController();
    detailRequest = request;
    currentIndex = index;

    const button = buttons[index];

    title.textContent = button.closest('.card').querySelector('.card-title').textContent.trim();
    text.textContent = 'Загрузка...';
    modal.show();

    try {
        const response = await fetch(button.dataset.url, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' },
            signal: request.signal,
        });
        if (!response.ok) {
            throw new Error('Country details request failed');
        }

        const html = await response.text();
        if (!request.signal.aborted) {
            text.innerHTML = html;
        }
    } catch {
        if (!request.signal.aborted) {
            text.textContent = 'Не удалось загрузить подробности. Попробуйте открыть карточку ещё раз.';
        }
    }
}

buttons.forEach((button, index) => button.addEventListener('click', () => openCountry(index)));
modalElement.addEventListener('hide.bs.modal', () => detailRequest?.abort());

document.addEventListener('keydown', event => {
    if (!buttons.length || !modalElement.classList.contains('show')) {
        return;
    }

    if (event.key !== 'ArrowLeft' && event.key !== 'ArrowRight') {
        return;
    }

    event.preventDefault();

    const step = event.key === 'ArrowRight' ? 1 : -1;
    openCountry((currentIndex + step + buttons.length) % buttons.length);
});
