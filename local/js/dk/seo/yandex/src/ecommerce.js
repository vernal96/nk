export default {
    init() {
        this.impressions();
    },
    impressions() {
        BX.addCustomEvent('onProductPageReady', (data) => {
            this.push({
                impressions: {
                    actionField: null,
                    products: data
                }
            });
        });
    },
    push(data) {
        if (typeof window.dataLayer == 'undefined') return;
        window.dataLayer.push({
            'ecommerce': {
                'currencyCode': 'RUB',
                data
            }
        });
    }
}