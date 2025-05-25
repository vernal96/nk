import {template} from "./template";
import {component as Row} from "../row/component";

export const component = {
    name: "Table",
    props: {
        sizes: {
            type: Array,
            required: true
        },
        productId: {
            type: Number,
            required: true
        },
        showPlaceholder: {
            type: Boolean,
            required: true
        }
    },
    data: () => ({
        containerWidth: 0,
        lang: {
            sizes: BX.message.SIZES_HEADER_TITLE,
            boxMobile: BX.message.SIZES_HEADER_BOX_MOBILE,
            price: BX.message.SIZES_HEADER_PRICE,
            priceMobile: BX.message.SIZES_HEADER_PRICE_MOBILE,
            count: BX.message.SIZES_HEADER_COUNT,
            countMobile: BX.message.SIZES_HEADER_COUNT_MOBILE,
            sum: BX.message.SIZES_HEADER_SUM,
            currency: BX.message.CURRENCY,
            toCart: BX.message.SIZES_TO_CART
        }
    }),
    computed: {
        sizesHeader() {
            return this.lang.sizes;
        },
        priceHeader() {
            return this.containerWidth > 580 ? this.lang.price : this.lang.priceMobile;
        },
        countHeader() {
            return this.containerWidth > 580 ? this.lang.count : this.lang.countMobile;
        },
        sumHeader() {
            return this.lang.sum;
        },
        miniToCart() {
            return this.containerWidth < 560;
        }
    },
    methods: {
        initContainerWidthListener() {
            if (this.$refs.table) {
                this.containerWidth = this.$refs.table.clientWidth;
            }
        }
    },
    mounted() {
        window.addEventListener("resize", () => {
            this.initContainerWidthListener();
        });
        this.initContainerWidthListener();
    },
    components: {
        Row
    },
    template: template
};