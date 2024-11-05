export const template = `
<Placeholder v-if="preload"></Placeholder>
<template v-else>
    <ul class="header-catalog__categories catalog-categories scroll">
        <CatalogCategory 
        v-for="category in tree"
        :key="category.ID"
        :category="category"
        :activeCategory="activeCategory"
        @onSelect="changeActiveCategory"
        ></CatalogCategory>
    </ul>
    <div class="header-catalog__main">
        <div class="title title--min-bottom title--h3 header-catalog__title">
            <a :href="activeCategory.url">{{ activeCategory.title }}</a>
        </div>
        <div class="header-catalog__main-content scroll">
            <div class="header-catalog__rows">
                <CategoryColumn
                v-for="category in activeCategory.children"
                :key="category.id"
                :category="category"
                ></CategoryColumn>
            </div>
        </div>
    </div>
    <img 
    :src="activeCategory.images.detail.src"
    class="header-catalog__image"
    :alt="activeCategory.images.detail.alt"
    :title="activeCategory.images.detail.title"
    v-if="activeCategory.images.detail"
    >
</template>
`;