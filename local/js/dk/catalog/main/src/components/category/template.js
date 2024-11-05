export const template = `
<li>
    <div 
    class="catalog-category"
    :class="{
    'is-tagged': isTagged,
    'is-tagged--new': isNew,
    'is-active': isActive,
    'catalog-category--min': isSubcategory
    }"
    @click="changeActive(category, $event)"
    >
        <img 
        :src="image.src" 
        :class="image.elementClass"
        alt="" 
        v-if="image.src">
        <span class="catalog-category__label">
            <a :href="category.url" class="catalog-category__link">{{ category.title }}</a>
        </span>
        <span class="loader loader--blue" v-if="isLoading"></span>
        <span class="catalog-category__arrow catalog-category__arrow--down" v-if="showArrow"></span>
    </div>
    <transition
        @enter="showEnter"
        @after-enter="showAfterEnter"
        @before-leave="hideBeforeLeave"
        @leave="hideLeave"
        name="vertical-slide"
    >
        <ul 
        class="catalog-categories__children"
        :class="{'catalog-categories__children--not-transform': start}"
        v-if="hasChildren && showChildren"
        >
            <Category 
            v-for="subcategory in category.children"
            :category="subcategory"
            :start="start"
            :loading="loading"
            :activeCategories="activeCategories"
            :showChildrenCategories="showChildrenCategories"
            :isSubcategory="true"
            :loadingCategory="loadingCategory"
            :key="subcategory.id"
            @onChangeActive="changeActive"
            ></Category>
        </ul>
    </transition>
</li>
`;