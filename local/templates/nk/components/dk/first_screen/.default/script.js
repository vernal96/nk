BX.ready(function () {
    const slides = document.querySelectorAll(".first-screen__content-item");
    if (!slides) return;
    const mediaSwiper = new Swiper("#firstScreenImages", {
        effect: "fade",
        allowTouchMove: false,
        slidesPerView: 1,
        loop: slides.length > 1,
    });
    const mainSwiper = new Swiper("#firstScreenContent", {
        direction: "vertical",
        allowTouchMove: false,
        pagination: {
            el: "#firstScreenDots",
            type: "bullets",
            bulletActiveClass: "is-active",
            bulletClass: "dot",
            clickable: true,
            verticalClass: "dots-vertical",
            lockClass: "is-disabled"
        },
        slideActiveClass: "is-active",
        controller: {
            by: "container",
            control: [
                mediaSwiper
            ]
        },
        loop: slides.length > 1,
        autoplay: {
            delay: 10000,
            disableOnInteraction: false,
            pauseOnMouseEnter: true
        }
    });
});