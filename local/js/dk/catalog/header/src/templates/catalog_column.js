export const template = `
<div class="header-catalog__column">
    <img 
    :src="category.images.picture.src"
    :alt="category.images.picture.alt"
    :title="category.images.picture.title"
    class="header-catalog__column-image"
    v-if="category.images.picture"
    >
    <div class="header-catalog__column-list">
        <div class="header-catalog__column-title">
            <a :href="category.url">{{ category.title }}</a>
        </div>
        <ul class="header-catalog__column-ul" v-if="category.children.length">
            <SubCategory 
            v-for="subCategory in category.children"
            :key="subCategory.id"
            :category="subCategory"
            :useLimit="useLimit"
            ></SubCategory>
        </ul>
        <button 
        class="more header-catalog__more" 
        v-if="category.showMore && useLimit" 
        @click="showAll"
        >{{ lang.more }}</button>
    </div>
</div>
`;