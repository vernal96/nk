export const template = `
<div class="mm__main mm-item" :class="{'is-mm': isActive}">
  <div class="mm__header">
    <button class="mm__button" v-if="activeItem" @click="back">
      <i class="icon icon--mm-back"></i>
    </button>
    <div class="mm__empty-button" v-else></div>
    <div class="mm__title">
        <template v-if="activeItem">
            <img :src="activeItem.icon" alt="" class="mm__icon" v-if="activeItem.icon">
            <img :src="activeItem.images.picture.src" alt="" class="mm__icon mm__icon--image" v-else-if="activeItem.images.picture">
        </template>
        <span class="mm__title-text">
      {{ mmTitle }}
      </span>
    </div>
    <button class="mm__button mmo">
      <i class="icon icon--mm-close"></i>
    </button>
  </div>
  <div class="mm__body hscroll">
    <ul>
      <li class="mm__parent" :class="{'mm__bold': item.isCatalog}" v-for="item, index in items" :key="item.id">
        <a :href="item.url" class="mm__link">
          <img :src="item.icon" alt="" class="mm__icon" v-if="item.icon">
          <img :src="item.images.picture.src" alt="" class="mm__icon mm__icon--image" v-else-if="item.images.picture">
          <span>
            {{ item.title }}
          </span>
        </a>
        <button class="mm__show" v-if="item.children.length" @click="changeActiveItem(index)">
          <i class="icon icon--arrow-right-blue"></i>
        </button>
        <button class="mm__show" :class="{'is-loading': loadingCatalog}" v-if="item.isCatalog" @click="changeActiveItemCatalog(index)">
            <span class="loader loader--gray" v-if="loadingCatalog"></span>
          <i class="icon icon--arrow-right-blue"></i>
        </button>
      </li>
    </ul>
  </div>
  <div class="mm__footer">
    <div v-html="login"></div>
    <button class="button button--link button--link-orange button--medium button--wrap button--pi0 fastpay">
      <i class="icon icon--check-form"></i>
      {{ lang.fastpay }}
    </button>
    <button class="button button--orange button--long modal-trigger" data-src="#recall">
        {{ lang.recall }}
    </button>
    <div class="mm__contact">
      <a :href="phoneLink" class="main-phone">
        <i class="icon icon--phone-orange"></i>
        {{ phone.format }}
      </a>
    </div>
    <SocNet :data="socnet"></SocNet>
  </div>
</div>
<div class="mm__overlay mm-item mmo" :class="{'is-mm': isActive}"></div>
`;  