export default {
    ym: {
        id: 100346127,
        init() {
            try {
                this.productPageReady();
                this.addToCart();
                this.onOrderSuccess();
                this.onPhoneClick();
                this.onRecallClick();
                this.onRecallSubmitted();
            } catch (e) {}
        },
        productPageReady() {
            BX.addCustomEvent('onProductPageReady', () => {
                ym(this.id,'reachGoal','onProductPageReady', {
                    url: location.pathname
                });
            });
        },
        addToCart() {
            BX.addCustomEvent('onProductCartAdd', (object) => {
                ym(this.id,'reachGoal','onProductCartAdd', object.data);
            });
        },
        onOrderSuccess() {
            BX.addCustomEvent('onCartPageReady', () => {
                ym(this.id,'reachGoal','onCartPageReady');
            });
        },
        onPhoneClick() {
            BX.addCustomEvent('onMainPhoneClick', () => {
                ym(this.id,'reachGoal','onPhoneClick');
            });
        },
        onRecallClick() {
            BX.addCustomEvent('onRecallOpen', () => {
                ym(this.id,'reachGoal','onRecallClick');
            });
        },
        onRecallSubmitted() {
            BX.addCustomEvent('onRecallOpen', () => {
                ym(this.id,'reachGoal','onFormSubmitSuccess');
            });
        }
    }
}