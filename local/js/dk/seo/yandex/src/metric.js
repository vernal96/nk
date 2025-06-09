export default {
    id: 100346127,
    init() {
        this.productPageReady();
        this.addToCart();
        this.orderSuccess();
        this.phoneClick();
        this.recallClick();
        this.recallSubmitted();
        this.cartPageReady();
    },
    sendEvent(eventName, data = {}) {
        try {
            ym(this.id,'reachGoal', eventName, data);
        } catch (e) {
            console.error('YM is not defined.');
        }

    },
    productPageReady() {
        BX.addCustomEvent('onProductPageReady', () => {
            this.sendEvent('onProductPageReady', {url: location.pathname});

        });
    },
    cartPageReady() {
        BX.addCustomEvent('onCartPageReady', () => {
            this.sendEvent('onCartPageReady');
        });
    },
    addToCart() {
        BX.addCustomEvent('onProductCartAdd', (object) => {
            this.sendEvent('onAddToCart', object.data);
        });
    },
    orderSuccess() {
        BX.addCustomEvent('onCartSubmitSuccess', (object) => {
            this.sendEvent('onOrderSuccess', object.data);
        });
    },
    phoneClick() {
        BX.addCustomEvent('onMainPhoneClick', () => {
            this.sendEvent('onPhoneClick');
        });
    },
    recallClick() {
        BX.addCustomEvent('onRecallOpen', () => {
            this.sendEvent('onRecallClick');
        });
    },
    recallSubmitted() {
        BX.addCustomEvent('onFormSubmitSuccess', () => {
            this.sendEvent('onFormSubmitSuccess');
        });
    }
}