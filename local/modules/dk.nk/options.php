<?php

use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\HttpApplication;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;

/** @var CMain $APPLICATION */

$module_id = basename(__DIR__);

Loc::loadMessages($_SERVER["DOCUMENT_ROOT"] . BX_ROOT . "modules/main/options.php");
Loc::loadMessages(__FILE__);

Loader::includeModule($module_id);

$request = HttpApplication::getInstance()->getContext()->getRequest();

$aTabs = [
    [
        "DIV" => "edit_info",
        "TAB" => Loc::getMessage("DK_INFO_TAB"),
        "OPTIONS" => [
            [
                "PHONE", Loc::getMessage("DK_PHONE"), "", ["text", 50],
            ],
            [
                "YEAR_START", Loc::getMessage("DK_YEAR_START"), "", ["text", 50],
            ],
            Loc::getMessage("DK_SOCNET_HEAD"),
            [
                "TELEGRAM", Loc::getMessage("DK_TELEGRAM"), "", ["text", 50],
            ],
            [
                "WHATSAPP", Loc::getMessage("DK_WHATSAPP"), "", ["text", 50],
            ],
            [
                "VK", Loc::getMessage("DK_VK"), "", ["text", 50],
            ],
            [
                "VIBER", Loc::getMessage("DK_VIBER"), "", ["text", 50],
            ],
        ]
    ],
    [
        "DIV" => "edit_media",
        "TAB" => Loc::getMessage("DK_MEDIA_TAB"),
        "OPTIONS" => [
            [
                "NOPHOTO", Loc::getMessage("DK_NOPHOTO"), "", ["text", 50],
            ],
            [
                "NO_AVATAR", Loc::getMessage("DK_NO_AVATAR"), "", ["text", 50],
            ],
            [
                "NO_AVATAR_MALE", Loc::getMessage("DK_NO_AVATAR_MALE"), "", ["text", 50],
            ],
            [
                "NO_AVATAR_FEMALE", Loc::getMessage("DK_NO_AVATAR_FEMALE"), "", ["text", 50],
            ],
            [
                "MAX_FILE_SIZE", Loc::getMessage("DK_MAX_FILE_SIZE"), "", ["text", 50],
            ],
        ]
    ],
    [
        "DIV" => "edit_services",
        "TAB" => Loc::getMessage("DK_SERVICES_TAB"),
        "OPTIONS" => [
            Loc::getMessage("DK_BX24_HEAD"),
            [
                "BX24_DISABLED", Loc::getMessage("DK_BX24_DISABLED"), "", ["checkbox"],
            ],
            [
                "BX24_HOOK", Loc::getMessage("DK_BX24_HOOK"), "", ["text", 50],
            ],
            [
                "BX24_BOT_HOOK", Loc::getMessage("DK_BX24_BOT_HOOK"), "", ["text", 50],
            ],
            [
                "BX24_FEEDBACK_RESPONSIBLE", Loc::getMessage("DK_BX24_FEEDBACK_RESPONSIBLE"), "", ["text", 2],
            ],
            [
                "BX24_CREATOR", Loc::getMessage("DK_BX24_CREATOR"), "", ["text", 2],
            ],
            Loc::getMessage("DK_DADATA_HEAD"),
            [
                "DADATA_PUBLIC", Loc::getMessage("DK_DADATA_PUBLIC"), "", ["text", 50],
            ],
            [
                "DADATA_SECRET", Loc::getMessage("DK_DADATA_SECRET"), "", ["text", 50],
            ],
            Loc::getMessage("DK_GRC_HEAD"),
            [
                "GRC_PUBLIC", Loc::getMessage("DK_GRC_PUBLIC"), "", ["text", 50],
            ],
            [
                "GRC_SECRET", Loc::getMessage("DK_GRC_SECRET"), "", ["text", 50],
            ],
            Loc::getMessage("DK_YM_HEAD"),
            [
                "YM_KEY", Loc::getMessage("DK_YM_KEY"), "", ["text", 50],
            ],
            Loc::getMessage("KD_TINYMCE_HEAD"),
            [
                "TINYMCE_KEY", Loc::getMessage("DK_TINYMCE_KEY"), "", ["text", 50],
            ],
        ]
    ],
];

$arTaggedCache = ["socnet"];

if ($request->isPost() && $request["Update"] && check_bitrix_sessid()) {
    foreach ($aTabs as $aTab) {
        foreach ($aTab["OPTIONS"] as $arOption) {
            if (!is_array($arOption)) continue;
            if ($arOption["note"]) continue;
            $optionName = $arOption[0];
            $optionValue = $request->getPost($optionName);
            Option::set($module_id, $optionName, is_array($optionValue) ? implode(",", $optionValue) : $optionValue);
        }
    }

    foreach ($arTaggedCache as $tag) {
        Application::getInstance()->getTaggedCache()->clearByTag($tag);
    }

}

$tabControl = new CAdminTabControl("tabControl", $aTabs);

?>

<? $tabControl->Begin(); ?>

    <form method="POST"
          action="<? $APPLICATION->getCurPage() ?>?mid=<?= htmlspecialcharsbx($request["mid"]) ?>&amp;lang=<?= $request["lang"] ?>"
          name="bps_support_settings"
          enctype="multipart/form-data"
    >

        <? foreach ($aTabs as $aTab) : ?>
            <? $tabControl->BeginNextTab(); ?>
            <? if ($aTab["OPTIONS"]) : ?>
                <? __AdmSettingsDrawList($module_id, $aTab["OPTIONS"]); ?>
            <? endif; ?>
        <? endforeach; ?>

        <? $tabControl->Buttons(); ?>

        <input type="submit" name="Update" value="<?= GetMessage("MAIN_SAVE") ?>">
        <input type="submit" name="reset" value="<?= GetMessage("MAIN_RESET") ?>">

        <?= bitrix_sessid_post(); ?>

    </form>

<? $tabControl->End();
