import './bootstrap';
import { Modal, Popover, Toast } from 'bootstrap';
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

function openCountry(index) {
    currentIndex = index;

    const button = buttons[index];
    const card = button.closest('.card');

    title.textContent = card.querySelector('.card-title').textContent;
    text.textContent = 'Загрузка...';
    modal.show();

    fetch(button.dataset.url, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
        .then(function (response) {
            return response.text();
        })
        .then(function (html) {
            if (currentIndex === index) {
                text.innerHTML = html;
            }
        });
}

for (let i = 0; i < buttons.length; i++) {
    buttons[i].onclick = function () {
        openCountry(i);
    };
}

document.addEventListener('keydown', function (event) {
    if (!modalElement.classList.contains('show')) {
        return;
    }

    let nextIndex = currentIndex;

    if (event.key === 'ArrowRight') {
        nextIndex++;
    } else if (event.key === 'ArrowLeft') {
        nextIndex--;
    } else {
        return;
    }

    event.preventDefault();

    if (nextIndex >= buttons.length) {
        nextIndex = 0;
    }

    if (nextIndex < 0) {
        nextIndex = buttons.length - 1;
    }

    openCountry(nextIndex);
});
