BX.ready(function () {
    BX.loadScript(`https://api-maps.yandex.ru/2.1/?apikey=${ymApiKey}&lang=ru_RU`, setContactsMap);
});

function setContactsMap() {
    const container = document.querySelector(".contacts__map");
    if (!container) return;
    const icon = container.dataset.icon;
    ymaps.ready(() => {
        const map = new ymaps.Map(container, {
            center: [0, 0],
            zoom: 0,
            controls: []
        });
        const clusterer = new ymaps.Clusterer({
            clusterize: true,
            clusterIconLayout: "default#pieChart",
        });
        container.querySelectorAll("span[data-coord]").forEach((element, index) => {
            const coordinates = element.dataset.coord.split(",");
            clusterer.add(new ymaps.Placemark(coordinates, {}, {
                    iconImageHref: icon,
                    iconLayout: 'default#image',
                    iconImageSize: [68, 88],
                    iconImageOffset: [-34, -88]
                }
            ));
        });
        map.geoObjects.add(clusterer);
        map.setBounds(clusterer.getBounds());
    });
}