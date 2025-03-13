<?php

use Bitrix\Main\Config\Option;
use Bitrix\Main\Engine\ActionFilter\Csrf;
use Bitrix\Main\Engine\Contract\Controllerable;
use DK\NK\Helper\Main;

class DKLoginComponent extends CBitrixComponent implements Controllerable
{

    public function executeComponent(): void
    {
        global $USER, $APPLICATION;
        if ($USER->IsAuthorized()) {
            $this->setUserData();
            $this->arResult["AUTH"] = $USER->IsAuthorized();
        } else {
            if ($this->request->get("FRAME") == "Y") {
                $APPLICATION->RestartBuffer();
            }
        }
        if ($APPLICATION->GetCurPage() == "/test.php") {
            $this->includeComponentTemplate();
        }
    }

    private function setUserData(): void
    {
        global $USER;
        $user = CUSer::GetByID($USER->GetId())->fetch();
        $title = $user["NAME"] ? CUser::FormatName(CSite::GetNameFormat(), $user) : $user["EMAIL"];
        if ($user["PERSONAL_PHOTO"]) {
            $avatar = $user["PERSONAL_PHOTO"];
        } else {
            if ($user["PERSONAL_GENDER"]) {
                $avatar = Main::getFileIdBySrc(
                    Option::get(
                        NK_MODULE_NAME,
                        $user["PERSONAL_GENDER"] == "M" ? "NO_AVATAR_MALE" : "NO_AVATAR_FEMALE"
                    )
                );
            } else {
                $avatar = Main::getFileIdBySrc(Option::get(NK_MODULE_NAME, "NO_AVATAR"));
            }
        }
        $avatar = CFile::ResizeImageGet($avatar, ["width" => 50, "height" => 50], BX_RESIZE_IMAGE_EXACT)["src"];
        $this->arResult["USER"] = [
            "TITLE" => $title,
            "IMAGE" => $avatar,
            "STATUS" => $user["UF_STATUS"]
        ];
    }

    public function authAction(): array
    {
        global $APPLICATION;
        $arResult = $APPLICATION->IncludeComponent("bitrix:system.auth.form", "", [
            "SHOW_ERRORS" => "Y",
        ], $this, [], true);
        $post = [];
        $get = [];
        foreach ($arResult["POST"] as $key => $value) {
            $post[] = ["key" => $key, "value" => $value];
        }
        foreach ($arResult["GET"] as $key => $value) {
            $get[] = ["key" => $key, "value" => $value];
        }
        return [
            "formType" => $arResult["FORM_TYPE"],
            "errorMessage" => $arResult["ERROR_MESSAGE"]["MESSAGE"],
            "storePassword" => $arResult["STORE_PASSWORD"] == "Y",
            "post" => $post,
            "get" => $get,
            "rememberOtp" => $arResult["REMEMBER_OTP"] == "Y",
            "captchaCode" => $arResult["CAPTCHA_CODE"],
        ];
    }

    public function forgotpasswdAction() {
        global $APPLICATION;
        $arResult = $APPLICATION->IncludeComponent("bitrix:main.auth.forgotpasswd", "", [], $this, [], true);
        return [
            "phoneReg" => (bool)$arResult["PHONE_REGISTRATION"],
            "captchaCode" => $arResult["CAPTCHA_CODE"],
            "errors" => implode("<br>", $arResult["ERRORS"]),
            "success" => $arResult["SUCCESS"],
            "fields" => $arResult["FIELDS"],
            "original" => $arResult
        ];
    }

    public function registrationAction(): mixed
    {
        global $APPLICATION;
        $arResult = $APPLICATION->IncludeComponent("bitrix:main.auth.registration", "", [], $this, [], true);
        return $arResult;
    }

    public function configureActions(): array
    {
        return [
            "auth" => [
                "prefilters" => [new Csrf()]
            ],
            "forgotpasswd" => [
                "prefilters" => [new Csrf()]
            ],
            "registration" => [
                "prefilters" => [new Csrf()]
            ]
        ];
    }
}
