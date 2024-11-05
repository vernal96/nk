import {template} from "../templates/catalog_column";
import {component as SubCategory} from "./catalog_subcategory";
import {Loc} from 'main.core';

export const component = {
    name: "headerCatalogColumn",
    props: {
        category: {
            type: Object,
            required: true
        }
    },
    data: () => ({
        useLimit: true,
        lang: {
            more: Loc.getMessage("DK_CATALOG_HEADER_MORE")
        }
    }),
    methods: {
        showAll() {
            this.useLimit = false;
        }
    },
    components: {
        SubCategory
    },
    template: template
};