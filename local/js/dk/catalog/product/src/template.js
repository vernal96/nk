export const template = `
<div class="product" :class="{'product--section': data.isSection}">
    <a :href="data.url" class="product__header">
        <picture class="product__image">
            <img :src="data.picture.src" :alt="data.picture.alt" :title="data.picture.title">
        </picture>
        <span class="product__title">{{ data.name }}</span>
    </a>
    <template v-if="!data.isSection">
        <span class="product__price" :class="{'product__price--min': data.price.from}">
            <template v-if="data.price.from">{{ lang.from }}</template> {{ data.price.cost }} {{ lang.currency }}
        </span>
        <SizesButton v-if="data.price.from"
        :productId="data.id"
        ></SizesButton>
        <Counter v-else
        :productId="data.id"
        :sizeId="data.price.sizeId"
        :cartCount="data.price.cartCount"
        ></Counter>
    </template>
</div>
`;