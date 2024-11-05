import {Methods} from "dk.main";
import "ui.vue3";
import "./style.css";

import {component as Root} from "./components/root";

const catalogButtonSelector = ".menu__catalog",
    catalogRootClass = "header-catalog",
    catalogOverlayClass = "header-catalog-overlay",
    activeClass = "is-active";

let catalogRootElement,
    catalogLayoutElement;

BX.ready(() => {
    Methods.on("click", catalogButtonSelector, function (event) {
        event.preventDefault();
        catalogRootElement = document.querySelector(`.${catalogRootClass}`);
        catalogLayoutElement = document.querySelector(`.${catalogOverlayClass}`);
        if (!catalogRootElement) {
            catalogRootElement = Methods.createStructure({classes: catalogRootClass});
            catalogLayoutElement = Methods.createStructure({classes: catalogOverlayClass});
            BX.Vue3.createApp(Root).mount(catalogRootElement);
        }
        this.parentNode.append(catalogRootElement, catalogLayoutElement);
        toggleActive("toggle");
    });
    Methods.on("click", `.${catalogOverlayClass}`, () => toggleActive("remove"));
});

function toggleActive(method) {
    setTimeout(() => {
        document.querySelectorAll(catalogButtonSelector).forEach(button => button.classList[method](activeClass));
        catalogRootElement.classList[method](activeClass);
        catalogLayoutElement.classList[method](activeClass);

        if (catalogLayoutElement.classList.contains(activeClass)) {
            Methods.toggleBodyOverflow();
        } else {
            Methods.toggleBodyOverflow(false);
        }

    }, 0);
}