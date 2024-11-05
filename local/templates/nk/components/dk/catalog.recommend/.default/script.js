function initCatalogRecommended() {
    const swiper = document.getElementById("recommendSwiper");
    if (!swiper) return;
    let element = document.querySelector(".container");
    let paddingValue = +window.getComputedStyle(element, null).getPropertyValue("padding-left").replace("px", "");
    new Swiper("#recommendSwiper", {
        slidesPerView: "auto",
        spaceBetween: 12,
        slidesOffsetBefore: paddingValue,
        slidesOffsetAfter: paddingValue,
        allowTouchMove: true,
        navigation: {
            enabled: true,
            nextEl: document.getElementById("recommendArrowNext"),
            prevEl: document.getElementById("recommendArrowPrev"),
            disabledClass: "is-disabled"
        },
        freeMode: {
            enabled: true,
            sticky: true
        },
        breakpoints: {
            550: {
                slidesPerView: 3,
                slidesOffsetBefore: 0,
                slidesOffsetAfter: 0
            },
            781: {
                slidesPerView: 2,
                slidesOffsetBefore: 0,
                slidesOffsetAfter: 0
            },
            990: {
                slidesPerView: 3,
                spaceBetween: 20,
                slidesOffsetBefore: 0,
                slidesOffsetAfter: 0
            },
            1400: {
                spaceBetween: 30,
                slidesPerView: 4,
                slidesOffsetBefore: 0,
                slidesOffsetAfter: 0
            },
            1720: {
                slidesPerView: 4,
                slidesOffsetBefore: 0,
                slidesOffsetAfter: 0
            }
        }
    });
}