export const template = `
<tr>
    <td class="product-table__col">{{ size.title }}</td>
    <td class="product-table__col" v-if="showBoxColumn">{{ size.box }}</td>
    <td class="product-table__col">
        {{ size.price }}
        <small v-if="!showBoxColumn">
            ({{ size.box }} {{ boxHeader }})
        </small>
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
    <td class="product-table__col" :class="{'empty': !this.valueSum}">{{ this.sum }}</td>
</tr>
`;