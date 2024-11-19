export const template = `
    <div class="pagination" v-if="pageCount > 1">
        <template v-if="currentPage > 1">
            <button class="pagination__arrow pagination__arrow--prev" @click="goPrev"></button>
            <button @click="goPage(1)" class="pagination__item">1</a>
        </template>
        <template v-else>
            <div class="pagination__arrow pagination__arrow--prev pagination__arrow--closed"></div>
            <div class="pagination__item pagination__item--active">1</div>
        </template>
        <div class="pagination__item pagination__item--empty" v-if="pages.start > 1">...</div>
        
        <template v-for="page in pages.end - 2">
        <button v-if="page + 1 > pages.start"
        class="pagination__item"
        :class="{'pagination__item--active': page + 1 == currentPage}"
        @click="goPage(page + 1)"
        >{{ page + 1 }}</button>
        </template>
        
        <template v-if="pages.end < pageCount">
            <button class="pagination__item" @click="goPage(pages.end)" v-if="pages.end + 1 == pageCount">{{ pages.end }}</button>
            <div class="pagination__item pagination__item--empty" v-else>...</div>
        </template>
        
        <template v-if="currentPage < pageCount">
            <button class="pagination__item" @click="goPage(pageCount)" v-if="pageCount > 1">{{ pageCount }}</button>
            <button class="pagination__arrow pagination__arrow--next" @click="goNext"></button>
        </template>
        <template v-else>
            <div class="pagination__item pagination__item--active" v-if="pageCount > 1">{{ pageCount }}</div>
            <div class="pagination__arrow pagination__arrow--next pagination__arrow--closed"></div>
        </template>
        
    </div>
    <div v-else></div>
`;