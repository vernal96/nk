export default {
    ym: {
        id: 100346127,
        init() {
            try {
                this.productPageReady();
            } catch (e) {

            }
        },
        productPageReady() {
            BX.addCustomEvent('onProductPageReady', () => {
                ym(this.id,'reachGoal','onProductPageReady', {
                    url: location.pathname
                })
            });
        }
    }
}