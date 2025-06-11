import "./style.css";
import {Methods} from 'dk.main.methods';

export const Counter = {
    name: "Counter",
    props: {
        sizeId: {
            type: Number,
            required: true
        },
        productId: {
            type: Number,
            required: true
        },
        cartCount: {
            type: Number,
            required: true
        },
        min: {
            type: Boolean,
            required: false,
            default: false
        },
        startButton: {
            type: Boolean,
            required: false,
            default: true
        }
    },
    data: () => ({
        lang: {
            toCart: BX.message.TO_CART
        },
        count: "",
        loading: false,
        waitTime: 200,
        waiter: null,
        requestToken: 0
    }),
    methods: {
        add() {
            this.count = +this.count + 1;
            this.runAction();
        },
        remove() {
            if (+this.count) {
                this.count = +this.count - 1;
            }
            if (!this.count) this.count = "";
            this.runAction();
        },
        set() {
            if (this.count === "0" || this.count === 0) this.count = "";
            this.runAction();
        },
        runAction() {
            if (this.waiter) clearTimeout(this.waiter);
            this.waiter = setTimeout(() => {
                this.loading = true;
                this.requestToken++;
                const currentToken = this.requestToken;
                BX.ajax.runComponentAction("dk:catalog", "cartUpdate", {
                    mode: "class",
                    data: {
                        productId: this.productId,
                        id: this.sizeId,
                        count: +this.count
                    }
                }).then(
                    response => {
                        if (currentToken !== this.requestToken) return;
                        const responseCount = response.data.count.value ? response.data.count.value : "";
                        this.loading = false;
                        this.count = responseCount;
                        this.changeCartValues(response.data);
                        this.$emit("onChange", response.data);
                        this.waiter = null;

                        if (response.data.type === 'add') {
                            this.renderCartMessage();
                        }

                        if (response.data.type === 'add') {
                            BX.onCustomEvent(window, 'onProductCartAdd', {
                                count: this.count,
                                productId: this.productId,
                                sizeId: this.sizeId
                            });
                        }

                    }
                );
            }, this.waitTime);
        },
        changeCartValues(data) {
            const emptyClass = "mini-cart__content--empty";
            document.querySelectorAll(".total-sum").forEach(node => {
                if (node.classList.contains("mini-cart__content")) {
                    if (data.total.sum.value) {
                        node.classList.remove(emptyClass);
                    } else {
                        node.classList.add(emptyClass);
                    }
                }
                node.textContent = `${data.total.sum.format}`;
            });

            document.querySelectorAll('.mini-cart').forEach(node => {
                if (data.type === 'add') {
                    node.classList.add('--shake-add');
                } else if(data.type === 'remove') {
                    node.classList.add('--shake-remove');
                }
                setTimeout(() => {
                    node.classList.remove('--shake-add', '--shake-remove');
                }, 500);
            });

            document.querySelectorAll(".total-count").forEach(node => {
                node.textContent = `${data.total.count.format}`;
            });
        },
        renderCartMessage() {
            const id = 'cartMessage';
            let node = document.getElementById(id);
            if (!node) {
                node = Methods.createStructure({
                    classes: ['cart-message', 'note', 'note--success'],
                    inner: `${BX.message.NOTIFY_CART_ADD} <strong><a href="/cart/" class="simple-link">${BX.message.NOTIFY_TO_CART}</a></strong>`,
                    id: id
                });
                document.body.append(node);
            }
            setTimeout(() => node.classList.add('is-active'), 0);
            setTimeout(() => node.classList.remove('is-active'), 2000);
        }
    },
    emits: ["onChange"],
    mounted() {
        this.count = this.cartCount ? this.cartCount : "";
        BX.onCustomEvent(window, 'onCounterInit', this.sizeId);
    },
    template: `
		<div
			class="button button--center button--orange button--100 button--long product__button"
			:class="{'is-loading': loading}"
			v-if="!count && startButton"
			@click="add"
		>
			<span class="loader" v-if="loading"></span>
			{{ lang.toCart }}
		</div>
		<label class="cart-counter" :class="{'cart-counter--min': min}" v-else>
            <div class="cart-counter__button cart-counter__button--remove" @click="remove"></div>
            <input type="number" class="cart-counter__count" v-model="count" @keyup="set" @change="set">
            <div class="cart-counter__button cart-counter__button--add" @click="add"></div>
        </label>
	`
};