<? use Bitrix\Main\Localization\Loc;

/** @var array $arResult */

if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
CJSCore::Init();
\CJSCore::init("sidepanel");
?>

<? if ($arResult["FORM_TYPE"] == "login"): ?>
    <form name="system_auth_form<?= $arResult["RND"] ?>" method="post" target="_top"
          action="<?= $arResult["AUTH_URL"] ?>" onsubmit="auth(event)">
        <div class="modal__form">
            <? if ($arResult['SHOW_ERRORS'] === 'Y' && $arResult['ERROR'] && !empty($arResult['ERROR_MESSAGE'])) : ?>
                <div class="note note--error">
                    <?= $arResult['ERROR_MESSAGE']["MESSAGE"]; ?>
                </div>
            <? endif; ?>
            <? if ($arResult["BACKURL"] <> ''): ?>
                <input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
            <? endif ?>
            <? foreach ($arResult["POST"] as $key => $value): ?>
                <input type="hidden" name="<?= $key ?>" value="<?= $value ?>"/>
            <? endforeach ?>
            <input type="hidden" name="AUTH_FORM" value="Y"/>
            <input type="hidden" name="TYPE" value="AUTH"/>
            <label class="input">
                <span class="input__title"><?= Loc::getMessage("AUTH_LOGIN"); ?></span>
                <input type="text" name="USER_LOGIN" maxlength="50" value="<?= $_REQUEST["USER_LOGIN"]; ?>"
                       placeholder="<?= Loc::getMessage("AUTH_LOGIN_PLACEHOLDER"); ?>"/>
            </label>
            <script>
                BX.ready(function () {
                    var loginCookie = BX.getCookie("<?=CUtil::JSEscape($arResult["~LOGIN_COOKIE_NAME"])?>");
                    if (loginCookie) {
                        var form = document.forms["system_auth_form<?=$arResult["RND"]?>"];
                        var loginInput = form.elements["USER_LOGIN"];
                        loginInput.value = loginCookie;
                    }
                });
            </script>
            <label class="input">
                <span class="input__title"><?= Loc::getMessage("AUTH_PASSWORD"); ?></span>
                <input type="password" name="USER_PASSWORD" maxlength="255" size="17" autocomplete="off"
                       value="<?= $_REQUEST["USER_PASSWORD"]; ?>"
                       placeholder="<?= Loc::getMessage("AUTH_PASSWORD"); ?>"/>
            </label>
            <? if ($arResult["STORE_PASSWORD"] == "Y"): ?>
                <label class="checkbox">
                    <input type="checkbox" name="USER_REMEMBER" value="1">
                    <span class="checkbox__fake"></span>
                    <span class="checkbox__content"><?= Loc::getMessage("AUTH_REMEMBER_SHORT"); ?></span>
                </label>
            <? endif ?>
            <? if ($arResult["CAPTCHA_CODE"]): ?>
                <div class="form-fieldset form-fieldset--horizontal form-fieldset--wrap">
                    <input type="hidden" name="captcha_sid" value="<? echo $arResult["CAPTCHA_CODE"] ?>"/>
                    <img src="/bitrix/tools/captcha.php?captcha_sid=<? echo $arResult["CAPTCHA_CODE"] ?>"
                         alt="CAPTCHA" class="captcha form-fieldset-item"/>
                    <label class="input form-fieldset-item form-fieldset-item--100">
                        <span class="input__title"><?= Loc::getMessage("AUTH_CAPTCHA_LABEL"); ?></span>
                        <input type="text" name="captcha_word" maxlength="50" value=""
                               placeholder="<?= Loc::getMessage("AUTH_CAPTCHA_PROMT"); ?>"/>
                    </label>
                </div>
            <? endif ?>
        </div>
        <div class="modal__footer">
            <button type="submit"
                    class="button button--orange button--100"><?= Loc::getMessage("AUTH_LOGIN_BUTTON"); ?></button>
            <a href="<?= $arResult["AUTH_FORGOT_PASSWORD_URL"] ?>" class="simple-link"
               style="font-size: 0.8em;"><?= Loc::getMessage("AUTH_FORGOT_PASSWORD_2"); ?></a>
        </div>
        </table>
    </form>
    <script>
        // function auth(event) {
        //     event.preventDefault();
        //     const formData = new FormData(event.target);
        //     formData.set("FRAME", "Y");
        //     BX.ajax.post(
        //         event.target.action,
        //         Object.fromEntries(formData.entries()),
        //         response => event.target.innerHTML = response
        //     );
        // }
    </script>
    <div id="bx_auth_float_container"></div>
    <? if ($arResult["AUTH_SERVICES"]): ?>
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
    <? endif ?>


<?
elseif ($arResult["FORM_TYPE"] == "otp"):
    ?>

    <form name="system_auth_form<?= $arResult["RND"] ?>" method="post" target="_top"
          action="<?= $arResult["AUTH_URL"] ?>">
        <? if ($arResult["BACKURL"] <> ''): ?>
            <input type="hidden" name="backurl" value="<?= $arResult["BACKURL"] ?>"/>
        <? endif ?>
        <input type="hidden" name="AUTH_FORM" value="Y"/>
        <input type="hidden" name="TYPE" value="OTP"/>
        <table width="95%">
            <tr>
                <td colspan="2">
                    <? echo GetMessage("auth_form_comp_otp") ?><br/>
                    <input type="text" name="USER_OTP" maxlength="50" value="" size="17" autocomplete="off"/></td>
            </tr>
            <? if ($arResult["CAPTCHA_CODE"]): ?>
                <tr>
                    <td colspan="2">
                        <? echo GetMessage("AUTH_CAPTCHA_PROMT") ?>:<br/>
                        <input type="hidden" name="captcha_sid" value="<? echo $arResult["CAPTCHA_CODE"] ?>"/>
                        <img src="/bitrix/tools/captcha.php?captcha_sid=<? echo $arResult["CAPTCHA_CODE"] ?>"
                             width="180" height="40" alt="CAPTCHA"/><br/><br/>
                        <input type="text" name="captcha_word" maxlength="50" value=""/></td>
                </tr>
            <? endif ?>
            <? if ($arResult["REMEMBER_OTP"] == "Y"): ?>
                <tr>
                    <td valign="top"><input type="checkbox" id="OTP_REMEMBER_frm" name="OTP_REMEMBER" value="Y"/>
                    </td>
                    <td width="100%"><label for="OTP_REMEMBER_frm"
                                            title="<? echo GetMessage("auth_form_comp_otp_remember_title") ?>"><? echo GetMessage("auth_form_comp_otp_remember") ?></label>
                    </td>
                </tr>
            <? endif ?>
            <tr>
                <td colspan="2"><input type="submit" name="Login" value="<?= GetMessage("AUTH_LOGIN_BUTTON") ?>"/>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <noindex><a href="<?= $arResult["AUTH_LOGIN_URL"] ?>"
                                rel="nofollow"><? echo GetMessage("auth_form_comp_auth") ?></a></noindex>
                    <br/></td>
            </tr>
        </table>
    </form>
<? else : ?>
    success
<? endif ?>