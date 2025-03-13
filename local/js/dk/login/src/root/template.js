export const template = `
<div class="modal__header">
    <div class="modal__title">{{ lang.title }}</div>
    <div class="modal__subtitle">
        <div class="inline-links">
            <div
                v-for="tab, index in tabs"
                class="inline-link"
                :class="{'inline-link--is-active': tab.id === activeTab}"
                :key="tab.id"
                @click="toggleSlide(index, tab.id)"
            >{{ tab.title }}</div>
        </div>
    </div>
</div>
<div class="modal__forms swiper" ref="swiper">
    <div class="swiper-wrapper">
        <div class="swiper-slide">
            <Authorize @onUpdateContent="updateAutoHeight"></Authorize>
        </div>
        <div class="swiper-slide">
            <Registration @onUpdateContent="updateAutoHeight"></Registration>
        </div>
    </div>
</div>
`;