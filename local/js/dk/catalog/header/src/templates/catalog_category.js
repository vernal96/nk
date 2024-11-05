export const template = `
    <li>
        <div
        class="catalog-category catalog-category--alt-hover"
        :class="{
            'is-active': isActive,
            'is-tagged': isTagged,
            'is-tagged--new': isNew
        }"
         v-if="!isEmpty"
        @click="select"
        >
            <img 
                :src="category.icon"
                class="catalog-category__image"
                v-if="category.icon"
            >
            <div class="catalog-category__label">
                <a :href="category.url" class="catalog-category__link">{{ category.title }}</a>
            </div>
            <div class="catalog-category__arrow"></div> 
        </div>
        <a :href="category.url"
        class="catalog-category catalog-category--alt-hover"
        :class="{
            'is-active': isActive,
            'is-tagged': isTagged,
            'is-tagged--new': isNew
        }"
        v-else
        >
            <img 
                :src="category.icon"
                class="catalog-category__image"
                v-if="category.icon"
            >
            <span class="catalog-category__label">
                {{ category.title }}
            </span>
        </a>
    </li>
`;