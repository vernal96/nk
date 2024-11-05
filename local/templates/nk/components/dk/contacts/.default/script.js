BX.ready(function () {
    BX.loadScript(`https://api-maps.yandex.ru/2.1/?apikey=${ymApiKey}&lang=ru_RU`, setContactsMap);
});

function setContactsMap() {
    ymaps.ready(() => {
        document.querySelectorAll(".contact-item__map").forEach((element, index) => {
            const coordinates = element.dataset.coord.split(",");
            const map = new ymaps.Map(element, {
                center: coordinates,
                zoom: 12,
                controls: []
            });
            map.geoObjects.add(new ymaps.Placemark(coordinates, {}, {
                iconImageHref: element.dataset.icon,
                iconLayout: 'default#image',
                iconImageSize: [68, 88],
                iconImageOffset: [-34, -88]
            }));
        });
    });
}