<?php

use Bitrix\Main\Localization\Loc;

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $arResult */
$this->setFrameMode(true);
?>
<? $frame = $this->createFrame()->begin(); ?>
<? if (!$arResult["AUTH"]) : ?>
    <?php
    $arrClasses = ["login"];
    if (isset($arResult["USER"]["STATUS"]) && $arResult["USER"]["STATUS"] > 1) {
        $arrClasses[] = "login--with-status";
        if ($arResult["USER"]["STATUS"] == 2) $arrClasses[] = "login--with-status-silver";
        if ($arResult["USER"]["STATUS"] == 3) $arrClasses[] = "login--with-status-gold";
    }
    ?>
    <a href="/personal/" class="<?= implode(" ", $arrClasses); ?>">
        <img src="<?= $arResult["USER"]["IMAGE"]; ?>" alt="" class="login__image">
        <span class="login__content">
            <span class="login__title"><?= $arResult["USER"]["TITLE"]; ?></span>
            <span class="login__description"><?= Loc::getMessage("LOGIN_DESCRIPTION"); ?></span>
        </span>
    </a>
<? else : ?>
    <button class="login modal-trigger" data-src="#authorize">
        <span class="login__image login__image--auth"></span>
        <span class="login__title"><?= Loc::getMessage("LOGIN_START"); ?></span>
    </button>
    <div id="authorize" class="modal">
        <script>
            const Registration = {
                name: "Registration",
                template: "Регистрация"
            };
            const Authorize = {
                name: "Authorize",
                template: `
                    <div class="modal__form">
                        <label class="input">
                            <span class="input__title">Логин</span>
                            <input type="text" name="USER_LOGIN" maxlength="50" value=""
                                   placeholder="Введите номер телефона или почту"/>
                        </label>
<label class="input">
                            <span class="input__title">Логин</span>
                            <input type="text" name="USER_LOGIN" maxlength="50" value=""
                                   placeholder="Введите пароль"/>
                        </label>
<label class="checkbox">
                    <input type="checkbox" name="USER_REMEMBER" value="1">
                    <span class="checkbox__fake"></span>
                    <span class="checkbox__content">Запомнить меня</span>
                </label>
                    </div>
<div class="modal__footer">
                    <button type="submit" class="button button--orange button--100">Войти</button>
                </div>
                <?
                $APPLICATION->IncludeComponent("bitrix:socserv.auth.form", "flat",
                    [
                        "AUTH_SERVICES" => $arResult["AUTH_SERVICES"],
                        "AUTH_URL" => $arResult["AUTH_URL"],
                        "POST" => $arResult["POST"],
                        "POPUP" => "Y",
                        "SUFFIX" => "form",
                    ],
                    $component,
                    ["HIDE_ICONS" => "Y"]
                );
                ?>
                `
            };
            const Login = {
                name: "login",
                data: () => ({
                    swiper: null,
                    tabs: [
                        {
                            id: "auth",
                            title: "Авторизация",
                        },
                        {
                            id: "reg",
                            title: "Регистрация"
                        }
                    ],
                    activeTab: "auth"
                }),
                components: {
                    Authorize,
                    Registration
                },
                methods: {
                    toggleSlide(slideIndex, tabId) {
                        if (!this.swiper) return;
                        this.swiper.slideTo(slideIndex);
                        this.activeTab = tabId;
                    }
                },
                mounted() {
                    this.swiper = new Swiper(this.$refs.swiper, {
                        autoHeight: true,
                        slidesPerView: 1,
                        effect: 'fade',
                        fadeEffect: {
                            crossFade: true
                        },
                    });
                },
                template: `
                <div class="modal__header">
                    <div class="modal__title">Войти в личный кабинет</div>
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
                            <Authorize></Authorize>
                        </div>
                        <div class="swiper-slide">
                            <Registration></Registration>
                        </div>
                    </div>
                </div>
                `
            };
            BX.Vue3.createApp(Login).mount("#authorize");
        </script>
    </div>
<? endif; ?>
<? $frame->beginStub(); ?>
<div class="login">
    <span class="login__image loader-fill"></span>
    <span class="login__title content-loader content-loader--9"></span>
</div>
<? $frame->end(); ?>
