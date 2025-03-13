import {template} from "../templates/root";
import {component as Placeholder} from "./placeholder";
import {component as CatalogCategory} from "./catalog_category";
import {component as CategoryColumn} from "./catalog_column";

export const component = {
    name: "headerCatalogRoot",
    data: () => ({
        preload: true,
        tree: [],
        activeCategory: 0,
        limit: 5,
        tmpLimit: 0,
        tmpShowMore: false
    }),
    methods: {
        changeActiveCategory(categoryId) {
            this.activeCategory = this.tree.find(section => section.id === categoryId);
        },
        prepareLimit(category, start = true) {
            if (start) {
                this.tmpLimit = 0;
                this.tmpShowMore = false;
            }
            if (this.tmpShowMore) return category;
            category.showWithLimit = this.tmpLimit <= this.limit;
            this.tmpLimit++;
            category.children.map(subcategory => this.prepareLimit(subcategory, false));
            if (this.limit <= this.tmpLimit && start) {
                this.tmpShowMore = true;
                category.showMore = true;
            }
            return category;
        }
    },
    mounted() {
        BX.ajax.runComponentAction("dk:catalog", "getTree", {
            mode: "class",
            data: {
                header: true
            }
        }).then(
            response => {
                this.tree = response.data.map(category => {
                    category.children.map(subcategory => this.prepareLimit(subcategory));
                    return category;
                });
                this.activeCategory = this.tree[0];
                this.preload = false;
            }
        );
    },
    components: {
        Placeholder,
        CatalogCategory,
        CategoryColumn
    },
    template: template
};