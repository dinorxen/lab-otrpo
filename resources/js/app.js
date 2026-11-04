import './bootstrap';
import { Modal, Popover, Toast } from 'bootstrap';
import '@fortawesome/fontawesome-free/js/all.js';
import $ from 'jquery';

window.$ = window.jQuery = $;

const hints = {
    france: `
        <button
            type="button"
            class="btn btn-link p-0 align-baseline"
            data-bs-trigger="focus"
            data-bs-toggle="popover"
            data-bs-title="Лувр"
            data-bs-content="Художественный музей в Париже, расположенный в бывшем королевском дворце."
        >Лувр</button>
    `,

    austria: `
        <button
            type="button"
            class="btn btn-link p-0 align-baseline"
            data-bs-trigger="focus"
            data-bs-toggle="popover"
            data-bs-title="Шёнбрунн"
            data-bs-content="Дворец в Вене, служивший летней резиденцией правителей династии Габсбургов."
        >Шёнбрунн</button>
    `,

    netherlands: `
        <button
            type="button"
            class="btn btn-link p-0 align-baseline"
            data-bs-toggle="popover"
            data-bs-trigger="focus"
            data-bs-title="Рейксмюсеум"
            data-bs-content="Музей в Амстердаме с коллекциями искусства и предметов истории Нидерландов."
        >Рейксмюсеум</button>
    `,

    japan: `
        <button
            type="button"
            class="btn btn-link p-0 align-baseline"
            data-bs-toggle="popover"
            data-bs-trigger="focus"
            data-bs-title="Синкансэн"
            data-bs-content="Система высокоскоростных железных дорог Японии."
        >Синкансэн</button>
    `,

    italy: `
        <button
            type="button"
            class="btn btn-link p-0 align-baseline"
            data-bs-toggle="popover"
            data-bs-trigger="focus"
            data-bs-title="Ватикан"
            data-bs-content="Независимое государство внутри Рима и резиденция Папы Римского."
        >Ватикан</button>
    `
};

function createCountryArticle(hint) {
    return `
        <section class="mb-4">
            <img class="" src="https://thumb.wikimedia.org/wikipedia/commons/thumb/4/4b/La_Tour_Eiffel_vue_de_la_Tour_Saint-Jacques%2C_Paris_ao%C3%BBt_2014_%282%29.jpg/1920px-La_Tour_Eiffel_vue_de_la_Tour_Saint-Jacques%2C_Paris_ao%C3%BBt_2014_%282%29.jpg?utm_source=ru.wikipedia.org&utm_campaign=imageinfo&utm_content=thumbnail     ">
            <h3 class="h5">Общие сведения</h3>

            <ul class="list-group list-group-flush">
                <li class="list-group-item">
                    <strong>Столица:</strong> заполнить
                </li>
                <li class="list-group-item">
                    <strong>Язык:</strong> заполнить
                </li>
                <li class="list-group-item">
                    <strong>Валюта:</strong> заполнить
                </li>
                <li class="list-group-item">
                    <strong>Форма правления:</strong> заполнить
                </li>
            </ul>
        </section>

        <section class="mb-4">
            <h3 class="h5">География</h3>
            <p>Здесь опиши расположение, климат и природу страны.</p>
        </section>

        <section class="mb-4">
            <h3 class="h5">История и культура</h3>
            <p>Здесь расскажи об истории, традициях и культуре.</p>
        </section>

        <section class="mb-4">
            <h3 class="h5">Достопримечательности</h3>
            <p>
                Здесь добавь описание достопримечательностей.
                Название с подсказкой: ${hint}.
            </p>
        </section>

        <section>
            <h3 class="h5">Интересные факты</h3>
            <ul>
                <li>Первый факт.</li>
                <li>Второй факт.</li>
                <li>Третий факт.</li>
            </ul>
        </section>
    `;
}

const countries = [
    {
        title: 'Франция',
        image: 'https://thumb.wikimedia.org/wikipedia/commons/thumb/4/4b/La_Tour_Eiffel_vue_de_la_Tour_Saint-Jacques%2C_Paris_ao%C3%BBt_2014_%282%29.jpg/1920px-La_Tour_Eiffel_vue_de_la_Tour_Saint-Jacques%2C_Paris_ao%C3%BBt_2014_%282%29.jpg',
        text: `
            <section class="mb-4">
                <h3 class="h5">Общие сведения</h3>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Столица:</strong> Париж</li>
                    <li class="list-group-item"><strong>Язык:</strong> французский</li>
                    <li class="list-group-item"><strong>Валюта:</strong> евро</li>
                    <li class="list-group-item"><strong>Форма правления:</strong> полупрезидентская республика</li>
                </ul>
            </section>

            <section class="mb-4">
                <h3 class="h5">География</h3>
                <p>
                    Основная европейская территория Франции находится в Западной
                    Европе. Страна имеет выход к Атлантическому океану и
                    Средиземному морю. На юго-востоке расположены Альпы,
                    а вдоль границы с Испанией — Пиренеи.
                    Природа страны включает равнины, горные районы,
                    виноградники и морское побережье.
                </p>
            </section>

            <section class="mb-4">
                <h3 class="h5">История и культура</h3>
                <p>
                    Франция сыграла значительную роль в развитии европейской
                    литературы, живописи и философии. Французская революция
                    1789 года оказала влияние на политическую историю Европы.
                    Сегодня страна известна музеями, архитектурой,
                    кинематографом, модой и гастрономическими традициями.
                </p>
            </section>

            <section class="mb-4">
                <h3 class="h5">Достопримечательности</h3>
                <p>
                    В Париже находятся Эйфелева башня, Триумфальная арка
                    и ${hints.france}. Недалеко от столицы расположен
                    Версальский дворец с садами. Среди других известных
                    мест — замки долины Луары и остров Мон-Сен-Мишель.
                </p>
            </section>

            <section>
                <h3 class="h5">Интересные факты</h3>
                <ul class="mb-0">
                    <li>Эйфелеву башню построили к Всемирной выставке 1889 года.</li>
                    <li>В Лувре хранится «Мона Лиза» Леонардо да Винчи.</li>
                    <li>Франции принадлежат территории за пределами Европы.</li>
                </ul>
            </section>
        `
    },
    {
        title: 'Австрия',
        image: 'https://commons.wikimedia.org/wiki/Special:FilePath/20110717_Vienna_2391.jpg',
        text: `
            <section class="mb-4">
                <h3 class="h5">Общие сведения</h3>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Столица:</strong> Вена</li>
                    <li class="list-group-item"><strong>Язык:</strong> немецкий</li>
                    <li class="list-group-item"><strong>Валюта:</strong> евро</li>
                    <li class="list-group-item"><strong>Форма правления:</strong> федеративная парламентская республика</li>
                </ul>
            </section>

            <section class="mb-4">
                <h3 class="h5">География</h3>
                <p>
                    Австрия находится в Центральной Европе и не имеет выхода
                    к морю. Значительную часть территории занимают Альпы.
                    Через страну протекает Дунай, на котором расположена Вена.
                    Горные районы известны озёрами, лесами и горнолыжными
                    курортами.
                </p>
            </section>

            <section class="mb-4">
                <h3 class="h5">История и культура</h3>
                <p>
                    История Австрии тесно связана с династией Габсбургов
                    и Австро-Венгерской монархией. Вена стала одним из
                    важнейших центров европейской классической музыки.
                    Здесь работали Моцарт, Бетховен и представители
                    музыкальной династии Штраусов.
                </p>
            </section>

            <section class="mb-4">
                <h3 class="h5">Достопримечательности</h3>
                <p>
                    В Вене можно увидеть дворец ${hints.austria},
                    Хофбург и собор Святого Стефана. Зальцбург известен
                    историческим центром и связью с жизнью Моцарта.
                    Среди природных достопримечательностей выделяются
                    альпийские озёра и горные долины.
                </p>
            </section>

            <section>
                <h3 class="h5">Интересные факты</h3>
                <ul class="mb-0">
                    <li>Моцарт родился в Зальцбурге.</li>
                    <li>Шёнбрунн служил летней резиденцией Габсбургов.</li>
                    <li>Венская кофейная культура известна своими традициями.</li>
                </ul>
            </section>
        `
    },
    {
        title: 'Нидерланды',
        image: 'https://thumb.wikimedia.org/wikipedia/commons/thumb/b/be/KeizersgrachtReguliersgrachtAmsterdam.jpg/1920px-KeizersgrachtReguliersgrachtAmsterdam.jpg',
        text: `
            <section class="mb-4">
                <h3 class="h5">Общие сведения</h3>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Столица:</strong> Амстердам</li>
                    <li class="list-group-item"><strong>Язык:</strong> нидерландский</li>
                    <li class="list-group-item"><strong>Валюта:</strong> евро</li>
                    <li class="list-group-item"><strong>Форма правления:</strong> конституционная монархия</li>
                </ul>
            </section>

            <section class="mb-4">
                <h3 class="h5">География</h3>
                <p>
                    Европейская часть Нидерландов расположена на северо-западе
                    Европы у Северного моря. Для страны характерен равнинный
                    рельеф, а часть территории находится ниже уровня моря.
                    Дамбы, шлюзы и насосные станции помогают защищать
                    населённые пункты и сельскохозяйственные земли от воды.
                </p>
            </section>

            <section class="mb-4">
                <h3 class="h5">История и культура</h3>
                <p>
                    В XVII веке Нидерланды стали важным центром морской
                    торговли, науки и искусства. С этим периодом связаны
                    произведения Рембрандта и Вермеера.
                    Современные города известны велосипедной инфраструктурой,
                    каналами и исторической застройкой.
                </p>
            </section>

            <section class="mb-4">
                <h3 class="h5">Достопримечательности</h3>
                <p>
                    В Амстердаме находятся ${hints.netherlands},
                    Музей Ван Гога и Дом Анны Франк.
                    Каналы исторического центра образуют узнаваемый облик
                    города. За его пределами можно увидеть ветряные
                    мельницы Киндердейка и весенние цветочные сады Кёкенхоф.
                </p>
            </section>

            <section>
                <h3 class="h5">Интересные факты</h3>
                <ul class="mb-0">
                    <li>Столица — Амстердам, а правительство располагается в Гааге.</li>
                    <li>Осушенные и защищённые дамбами земли называют польдерами.</li>
                    <li>Голландия — название части Нидерландов, а не всей страны.</li>
                </ul>
            </section>
        `
    },
    {
        title: 'Япония',
        image: 'https://thumb.wikimedia.org/wikipedia/commons/thumb/c/ca/Tokyo_Tower_at_night_8.JPG/1920px-Tokyo_Tower_at_night_8.JPG',
        text: `
            <section class="mb-4">
                <h3 class="h5">Общие сведения</h3>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Столица:</strong> Токио</li>
                    <li class="list-group-item"><strong>Основной язык:</strong> японский</li>
                    <li class="list-group-item"><strong>Валюта:</strong> японская иена</li>
                    <li class="list-group-item"><strong>Форма правления:</strong> конституционная монархия с парламентской системой</li>
                </ul>
            </section>

            <section class="mb-4">
                <h3 class="h5">География</h3>
                <p>
                    Япония — островное государство в Восточной Азии.
                    Четыре крупнейших острова — Хонсю, Хоккайдо, Кюсю
                    и Сикоку. Значительная часть территории гористая.
                    Страна расположена в сейсмически активном районе,
                    поэтому здесь бывают землетрясения и извержения вулканов.
                </p>
            </section>

            <section class="mb-4">
                <h3 class="h5">История и культура</h3>
                <p>
                    В японской культуре сочетаются традиционные искусства
                    и современные технологии. Чайная церемония, каллиграфия,
                    театр и садовое искусство соседствуют с аниме,
                    видеоиграми и современной архитектурой.
                    Высокоскоростные поезда ${hints.japan}
                    связывают многие крупные города страны.
                </p>
            </section>

            <section class="mb-4">
                <h3 class="h5">Достопримечательности</h3>
                <p>
                    В Токио можно увидеть Токийскую башню и храм Сэнсодзи.
                    Киото известен историческими храмами и садами.
                    Одним из символов Японии считается гора Фудзи.
                    Хиросима известна Мемориальным парком мира.
                </p>
            </section>

            <section>
                <h3 class="h5">Интересные факты</h3>
                <ul class="mb-0">
                    <li>Высота Фудзи составляет 3776 метров.</li>
                    <li>Первая линия Синкансэн открылась в 1964 году.</li>
                    <li>Любование цветущей сакурой называют ханами.</li>
                </ul>
            </section>
        `
    },
    {
        title: 'Италия',
        image: 'https://thumb.wikimedia.org/wikipedia/commons/thumb/c/c7/Rom_%28IT%29%2C_Kolosseum_--_2024_--_0610.jpg/1920px-Rom_%28IT%29%2C_Kolosseum_--_2024_--_0610.jpg',
        text: `
            <section class="mb-4">
                <h3 class="h5">Общие сведения</h3>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><strong>Столица:</strong> Рим</li>
                    <li class="list-group-item"><strong>Язык:</strong> итальянский</li>
                    <li class="list-group-item"><strong>Валюта:</strong> евро</li>
                    <li class="list-group-item"><strong>Форма правления:</strong> парламентская республика</li>
                </ul>
            </section>

            <section class="mb-4">
                <h3 class="h5">География</h3>
                <p>
                    Италия расположена в Южной Европе. Основная часть
                    территории занимает Апеннинский полуостров.
                    На севере находятся Альпы, а вдоль полуострова
                    протянулись Апеннины. Крупнейшие острова страны —
                    Сицилия и Сардиния. Побережье омывается морями
                    Средиземноморского бассейна.
                </p>
            </section>

            <section class="mb-4">
                <h3 class="h5">История и культура</h3>
                <p>
                    На территории Италии находился центр Древнего Рима.
                    Позднее итальянские города стали важнейшими центрами
                    эпохи Возрождения. С Италией связаны имена Леонардо
                    да Винчи, Микеланджело и Рафаэля.
                    Страна известна архитектурой, оперой, модой
                    и разнообразными кулинарными традициями.
                </p>
            </section>

            <section class="mb-4">
                <h3 class="h5">Достопримечательности</h3>
                <p>
                    В Риме находятся Колизей, Пантеон и Римский форум.
                    В пределах города расположен ${hints.italy}.
                    Флоренция известна собором Санта-Мария-дель-Фьоре
                    и галереей Уффици, а Венеция — каналами
                    и площадью Сан-Марко.
                </p>
            </section>

            <section>
                <h3 class="h5">Интересные факты</h3>
                <ul class="mb-0">
                    <li>Ватикан и Сан-Марино — независимые государства, окружённые Италией.</li>
                    <li>На Сицилии расположен действующий вулкан Этна.</li>
                    <li>Современное объединённое Итальянское королевство провозгласили в 1861 году.</li>
                </ul>
            </section>
        `
    }
];

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
const modalImage = document.getElementById('countryModalImage');    
const buttons = document.querySelectorAll('.details-button');

let popovers = [];
let currentIndex = 0;

for (let i = 0; i < buttons.length; i++) {
    buttons[i].onclick = function () {
        currentIndex = i;

        for (const popover of popovers) {
            popover.dispose();
        }

        popovers = [];

        title.textContent = countries[i].title;
        text.innerHTML = countries[i].text;
        modalImage.src = countries[i].image;
        modalImage.alt = `Достопримечательности: ${countries[i].title}`;
        const hints = text.querySelectorAll('[data-bs-toggle="popover"]');

        for (const hint of hints) {
            const popover = new Popover(hint, {
                container: modalElement
            });

            popovers.push(popover);
        }

        modal.show();
    };
}

modalElement.addEventListener('hide.bs.modal', function () {
    for (const popover of popovers) {
        popover.hide();
    }
});

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

    if (nextIndex >= countries.length) {
        nextIndex = 0;
    }

    if (nextIndex < 0) {
        nextIndex = countries.length - 1;
    }

    buttons[nextIndex].click();
});