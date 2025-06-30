export default {
    init() {
        this.impressions.init(this);

        // BX.addCustomEvent('onSingleProductPageReady', (event) => {
        //     this.push('detail', [event.data]);
        // });

        BX.addCustomEvent('onProductCartAdd', (event) => {
            this.push('add', [event.data.sizeId]);
        });

        BX.addCustomEvent('onCartSubmitSuccess', (event) => {
            this.push('purchase', event.data.items, {
                id: `#${event.data.id}`,
                revenue: event.data.sum.value
            });
        });

        BX.addCustomEvent('onCounterInit', (event) => {
            this.push('detail', [event.data]);
        });
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
                parent.push('impressions', this.items);
                this.items = [];
            }, 500);
        }
    },
    push(type, ids, action = null) {
        if (typeof window.dataLayer == 'undefined') return;

        BX.ajax.runAction('dk:nk.SEO.getItems', {
            data: {
                ids: ids
            }
        }).then(({data}) => {
            window.dataLayer.push({
                ecommerce: {
                    currencyCode: 'RUB',
                    [type]: {
                        actionField: action,
                        products: data,
                    }
                }
            });
        }).catch(({errors}) => {
            console.error(errors);
        });
    }
}