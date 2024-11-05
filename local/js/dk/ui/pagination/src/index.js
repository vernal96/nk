import {template} from "./template";

export const Pagination = {
    name: "Pagination",
    props: {
        currentPage: {
            type: Number,
            required: true
        },
        pageSize: {
            type: Number,
            required: true
        },
        elementCount: {
            type: Number,
            required: true
        }
    },
    data: () => ({
        maxPage: 5,
        lang: {
            start: BX.message.PAGINATION_START,
            end: BX.message.PAGINATION_END
        }
    }),
    computed: {
        pageCount() {
            if (this.elementCount < this.pageSize) return 0;
            return Math.ceil(this.elementCount / this.pageSize);
        },
        pages() {
            const currentPage = this.currentPage;
            const pages = [currentPage];
            const max = Math.min(this.pageCount, this.maxPage);
            let plus = false;
            let counter = 1;
            while (pages.length < max) {
                let newValue = currentPage + (plus ? counter : 0 - counter);
                if (plus) counter++;
                plus = !plus;
                if (newValue > 0 && newValue <= this.pageCount) {
                    pages.push(newValue);
                }
            }
            pages.sort((a, b) => a - b);
            return pages;
        },
        hidePrev() {
            return this.currentPage === 1;
        },
        hideNext() {
            return this.currentPage === this.pageCount;
        }
    },
    emits: ["onChangePage"],
    methods: {
        goPage(page) {
            this.$emit("onChangePage", page);
        },
        goStart() {
            if (this.hidePrev) return;
            this.goPage(1);
        },
        goEnd() {
            if (this.hideNext) return;
            this.goPage(this.pageCount)
        },
        goPrev() {
            if (this.hidePrev) return;
            this.goPage(this.currentPage - 1);
        },
        goNext() {
            if (this.hideNext) return;
            this.goPage(this.currentPage + 1);
        }
    },
    template: template
};