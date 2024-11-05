import {Methods} from "dk.main.methods";
import "./style.css";

export const Selector = {
    name: "Selector",
    props: {
        options: {
            type: Array,
            required: true
        },
        placeholder: {
            type: String,
            required: true
        },
        name: {
            type: String,
            required: false,
            default: ""
        },
        mainClass: {
            type: String,
            required: false,
            default: ""
        },
        value: {
            required: false,
            default: null
        },
        title: {
            type: String,
            required: true,
        },
        isError: {
            type: Boolean,
            required: false,
            default: false
        }
    },
    data: () => ({
        selected: {
            value: null
        },
        listOpened: false,
        isMobile: false
    }),
    computed: {
        label() {
            return this.selected.value ? this.selected.title : this.placeholder;
        },
        notSelected() {
            return this.selected.value === null;
        }
    },
    emits: ["onChange"],
    watch: {
        selected(option) {
            this.$emit("onChange", option.value);
        }
    },
    methods: {
        check(option) {
            this.selected = option;
            this.listOpened = false;
        },
        openList() {
            const reverseClass = "is-reverse",
                list = this.$refs.list,
                parent = this.$refs.root;
            if (window.innerHeight - list.getBoundingClientRect().top - parent.offsetHeight < list.offsetHeight) {
                parent.classList.add(reverseClass);
            } else parent.classList.remove(reverseClass);
            this.listOpened = !this.listOpened;
        }
    },
    beforeMount() {
        if (this.value) {
            const option = this.options.find(option => option.value === this.value);
            if (option) {
                this.selected = option;
            }
        }
    },
    mounted() {
        Methods.outsideClick(this.$refs.root, () => {
            this.listOpened = false;
        });
        this.isMobile = Methods.isMobile();
    },
    template: `
		<div class="selector" :class="[mainClass, {'is-active': listOpened, 'selector_mobile': isMobile}]" ref="root">
			<select :name="name" v-model="selected">
				<option 
				v-for="option in options"
				:value="option"
				>{{ option.title }}</option>
			</select>
			<div class="selector__under-title" :class="{'is-active': selected.value}">{{ title }}</div>
			<div class="selector__label" :class="{'is-error': isError}" @click="openList">
				<div class="selector__title" :class="{'is-disabled': notSelected}">{{ label }}</div>
				<div class="selector__btn"></div>
			</div>
			<div class="selector__list" ref="list">
				<div 
				v-for="option in options"
				class="selector__option"
				:class="{'is-selected': option == selected}"
				:key="option.value"
				@click="check(option)"
				>{{ option.title }}</div>
			</div>
		</div>
	`
};