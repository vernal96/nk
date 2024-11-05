<?php

namespace DK\NK\Helper;

use Bitrix\Highloadblock\HighloadBlockTable as HL;
use Bitrix\Iblock\ElementTable;
use Bitrix\Iblock\SectionTable;
use Bitrix\Main\Application;
use Bitrix\Main\Config\Option;
use Bitrix\Main\FileTable;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\ExpressionField;
use Bitrix\Main\Type\DateTime as BitrixDateTime;
use CFile;

class Main
{
    public static function getPhone(string $phone): string
    {
        $phone = preg_replace("/^8/", "+7", $phone);
        return preg_replace("/[^\d+]/", "", $phone);
    }

    public static function getPictureSrcSet($file, $sizesList): void
    {
        ksort($sizesList);
        foreach ($sizesList as $screenSize => $sizes) {
            $fileSrc = CFile::ResizeImageGet($file, ["width" => $sizes[0], "height" => $sizes[1]], BX_RESIZE_IMAGE_EXACT)["src"];
            echo "<source media=\"(max-width: {$screenSize}px)\" srcset=\"$fileSrc\">";
        }
    }

    public static function getDirFiles(string $path): array
    {
        $documentRoot = Application::getInstance()->getContext()->getServer()->getDocumentRoot();
        $path = (preg_match("/^\//", $path)) ? $path : "/$path";
        $path = (preg_match("/\/$/", $path)) ? $path : "$path/";
        $path = (str_contains($path, $documentRoot)) ?: $documentRoot . $path;
        $result = array_filter(scandir($path), fn($file) => !in_array($file, [".", ".."]));
        return array_values(array_map(fn($file) => $path . $file, $result));
    }

    public static function getHLObject($id): DataManager|string
    {
        Loader::includeModule("highloadblock");
        $hlBlock = HL::getById($id)->fetch();
        $entity = HL::compileEntity($hlBlock);
        return $entity->getDataClass();
    }

    public static function include(string $fileName, array $params = []): void
    {
        $PARAMS = $params;
        include $_SERVER["DOCUMENT_ROOT"] . SITE_TEMPLATE_PATH . "/include/$fileName.php";
    }

    public static function getFileIdBySrc(string $strFilename): ?int
    {
        $strUploadDir = '/' . Option::get("main", "upload_dir") . "/";
        $strFile = substr($strFilename, strlen($strUploadDir));
        $result = FileTable::query()
            ->registerRuntimeField("PATH", new ExpressionField("PATH", "CONCAT(%s, '/', %s)", ["SUBDIR", "FILE_NAME"]))
            ->where("PATH", $strFile)
            ->setSelect(["ID"])
            ->exec()
            ->fetch();
        return $result ? $result["ID"] : null;
    }

    public static function priceFormat($number, $showCurrency = false): string
    {
        $result = number_format($number, 2, ",", " ");
        return $showCurrency ? $result . " " . Loc::getMessage("CURRENCY") : $result;
    }

    public static function numberFormat($number): string
    {
        return number_format($number, 0, ",", " ");
    }

    public static function num2word($num, $words)
    {
        $num = $num % 100;
        if ($num > 19) {
            $num = $num % 10;
        }
        switch ($num) {
            case 1:
            {
                return ($words[0]);
            }
            case 2:
            case 3:
            case 4:
            {
                return ($words[1]);
            }
            default:
            {
                return ($words[2]);
            }
        }
    }

    public static function getUserType(): int
    {
        return Application::getInstance()->getSession()->get("USER_STATUS");
    }

    public static function setTimeLastUpdate(): void
    {
        Option::set(NK_MODULE_NAME, "LAST_UPDATE", new BitrixDateTime());
    }

    public static function getIblockPhotoSrc(bool $isElement, int|string $id, array $arSizes) {
        $id = is_int($id) ? $id : (int) preg_replace("/\D/", "", $id);
        $arItem = $isElement ? ElementTable::getRowById($id) : SectionTable::getRowById($id);
        $pictureId = $arItem[$isElement ? "PREVIEW_PICTURE" : "PICTURE"] ?: Main::getFileIdBySrc(Option::get(NK_MODULE_NAME, "NOPHOTO"));
        return CFile::ResizeImageGet($pictureId, ["width" => $arSizes[0], "height" => $arSizes[1]], BX_RESIZE_IMAGE_EXACT)["src"];
    }

}