import {Sizes} from "dk.catalog.sizes";

export const SizesButton = {
    name: "SizesButton",
    props: {
        productId: {
            type: Number,
            required: true
        }
    },
    data: () => ({
        lang: {
            title: BX.message.SHOW_SIZES
        },
        loading: false,
        init: false
    }),
    methods: {
        openSizeTable() {
            if (!this.init) {
                this.loading = true;
                this.$refs.sizeTable.init();
            } else {
                this.openModal();
            }
        },
        initialize() {
            this.loading = false;
            this.init = true;
            this.openModal();
        },
        openModal() {
            new Fancybox([
                {
                    src: this.$refs.modal,
                    type: "html"
                }
            ], {
                dragToClose: false,
                Hash: false,
                autoFocus: false
            });
            window.dispatchEvent(new Event("resize"));
        }
    },
    components: {
        Sizes
    },
    template: `
		<div 
		class="button button--center button--transparent button--bordered button--100 product__button"
		:class="{'is-loading': loading}"
		@click="openSizeTable"
		>
		<span class="loader loader--gray" v-if="loading"></span>
        {{ lang.title }}
        </div>
        <div class="modal modal-sizes" ref="modal">
            <Sizes 
            :productId="productId"
            :postpone="true"
            :full="true"
            ref="sizeTable"
            @onInitialized="initialize"
            ></Sizes>
        </div>
	`
};