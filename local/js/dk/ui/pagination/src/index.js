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
        pageWindow: 5,
    }),
    computed: {
        pageCount() {
            if (this.elementCount < this.pageSize) return 0;
            return Math.ceil(this.elementCount / this.pageSize);
        },
        pages() {
            let startPage, endPage;
            if (this.currentPage > Math.floor(this.pageWindow / 2) + 1 && this.pageCount > this.pageWindow) {
                startPage = this.currentPage - Math.floor(this.pageWindow / 2);
            } else {
                startPage = 1;
            }
            if (this.currentPage <= this.pageCount - Math.floor(this.pageWindow / 2) && startPage + this.pageWindow - 1 <= this.pageCount) {
                endPage = startPage + this.pageWindow - 1;
            } else {
                endPage = this.pageCount;
                if (endPage - this.pageWindow + 1 >= 1) {
                    startPage = endPage - this.pageWindow + 1;
                }
            }
            return {
                start: startPage,
                end: endPage
            }
        },
        middlePages() {

        }
    },
    emits: ["onChangePage"],
    methods: {
        goPage(page) {
            if (page === this.currentPage) return;
            this.$emit("onChangePage", page);
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