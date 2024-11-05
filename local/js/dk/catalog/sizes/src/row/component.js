import {Counter} from "dk.catalog.product.buttons.counter";
import {template} from "./template";

export const component = {
    name: "Row",
    props: {
        size: {
            type: Object,
            required: true
        },
        productId: {
            type: Number,
            required: true
        },
        containerWidth: {
            type: Number,
            required: true
        },
        lang: {
            type: Object,
            required: true
        },
        showBoxColumn: {
            type: Boolean,
            required: true
        },
        boxHeader: {
            type: String,
            required: true
        }
    },
    data: () => ({
        sum: "",
        valueSum: 0
    }),
    methods: {
        changeSum(data) {
            this.sum = data.sum.format;
            this.valueSum = data.sum.value;
        }
    },
    mounted() {
        this.sum = this.size.cart.sum.format;
        this.valueSum = this.size.cart.sum.value;
    },
    components: {
        Counter
    },
    template: template
};