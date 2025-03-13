import {template} from "./template";
import {Authorize} from "../authorize/component";
export const component = {
    name: "login",
    data: () => ({
        swiper: null,
        tabs: [
            {
                id: "auth",
                title: BX.message.LOGIN_AUTH,
            },
            {
                id: "reg",
                title: BX.message.LOGIN_REG
            }
        ],
        lang: {
            title: BX.message.LOGIN_TITLE
        },
        activeTab: "auth"
    }),
    components: {
        Authorize,
        // Registration
    },
    methods: {
        toggleSlide(slideIndex, tabId) {
            if (!this.swiper) return;
            this.swiper.slideTo(slideIndex);
            this.activeTab = tabId;
        },
        updateAutoHeight() {
            setTimeout(() => this.swiper.updateAutoHeight(100), 0);
        }
    },
    mounted() {
        this.swiper = new Swiper(this.$refs.swiper, {
            autoHeight: true,
            slidesPerView: 1,
            allowTouchMove: false,
            effect: 'fade',
            speed: 300,
            fadeEffect: {
                crossFade: true
            },
        });
    },
    template: template
};