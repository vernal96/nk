export const template = `
<div class="product-table" v-if="full">
    <div class="product-table__header" v-if="showPlaceholder">
        <div class="product-table__image loader-fill"></div>
        <div class="product-table__title content-loader"></div>
    </div>
    <div class="product-table__header" v-else>
        <div 
        class="product-table__image"
        :class="{'pic': data.fullImage}"
        :data-fancybox="!!data.fullImage"
        :data-src="data.fullImage"
        >
            <img :src="data.image" alt="">
        </div>
        <a :href="data.url" class="product-table__title">{{ data.title }}</a>
        <div class="product-table__show" v-if="toggleShow" @click="show = !show">{{ showButton }}</div>
    </div>
    <transition 
        @enter="showEnter"
        @after-enter="showAfterEnter"
        @before-leave="hideBeforeLeave"
        @leave="hideLeave"
        name="vertical-slide"
        >
        <div class="product-table__content" v-if="show">
            <Table
                :showPlaceholder="showPlaceholder"
                :productId="productId"
                :sizes="sizes"
            ></Table>
        </div>
    </transition>
</div>
<Table v-else
    :showPlaceholder="showPlaceholder"
    :productId="productId"
    :sizes="sizes"
></Table>
`;