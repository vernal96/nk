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
            <th>{{ sizesHeader }}</th>
            <th>{{ priceHeader }}</th>
            <th>{{ countHeader }}</th>
            <th>{{ sumHeader }}</th>
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