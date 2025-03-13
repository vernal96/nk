import {getComponent} from "../index";
import {template} from "./template";

export const component = {
    name: "Back",
    title: {
        type: String,
        required: true
    },
    form: {
        type: String,
        required: true
    },
    params: {
        type: Object,
        required: false
    },
    parentLoading: {
        type: Boolean,
        required: true
    },
    data: () => ({
        loading: false
    }),
    emits: ["onLoadingAfter", "onLoadingBefore"],
    methods: {
        go() {
            getComponent(this, params);
        }
    },
    template: template
};