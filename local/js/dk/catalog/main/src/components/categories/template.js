export const template = `
<ul class="catalog-categories scroll" ref="catalogCategories">
    <Category 
    v-for="category in categories"
    :category="category"
    :activeCategories="activeCategories"
    :showChildrenCategories="showChildrenCategories"
    :loadingCategory="loadingCategory"
    :start="start"
    :loading="loading"
    :key="category.id"
    @onChangeActive="changeActive"
    ></Category>
</ul>
`;