import "./style.css";

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
        waiter: null
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
                BX.ajax.runComponentAction("dk:catalog", "cartUpdate", {
                    mode: "class",
                    data: {
                        productId: this.productId,
                        id: this.sizeId,
                        count: +this.count
                    }
                }).then(
                    response => {
                        const responseCount = response.data.count.value ? response.data.count.value : "";
                        this.loading = false;
                        this.count = responseCount;
                        this.changeCartValues(response.data);
                        this.$emit("onChange", response.data);
                        this.waiter = null;
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
            document.querySelectorAll(".total-count").forEach(node => {
                node.textContent = `${data.total.count.format}`;
            });
        }
    },
    emits: ["onChange"],
    mounted() {
        this.count = this.cartCount ? this.cartCount : "";
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