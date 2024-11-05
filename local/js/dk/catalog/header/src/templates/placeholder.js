export const template = `
<ul class="header-catalog__categories catalog-categories">
    <li v-for="index in 8" :key="index">
        <div class="catalog-category">
            <div>
                <span class="content-loader content-loader--ah catalog-category__image"></span>
            </div>
            <div class="catalog-category__label content-loader"></div>
        </div>
    </li>
</ul>
<div class="header-catalog__main">
    <div class="title title--min-bottom title--h3 header-catalog__title content-loader content-loader--20"></div>
    <div class="header-catalog__main-content">
        <div class="header-catalog__rows">
            <div class="header-catalog__column" v-for="index in 3" :key="index">
                <div class="header-catalog__column-image content-loader content-loader--10 content-loader--ah content-loader--ar1"></div>
                <div class="header-catalog__column-list">
                    <div class="header-catalog__column-title content-loader content-loader--80"></div>
                        <ul class="header-catalog__column-ul">
                            <li>
                                <div class="content-loader"></div>
                                <ul>
                                    <li>
                                        <div class="content-loader content-loader--90"></div>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <div class="content-loader content-loader--70"></div>
                            </li>
                            <li>
                                <div class="header-catalog__more content-loader content-loader--20"></div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="header-catalog__products">
                <div class="mini-product mini-product--no-price header-catalog__product" v-for="index in 6" :key="index">
                    <div class="mini-product__image mini-product__image--no-border content-loader content-loader--ah"></div>
                    <div class="mini-product__content">
                        <div class="mini-product__title">
                            <div class="content-loader content-loader--90"></div>
                            <div class="content-loader content-loader--80"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="header-catalog__image">
    <div class="content-loader content-loader--100h"></div>
</div>
`;