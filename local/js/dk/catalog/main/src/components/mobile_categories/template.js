export const template = `
<div class="catalog-mobile-categories" :class="{'is-hidden': opened}" ref="mobileOpener">
    <button 
    class="catalog-category is-active"
    :class="{
    'is-tagged': isTagged,
    'is-tagged--new': isNew,
    'catalog-category--min': !isRoot
    }"
    @click="showTree"
    >
        <img :src="image" alt="" :class="{'catalog-category__image': isRoot, 'catalog-category__picture': !isRoot}" v-if="image">
        <span class="catalog-category__label">
            <a :href="category.url" class="catalog-category__link">{{ category.title }}</a>
        </span>
        <span class="catalog-category__arrow catalog-category__arrow--down"></span>
    </button>
</div>
`;