export default {
    ym: {
        id: 100346127,
        init() {
            try {
                this.productPageReady();
                this.addToCart();
                this.orderSuccess();
                this.phoneClick();
                this.recallClick();
                this.recallSubmitted();
                this.cartPageReady();
            } catch (e) {}
        },
        productPageReady() {
            BX.addCustomEvent('onProductPageReady', () => {
                ym(this.id,'reachGoal','onProductPageReady', {
                    url: location.pathname
                });
            });
        },
        cartPageReady() {
            BX.addCustomEvent('onCartPageReady', () => {
                ym(this.id,'reachGoal','onCartPageReady');
            });
        },
        addToCart() {
            BX.addCustomEvent('onProductCartAdd', (object) => {
                ym(this.id,'reachGoal','onAddToCart', object.data);
            });
        },
        orderSuccess() {
            BX.addCustomEvent('onCartSubmitSuccess', () => {
                ym(this.id,'reachGoal','onOrderSuccess');
            });
        },
        phoneClick() {
            BX.addCustomEvent('onMainPhoneClick', () => {
                ym(this.id,'reachGoal','onPhoneClick');
            });
        },
        recallClick() {
            BX.addCustomEvent('onRecallOpen', () => {
                ym(this.id,'reachGoal','onRecallClick');
            });
        },
        recallSubmitted() {
            BX.addCustomEvent('onFormSubmitSuccess', () => {
                ym(this.id,'reachGoal','onFormSubmitSuccess');
            });
        }
    }
}