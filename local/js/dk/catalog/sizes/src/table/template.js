export const template = `
<table v-if="showPlaceholder" ref="table">
    <thead>
        <tr>
            <th v-for="j in 4">
                <div class="content-loader"></div>
            </th>
        </tr>
    </thead>
    <tbody>
        <tr v-for="i in 6">
            <td v-for="j in 4">
                <div class="content-loader"></div>
            </td>
        </tr>
    </tbody>
</table>
<table ref="table" v-else>
    <thead>
        <tr>
            <th class="product-table__th product-table__th--size">{{ sizesHeader }}</th>
            <th class="product-table__th product-table__th--price">{{ priceHeader }}</th>
            <th class="product-table__th product-table__th--counter">{{ countHeader }}</th>
            <th class="product-table__th product-table__th--total">{{ sumHeader }}</th>
        </tr>
    </thead>
    <tbody>
        <Row 
        v-for="size in sizes" 
        :key="size.id" 
        :size="size"
        :productId="productId"
        :containerWidth="containerWidth"
        :lang="lang"
        :miniToCart="miniToCart"
        ></Row>
    </tbody>
</table>
`;