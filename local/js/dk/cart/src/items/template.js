export const template = `
<div class="cart__items">
    <Sizes 
    v-for="item in items"
    :productId="item.id"
    :sizeIdList="item.sizes"
    :full="true"
    :toggleShow="true"
    ></Sizes>
</div>
`;