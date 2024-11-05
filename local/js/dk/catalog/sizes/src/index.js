import {template} from "./template";
import {component as Table} from "./table/component";
import "./style.css";

export const Sizes = {
    name: "Sizes",
    props: {
        productId: {
            type: Number,
            required: false
        },
        full: {
            type: Boolean,
            required: false,
            default: false
        },
        postpone: {
            type: Boolean,
            required: false,
            default: false
        },
        sizeIdList: {
            type: Array,
            required: false,
            default: []
        },
        toggleShow: {
            type: Boolean,
            required: false,
            default: false
        }
    },
    data: () => ({
        sizes: [],
        data: null,
        show: true
    }),
    computed: {
        showPlaceholder() {
            return !this.sizes.length;
        },
        showButton() {
            return this.show ? BX.message.SIZES_HIDE : BX.message.SIZES_SHOW;
        }
    },
    emits: [
        "onInitialized"
    ],
    methods: {
        init() {
            BX.ajax.runComponentAction("dk:catalog", "getPrices", {
                mode: "class",
                data: {
                    id: this.productId,
                    full: this.full,
                    sizes: this.sizeIdList
                }
            }).then(
                response => {
                    this.sizes = response.data.sizes;
                    this.data = response.data.data;
                    this.$emit("onInitialized");
                }
            );
        },
        showEnter(element) {
            const contentHeight = element.clientHeight;
            element.style.height = 0;
            setTimeout(() => element.style.height = contentHeight + "px", 0);
        },
        showAfterEnter(element) {
            element.removeAttribute("style");
        },
        hideBeforeLeave(element) {
            element.style.height = element.clientHeight + "px";
        },
        hideLeave(element) {
            element.style.height = 0;
        }
    },
    beforeMount() {
        if (this.toggleShow) this.show = false;
    },
    mounted() {

        if (!this.postpone) {
            this.init();
        }
    },
    components: {
        Table
    },
    template: template
};