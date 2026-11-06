const name = document.querySelector('[data-name-check-url]');

if (name) {
    setupCountryForm(name);
}

function setupCountryForm(name) {
    const form = name.form;
    const area = form.elements.area;
    const description = form.elements.description;
    const government = form.elements.government_form;
    const nameFeedback = document.getElementById('name-feedback');
    const areaFeedback = document.getElementById('area-feedback');
    const descriptionFeedback = document.getElementById('description-feedback');
    const descriptionCount = document.getElementById('description-count');
    const { countryId, nameCheckUrl } = name.dataset;
    const areaMax = Number(area.dataset.max);
    const descriptionMin = Number(description.dataset.min);
    const descriptionMax = Number(description.dataset.max);
    const originalDescription = description.dataset.original.replace(/\r\n/g, '\n').trim();
    let nameCheckRequest;
    let lastDigits = /^[0-9]*$/.test(area.value) ? area.value : '';

    name.addEventListener('change', async () => {
        nameCheckRequest?.abort();
        name.setCustomValidity('');
        showError(name, nameFeedback, name.value ? '' : 'Выберите страну.');

        if (!name.value) {
            return;
        }

        const request = new AbortController();
        nameCheckRequest = request;
        const url = new URL(nameCheckUrl);
        url.searchParams.set('name', name.value);
        if (countryId) {
            url.searchParams.set('country_id', countryId);
        }

        try {
            const response = await fetch(url, {
                headers: { Accept: 'application/json' },
                signal: request.signal,
                cache: 'no-store',
            });
            if (response.status === 422) {
                const message = (await response.json()).errors?.name?.[0];
                if (message && !request.signal.aborted) {
                    name.setCustomValidity(message);
                    showError(name, nameFeedback, message);
                }
            }
        } catch {
            // Сервер повторит проверку при сохранении.
        }
    });

    function validateArea(showFeedback) {
        let message = '';
        if (!area.value) {
            message = 'Введите площадь.';
        } else if (!/^[0-9]+$/.test(area.value)) {
            message = 'Площадь вводится только цифрами.';
        } else if (Number(area.value) < 1) {
            message = 'Площадь должна быть больше нуля.';
        } else if (Number(area.value) > areaMax) {
            message = 'Площадь должна быть не больше ' + areaMax.toLocaleString('ru-RU') + ' км².';
        }

        area.setCustomValidity(message);
        if (showFeedback) {
            showError(area, areaFeedback, message);
        }
    }

    function validateDescription(showFeedback) {
        const value = description.value.trim();
        const length = Array.from(value).length;
        const unchanged = countryId && value === originalDescription;
        let message = '';
        if (!unchanged && length < descriptionMin) {
            message = 'В описании должно быть не менее ' + descriptionMin.toLocaleString('ru-RU') + ' символов.';
        } else if (!unchanged && length > descriptionMax) {
            message = 'В описании должно быть не более ' + descriptionMax.toLocaleString('ru-RU') + ' символов.';
        }

        description.setCustomValidity(message);
        descriptionCount.textContent = length.toLocaleString('ru-RU') + ' / ' +
            descriptionMax.toLocaleString('ru-RU') + ' символов';
        if (showFeedback) {
            showError(description, descriptionFeedback, message);
        }
    }

    validateArea(false);
    validateDescription(false);
    area.addEventListener('input', () => {
        if (/[^0-9]/.test(area.value)) {
            area.value = lastDigits;
            validateArea(false);
            showError(area, areaFeedback, 'Площадь вводится только цифрами.');
            return;
        }

        lastDigits = area.value;
        validateArea(true);
    });
    description.addEventListener('input', () => validateDescription(true));
    government.addEventListener('change', () => government.classList.toggle('is-invalid', !government.validity.valid));

    form.addEventListener('keydown', event => {
        if (event.key === 'Enter' && !event.isComposing && event.target === area) {
            event.preventDefault();
            description.focus();
        }
    });

    form.addEventListener('submit', event => {
        validateArea(true);
        validateDescription(true);
        if (!form.checkValidity()) {
            event.preventDefault();
            form.querySelector(':invalid')?.focus();
        }
        form.classList.add('was-validated');
    });

    const factRows = document.getElementById('country-fact-rows');
    if (factRows) {
        setupFactRows(factRows);
    }
}

function showError(field, feedback, message) {
    feedback.textContent = message;
    field.classList.toggle('is-invalid', Boolean(message));
}

function setupFactRows(rows) {
    const template = document.getElementById('country-fact-template');
    const add = document.getElementById('add-country-fact');
    let nextIndex = Array.from(rows.children).reduce(
        (next, row) => Math.max(next, Number(row.dataset.factIndex) + 1 || 0), 0
    );

    add.addEventListener('click', () => {
        rows.insertAdjacentHTML('beforeend', template.innerHTML.replaceAll('__INDEX__', nextIndex++));
        rows.lastElementChild.querySelector('input').focus();
    });
    rows.addEventListener('click', event => {
        if (event.target.closest('[data-remove-fact]')) {
            event.target.closest('fieldset').remove();
            add.focus();
        }
    });
}
