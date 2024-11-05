import {Methods} from "dk.main.methods";
import "dk.main.methods";
import * as AjaxForm from "./ajax_form";
import {Fancybox} from "@fancyapps/ui";
import {runModalCart} from "dk.cart";

BX.ready(() => {
    Methods.setPhonesMask();
    Methods.initViberLink();
    Methods.checkAgree();
    Methods.bxPreloader();
    AjaxForm.init();

    Swiper.defaults.speed = 1000;
    Swiper.defaults.allowTouchMove = false;
    Swiper.defaults.rewind = true;

    Fancybox.bind(".modal-trigger", {
        dragToClose: false,
        Hash: false,
        autoFocus: false
    });

    Methods.on("click", ".fastpay", runModalCart);

});

if (window.frameCacheVars !== undefined) {
    BX.addCustomEvent("onFrameDataReceived", function (json) {
        document.addEventListener('scroll', () => {
            Methods.fixedHeader();
        });
    });
} else {
    BX.ready(function () {
        document.addEventListener('scroll', () => {
            Methods.fixedHeader();
        });
    });
}