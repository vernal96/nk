export default {
    ym: {
        id: 100346127,
        init() {
            this.productPageReady();
        },
        productPageReady() {
            BX.addCustomEvent('onProductPageReady', () => {
                ym(this.id,'reachGoal','onProductPageReady', {
                    url: location.href
                })
            });
        }
    }
}