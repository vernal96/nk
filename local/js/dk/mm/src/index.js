import "ui.vue3";
import {component as Root} from "./components/root/component";

BX.ready(() => {
    BX.Vue3.createApp(Root).mount("#mm");
});
