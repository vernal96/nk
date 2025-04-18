import {Methods} from "dk.main.methods";
import "dk.main.methods";
import * as AjaxForm from "./ajax_form";
import {Fancybox} from "@fancyapps/ui";
import {runModalCart} from "dk.cart";
import Metric from './metric_events';

BX.ready(() => {

    BX.onCustomEvent(window, 'onPageReady');

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
    Methods.on('click', '.main-phone', () => BX.onCustomEvent(window, 'onMainPhoneClick'));
    Methods.on('click', '[data-src="#recall"]', () => BX.onCustomEvent(window, 'onRecallOpen'));

});

Metric.ym.init();

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