import {template} from "./template";
import {component as Category} from "../category/component";

export const component = {
    name: "Categories",
    props: {
        categories: {
            type: Array,
            required: true
        },
        activeCategories: {
            type: Array,
            required: true
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
        },
    },
    data: () => ({}),
    emits: ["onChangeActive"],
    methods: {
        changeActive(category) {
            this.$emit("onChangeActive", category);
        }
    },
    components: {
        Category
    },
    template: template
};