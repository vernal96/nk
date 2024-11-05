function initNewsSlider() {
    const arrows = document.getElementById("newsSwiperArrows"),
        swiper = document.getElementById("newsSwiper");
    if (swiper) {
        new Swiper("#newsSwiper", {
            allowTouchMove: true,
            slidesPerView: "auto",
            spaceBetween: 12,
            navigation: {
                enabled: true,
                nextEl: arrows.querySelector(".arrow--next"),
                prevEl: arrows.querySelector(".arrow--prev"),
                disabledClass: "is-disabled"
            },
            freeMode: {
                enabled: true,
                sticky: true
            },
            breakpoints: {
                768: {
                    slidesPerView: 3,
                    spaceBetween: 20
                },
                1200: {
                    spaceBetween: 30,
                    slidesPerView: 4
                },
                1720: {
                    spaceBetween: 30,
                    slidesPerView: 4
                }
            }
        });
    }
}