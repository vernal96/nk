import {template} from "./template";
import {Sizes} from "dk.catalog.sizes";

export const component = {
    name: "Items",
    data: () => ({
        items: []
    }),
    mounted() {
        BX.ajax.runComponentAction("dk:cart", "getItems", {
            mode: "class"
        }).then(response => {
            this.items = response.data;
        });
    },
    components: {
        Sizes
    },
    template: template
};