export const template = `
    <div class="pagination" v-if="pageCount">
        <button class="pagination__item" :class="{'pagination__item--closed': hidePrev}" @click="goStart">{{ lang.start }}</button>
        <button class="pagination__arrow pagination__arrow--prev" :class="{'pagination__arrow--closed': hidePrev}" @click="goPrev"></button>
        <button 
        class="pagination__item"
        :class="{'pagination__item--active': pageNum === currentPage}"
        v-for="pageNum in pages"
        @click="goPage(pageNum)"
        >
        {{ pageNum }}
        </button>
        <button class="pagination__arrow pagination__arrow--next" :class="{'pagination__arrow--closed': hideNext}" @click="goNext"></button>
        <button class="pagination__item" :class="{'pagination__item--closed': hideNext}" @click="goEnd">{{ lang.end }}</button>
    </div>
    <div v-else></div>
`;