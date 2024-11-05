import {template} from "../templates/catalog_subcategory";

export const component = {
    name: "headerCatalogSubcategory",
    props: {
        category: {
            type: Object,
            required: true
        },
        useLimit: {
            type: Boolean,
            required: true
        }
    },
    computed: {
        showCategory() {
            return this.category.showWithLimit || !this.useLimit;
        },
        showSubcategories() {
            return this.category.children.length > 0;
        }
    },
    template: template
};