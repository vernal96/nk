<?php

use Bitrix\Main\Config\Option;
use DK\NK\Helper\Main;

class DKLoginComponent extends CBitrixComponent
{

    public function executeComponent(): void
    {
        global $USER, $APPLICATION;
        if (!$USER->IsAdmin()) return;
        if ($USER->IsAuthorized()) {
            $this->setUserData();
            $this->arResult["AUTH"] = false;
        }
        $this->includeComponentTemplate();
    }

    private function setGuestData(): void {

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

}
