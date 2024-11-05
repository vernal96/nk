export const template = `
<li v-if="showCategory">
    <a :href="category.url">{{ limit }} {{ category.title }}</a>
    <ul class="header-catalog__column-ul" v-if="showSubcategories">
        <headerCatalogSubcategory
        v-for="subCategory in category.children"
        :key="subCategory.id"
        :category="subCategory"
        :useLimit="useLimit"
        ></headerCatalogSubcategory>
    </ul>
</li>
`;