import {template} from "../templates/catalog_category";

export const component = {
    name: "headerCatalogCategory",
    props: {
        category: {
            type: Object,
            required: true
        },
        activeCategory: {
            type: Object,
            required: true
        }
    },
    data: () => ({}),
    computed: {
        isActive() {
            return this.category === this.activeCategory;
        },
        isNew() {
            return this.category.tags.new;
        },
        isTagged() {
            return this.isNew;
        },
        isEmpty() {
            return this.category.children.length === 0;
        }
    },
    emits: ["onSelect"],
    methods: {
        select(event) {
            if (event.target.tagName !== "A") {
                this.$emit("onSelect", this.category.id);
            }
        }
    },
    template: template
};