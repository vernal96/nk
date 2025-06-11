export default {
    init() {
        this.impressions.init(this);
    },
    impressions: {
        items: [],
        parent: null,
        init(parent) {
            this.parent = parent;
            BX.addCustomEvent('onCounterInit', (event) => {
                this.items.push(event.data);
            });
            setInterval(() => {
                if (!this.items.length) return;
                this.getData();
                this.items = [];
            }, 500);
        },
        getData() {
            BX.ajax.runAction('dk:nk.SEO.impressions', {
                data: {
                    ids: this.items
                }
            }).then(({data}) => {
                this.parent.push({
                    impressions: data
                });
            }).catch(({errors}) => {
                console.error(errors);
            });
        }
    },
    push(data) {
        if (typeof window.dataLayer == 'undefined') return;
        window.dataLayer.push({
            'ecommerce': {
                'currencyCode': 'RUB',
                ...data
            }
        });
    }
}