import {template} from "./template";
import {SizesButton} from "dk.catalog.product.buttons.sizes";
import {Counter} from "dk.catalog.product.buttons.counter";

export const Product = {
    name: "Product",
    props: {
        data: {
            type: Object,
            required: true
        }
    },
    data: () => ({
        lang: {
            from: BX.message.FROM,
            currency: BX.message.CURRENCY
        }
    }),
    components: {
        SizesButton, Counter
    },
    template: template
};