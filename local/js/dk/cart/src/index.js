import {Form as CartForm} from "./form/component";
import {component as CartItems} from "./items/component";
import {Methods} from "dk.main.methods";

export const Cart = {
    name: "Cart",
    data: () => ({
        showItems: true,
        lang: {
            title: {
                form: BX.message.CART_TITLE_FORM,
                items: BX.message.CART_TITLE_ITEMS
            },
            currency: BX.message.CURRENCY,
            point: BX.message.POINT
        }
    }),
    computed: {
        showButtonText() {
            return this.showItems ? BX.message.CART_HIDE_ITEMS : BX.message.CART_SHOW_ITEMS;
        }
    },
    methods: {
        toggleShowItems() {
            this.showItems = !this.showItems;
        },
    },
    beforeMounted() {
        BX.ajax.runComponentAction("dk:cart", "getTotal", {
            mode: "class"
        }).then(
            response => {
                this.total = response.data;
            }
        );
    },
    components: {
        CartForm,
        CartItems
    },
    template: `
		<div class="cart__buttons">
			<button 
			class="button button--transparent button--bordered button--long"
			@click="toggleShowItems"
			>{{ showButtonText }}</button>
		</div>
		<div class="cart__wrapper">
			<div class="cart__left" :class="{'cart__left--full': !showItems}">
				<div class="title title--h3 title--min-bottom">{{ lang.title.form }}</div>
				<div class="white-block">
					<CartForm :fullWidth="!showItems"></CartForm>
				</div>
			</div>
			<Transition name="cart-items-slider">
				<div class="cart__right" v-show="showItems">
					<div class="title title--h3 title--min-bottom">{{ lang.title.items }}</div>
					<CartItems></CartItems>
				</div>
			</Transition>
		</div>
	`
};

export function runModalCart() {
    function onSuccess(html) {
        const form = window["formWrapper"];
        if (form) {
            form.classList.add("is-success");
            const formSuccessElement = Methods.createStructure({inner: html}).firstElementChild;
            form.append(formSuccessElement);
        }
    }

    const cart = Methods.createStructure({
        classes: ["modal", "modal-cart"],
        children: [
            {
                classes: ["form"],
                variable: "formWrapper",
                children: [
                    {
                        classes: ["form-content"],
                        children: [
                            {
                                classes: ["title", "title--h3", "title--min-bottom"],
                                content: BX.message.CART_TITLE_FORM
                            },
                            {
                                variable: "form"
                            }
                        ]
                    }
                ]
            }
        ]
    });
    BX.Vue3.createApp(CartForm, {useFile: true, fastpay: true, successFunction: onSuccess}).mount(window["form"]);
    new Fancybox([
        {
            src: cart,
            type: "html"
        }
    ], {
        dragToClose: false,
        Hash: false,
        autoFocus: false
    });
}