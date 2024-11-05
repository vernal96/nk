import {template} from "./template";

export const component = {
    name: "Category",
    props: {
        category: {
            type: Object,
            required: true
        },
        activeCategories: {
            type: Array,
            required: true
        },
        isSubcategory: {
            type: Boolean,
            required: false,
            default: false
        },
        showChildrenCategories: {
            type: Array,
            required: true
        },
        start: {
            type: Boolean,
            required: true
        },
        loading: {
            type: Boolean,
            required: true
        },
        loadingCategory: {
            type: Number,
            required: false
        }
    },
    data() {
        return {
            image: {
                src: false,
                elementClass: true
            }
        }
    },
    computed: {
        isNew() {
            return this.category.tags.new
        },
        isTagged() {
            return this.isNew;
        },
        hasChildren() {
            return this.category.children.length > 0
        },
        isActive() {
            return this.activeCategories.includes(this.category.id);
        },
        showChildren() {
            return this.showChildrenCategories.includes(this.category.id);
        },
        isLoading() {
            return this.category.id === this.loadingCategory;
        },
        showArrow() {
            return !this.isLoading && this.hasChildren;
        }
    },
    emits: ["onChangeActive"],
    methods: {
        changeActive(category, event) {
            if (event) {
                if (event.target.tagName === 'A') return;
            }
            if (this.loading) return;
            this.$emit("onChangeActive", category);
        },
        showEnter(element) {
            const childrenListHeight = element.clientHeight;
            element.style.height = 0;
            setTimeout(() => element.style.height = childrenListHeight + "px", 0);
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
    mounted() {
        let pictureSrc;
        if (this.isSubcategory) {
            pictureSrc = this.category.images.picture ? this.category.images.picture.src : false;
        } else {
            pictureSrc = this.category.icon;
        }
        this.image = {
            src: pictureSrc,
            elementClass: `catalog-category__${this.isSubcategory ? "picture" : "image"}`
        };
    },
    template: template
};