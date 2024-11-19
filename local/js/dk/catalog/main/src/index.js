import "ui.vue3";
import {component as Categories} from "./components/categories/component";
import {component as MobileCategories} from "./components/mobile_categories/component";
import {Product} from "dk.catalog.product";
import {Pagination} from "dk.ui.pagination";
import {Methods} from "dk.main.methods";

BX.ready(() => {
    const pageSize = 6;
    BX.ajax.runComponentAction("dk:catalog", "getTree", {
        mode: "class",
    }).then(
        response => {

            BX.ajax.runComponentAction("dk:catalog", "getProducts", {
                mode: "class",
                data: {
                    pageSize: pageSize,
                    page: 1,
                    sectionId: response.data[0].id
                }
            }).then(
                productResponse => {
                    BX.Vue3.createApp({
                        name: "MainCatalog",
                        data: () => ({
                            start: true,
                            catalog: response.data,
                            activeSectionIdPath: [],
                            categoryWithLoader: 0,
                            showSectionIdPath: [],
                            activeCategory: {},
                            loading: false,
                            page: {
                                size: pageSize,
                                current: 1
                            },
                            lang: {
                                closeMobile: BX.message.DK_CATALOG_MAIN_CLOSE_MOBILE
                            },
                            catalogBtn: {
                                title: BX.message.DK_CATALOG_MAIN_GO_TO_TITLE,
                                url: BX.message.DK_CATALOG_MAIN_GO_TO_URL,
                            },
                            products: productResponse.data,
                            mobileOpened: false
                        }),
                        emits: ["onChangeActive"],
                        watch: {
                            mobileOpened(value) {
                                if (value) {
                                    Methods.toggleBodyOverflow();
                                } else {
                                    Methods.toggleBodyOverflow(false);
                                }
                            }
                        },
                        methods: {
                            changeActive(category) {
                                this.loading = true;
                                this.categoryWithLoader = category.id;
                                BX.ajax.runComponentAction("dk:catalog", "getProducts", {
                                    mode: "class",
                                    data: {
                                        pageSize: this.page.size,
                                        page: 1,
                                        sectionId: category.id
                                    }
                                }).then(
                                    response => {
                                        this.page.current = 1;
                                        this.activeCategory = category;
                                        this.products = response.data;
                                        const needShow = !this.showSectionIdPath.length;
                                        const current = this.activeSectionIdPath.includes(category.id);
                                        this.activeSectionIdPath = [];
                                        this.showSectionIdPath = [];
                                        this.calculateActiveIdPath(category);
                                        this.activeSectionIdPath.reverse();
                                        this.showSectionIdPath.reverse();
                                        if (current) {
                                            if (!needShow) {
                                                this.showSectionIdPath.pop();
                                            }
                                            if (category.parent) {
                                                this.activeSectionIdPath.pop();
                                            }
                                        }
                                        this.loading = false;
                                        this.categoryWithLoader = 0;
                                    })
                            },
                            setCatalogParents(category, parent = null) {
                                category.parent = parent;
                                category.children.map(subcategory => this.setCatalogParents(subcategory, category));
                            },
                            calculateActiveIdPath(category) {
                                this.activeSectionIdPath.push(category.id);
                                this.showSectionIdPath.push(category.id);
                                if (category.parent) {
                                    this.calculateActiveIdPath(category.parent);
                                }
                            },
                            changeCurrentPage(page) {
                                this.loading = true;
                                BX.ajax.runComponentAction("dk:catalog", "getProducts", {
                                    mode: "class",
                                    data: {
                                        pageSize: this.page.size,
                                        page: page,
                                        sectionId: this.activeCategory.id
                                    }
                                }).then(response => {
                                        this.products = response.data;
                                        this.page.current = page;
                                        this.loading = false;
                                        // Methods.scrollToHeaderElement();
                                    }
                                );
                            },
                            mobileCatalogOpen() {
                                if (this.mobileOpened) return;
                                this.mobileOpened = true;

                                this.toggleHeaderAfterScrollEnd(true);

                                window.scroll({
                                    top: this.$refs.mobileButton.$refs.mobileOpener.getBoundingClientRect().top + window.pageYOffset,
                                    behavior: "smooth"
                                });

                            },
                            toggleHeaderAfterScrollEnd(hide) {
                                const fixedHeaderNode = document.getElementById("fixedHeader");
                                if (fixedHeaderNode) {
                                    if (hide) {
                                        fixedHeaderNode.classList.add("is-disabled");
                                    } else {
                                        fixedHeaderNode.classList.remove("is-disabled");
                                    }
                                }
                            },
                            closeMobileCatalog() {
                                this.mobileOpened = false;
                                this.toggleHeaderAfterScrollEnd(false);
                            }
                        },
                        updated() {
                            this.$refs.categories.$refs.catalogCategories.style.height = this.$refs.catalogMain.clientHeight + "px";
                        },
                        mounted() {
                            this.catalog.map(category => this.setCatalogParents(category));
                            this.activeSectionIdPath.push(this.catalog[0].id);
                            this.showSectionIdPath.push(this.catalog[0].id);
                            this.activeCategory = this.catalog[0];
                            this.start = false;
                        },
                        components: {
                            Categories,
                            Product,
                            Pagination,
                            MobileCategories
                        },
                        template: `
                            <MobileCategories
                            :category="activeCategory"
                            :opened="mobileOpened"
                            @onMobileCatalogOpen="mobileCatalogOpen"
                            ref="mobileButton"
                            ></MobileCategories>
                            <div class="catalog-categories-outer" :class="{'is-active': mobileOpened}">
                                <Categories 
                                :categories="catalog" 
                                :activeCategories="activeSectionIdPath"
                                :showChildrenCategories="showSectionIdPath"
                                :loadingCategory="categoryWithLoader"
                                :start="start"
                                :loading="loading"
                                @onChangeActive="changeActive"
                                ref="categories"
                                ></Categories>
                                <button 
                                class="button button--bordered button--100 button--white mobile-catalog-categories-closer"
                                @click="closeMobileCatalog"
                                >{{ lang.closeMobile }}</button>
                            </div>
                            <div class="catalog-products catalog-main loader-container" :class="{'is-loading': loading}" ref="catalogMain">
                                <div class="catalog-products__main">
                                    <Product v-for="product in products" :key="product.id" :data="product"></Product>
                                </div>
                                <div class="catalog-products__footer">
                                    <Pagination
                                    v-if="activeCategory.elCount"
                                    :currentPage="page.current"
                                    :pageSize="page.size"
                                    :elementCount="activeCategory.elCount"
                                    @onChangePage="changeCurrentPage"
                                    ></Pagination>
                                    <a :href="catalogBtn.url" class="button catalog__button">
                                        <i class="icon icon--burger"></i>
                                        {{ catalogBtn.title }}
                                    </a>
                                </div>
                            </div>
                        `
                    }).mount("#catalogMainWrapper");
                }
            )
        }
    );
});