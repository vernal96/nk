import {template} from "./template";

export const component = {
    name: "MobileCategories",
    props: {
        category: {
            type: Object,
            required: true
        },
        opened: {
            type: Boolean,
            required: true
        }
    },
    computed: {
        isRoot() {
            return !this.category.parent;
        },
        image() {
            return this.isRoot ? this.category.icon : (this.category.images.picture ? this.category.images.picture.src : false);
        },
        isNew() {
            if (this.category.tags) {
                return this.category.tags.new;
            }
            return false;
        },
        isTagged() {
            return this.isNew;
        }
    },
    emits: ["onMobileCatalogOpen"],
    methods: {
        showTree(event) {
            if (event.target.tagName.toLowerCase() === 'a') return;
            this.$emit("onMobileCatalogOpen")
        }
    },
    template: template
};