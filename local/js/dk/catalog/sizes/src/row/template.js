export const template = `
<tr>
    <td class="product-table__col">{{ size.title }}</td>
    <td class="product-table__col">
        {{ size.price }}
    </td>
    <td class="product-table__col product-table__col--counter">
        <Counter 
        :min="true" 
        :startButton="false" 
        :cartCount="size.cart.count.value"
        :productId="productId"
        :sizeId="size.id"
        @onChange="changeSum"
        ></Counter>
    </td>
    <td class="product-table__col" :class="{'empty': !valueSum}">
        <div class="total">
            {{ sum }}
            <a href="/cart/" class="button button--orange button--min button--small" v-if="valueSum">
                <i class="icon icon--cart"></i>
                <template v-if="!miniToCart">
                    {{ lang.toCart }}
                </template>
            </a>
        </div>
    </td>
</tr>
`;