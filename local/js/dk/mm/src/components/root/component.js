import {Methods} from "dk.main";
import {template} from "./template";
import {SocNet} from "dk.ui.socnet";

export const component = {
    name: "MM",
    data: () => ({
        socnet: {},
        children: [],
        phone: {},
        isActive: false,
        catalogInit: false,
        loadingCatalog: false,
        login: "",
        lang: {
            title: BX.message.NK_MM_TITLE,
            fastpay: BX.message.NK_MM_FASTPAY_BUTTON,
            recall: BX.message.NK_MM_RECALL_BUTTON
        },
        chain: []
    }),
    computed: {
        phoneLink() {
            return `tel:${this.phone.link}`
        },
        activeItem() {
            let item = this;
            if (!this.chain.length) item = null;
            this.chain.forEach(index => {
                item = item.children[index];
            });
            return item;
        },
        items() {
            return this.chain.length ? this.activeItem.children : this.children;
        },
        mmTitle() {
            if (this.activeItem) {
                return this.activeItem.title;
            } else {
                return this.lang.title;
            }
        }
    },
    watch: {
        isActive(value) {
            if (value) {
                Methods.toggleBodyOverflow();
            } else Methods.toggleBodyOverflow(false);
        }
    },
    methods: {
        initMMTriggers() {
            Methods.on("click", ".mmo", () => {
                this.isActive = !this.isActive;
            });
        },
        initMenuId(menu) {
            return menu.map(item => {
                item.id = Math.random();
                item.children = this.initMenuId(item.children);
                return item;
            });
        },
        changeActiveItem(index) {
            this.chain.push(index);
        },
        changeActiveItemCatalog(index) {
            if (!this.catalogInit) {
                this.loadingCatalog = true;
                BX.ajax.runComponentAction("dk:mm", "getTree", {
                    mode: "class"
                }).then(
                    response => {
                        this.children.find(item => item.isCatalog).children = response.data;
                        this.loadingCatalog = false;
                        this.catalogInit = true;
                        this.changeActiveItem(index);
                    },
                );
            } else {
                this.changeActiveItem(index);
            }
        },
        back() {
            this.chain.pop();
        }
    },
    mounted() {
        BX.ajax.runComponentAction("dk:mm", "getMM", {
            mode: "class"
        }).then(
            response => {
                for (let key in response.data) {
                    this[key] = response.data[key];
                }
                this.children = this.initMenuId(this.children);
            }
        );
        this.initMMTriggers();
    },
    components: {
        SocNet
    },
    template: template
}