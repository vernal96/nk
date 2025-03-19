<?php

namespace DK\NK\Helper;

use Bitrix\Highloadblock\HighloadBlockTable as HL;
use Bitrix\Iblock\ORM\Query;
use Bitrix\Main\Application;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\ArgumentOutOfRangeException;
use Bitrix\Main\Config\Option;
use Bitrix\Main\FileTable;
use Bitrix\Main\Loader;
use Bitrix\Main\LoaderException;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ObjectPropertyException;
use Bitrix\Main\ORM\Data\DataManager;
use Bitrix\Main\ORM\Fields\ExpressionField;
use Bitrix\Main\SystemException;
use Bitrix\Main\Type\DateTime as BitrixDateTime;
use Bitrix\Main\UserGroupTable;
use CFile;
use DK\NK\Cart;
use Throwable;

class Main
{
    public static function getPhone(string $phone): string
    {
        $phone = preg_replace('/^8/', '+7', trim($phone));
        return preg_replace('/[^\d+]/', '', $phone);
    }

    public static function getPictureSrcSet(
        mixed $file,
        array     $sizesList,
        int       $resizeType = BX_RESIZE_IMAGE_EXACT
    ): void
    {
        if (!$file || empty($sizesList)) {
            return;
        }

        ksort($sizesList);

        foreach ($sizesList as $screenSize => $sizes) {
            if (!isset($sizes[0], $sizes[1])) continue;
            $fileData = CFile::ResizeImageGet($file, ["width" => $sizes[0], "height" => $sizes[1]], $resizeType);
            if (!isset($fileData["src"])) continue;
            $fileSrc = htmlspecialchars($fileData["src"], ENT_QUOTES, 'UTF-8');
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

    /**
     * @throws LoaderException
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public static function getHLObject(int $id): DataManager|string
    {
        Loader::includeModule("highloadblock");
        $hlBlock = HL::getById($id)->fetch();
        $entity = HL::compileEntity($hlBlock);
        return $entity->getDataClass();
    }

    public static function include(
        string $fileName,
        array $params = [],
        string $templatePath = SITE_TEMPLATE_PATH,
        bool $returnString = false
    ): ?string
    {
        if ($returnString) ob_start();
        $PARAMS = $params;
        include $_SERVER["DOCUMENT_ROOT"] . $templatePath . "/include/$fileName.php";
        if ($returnString) return ob_end_clean();
        return null;
    }

    public static function getFileIdBySrc(string $strFilename): ?int
    {
        try {
            $strFile = preg_replace(
                '#^(https?://[^/]+)?/' . Option::get("main", "upload_dir") . '/#',
                '',
                $strFilename
            );

            return FileTable::query()
                ->addSelect('ID')
                ->where('PATH', $strFile)
                ->registerRuntimeField(
                    'PATH',
                    new ExpressionField('PATH', "CONCAT(%s, '/', %s)", ['SUBDIR', 'FILE_NAME'])
                )
                ->fetchObject()
                ?->getId();
        } catch (Throwable $exception) {
            addUncaughtExceptionToLog($exception);
            return null;
        }
    }

    public static function priceFormat(int|float|null $number, bool $showCurrency = false): string
    {
        if ($number === null) $number = 0;
        $result = number_format($number, 2, ',', ' ');
        return $showCurrency ? $result . ' ' . Loc::getMessage("CURRENCY") : $result;
    }

    public static function numberFormat($number): string
    {
        return number_format($number, 0, ',', ' ');
    }

    public static function num2word(int $num, array $words)
    {
        $num = $num % 100;
        if ($num > 19) {
            $num = $num % 10;
        }
        return match ($num) {
            1 => ($words[0]),
            2, 3, 4 => ($words[1]),
            default => ($words[2]),
        };
    }

    public static function getUserType(): int
    {
        $session = Application::getInstance()->getSession();
        if (!$session->has('USER_STATUS')) Cart::initUserStatus();
        return $session->get('USER_STATUS');
    }

    /**
     * @throws ArgumentOutOfRangeException
     */
    public static function setTimeLastUpdate(): void
    {
        Option::set(NK_MODULE_NAME, "LAST_UPDATE", new BitrixDateTime());
    }

    /**
     * @throws ArgumentException
     */
    public static function setFormatPhone(string $phone): string
    {
        $result = '+d (ddd) ddd-dd-dd';
        $phone = preg_replace('/\D/', '', $phone);
        $phone = preg_replace('/^8/', '7', $phone);
        if (strlen($phone) < 11) {
            throw new ArgumentException(Loc::getMessage("ERROR_MOBILE_PHONE_TOO_SHORT"));
        }
        if (strlen($phone) > 11) {
            throw new ArgumentException(Loc::getMessage("ERROR_MOBILE_PHONE_TOO_LONG"));
        }
        for ($i = 0; $i < strlen($phone); $i++) {
            $result = preg_replace('/d/', $phone[$i], $result, 1);
        }
        return $result;
    }

    /**
     * @throws ArgumentException
     * @throws ObjectPropertyException
     * @throws SystemException
     */
    public static function checkUserGroup(int $userId, array|int|string $groups): bool {
        if (!is_array($groups)) $groups = [$groups];

        $groupQuery = Query::filter()
            ->logic('or')
            ->where('GROUP_ID', 1);

        foreach ($groups as $group) {
            if (is_int($group)) {
                $groupQuery->where('GROUP_ID', $group);
            } else {
                $groupQuery->where('GROUP.STRING_ID', $group);
            }
        }

        return (bool)UserGroupTable::query()
            ->where('USER_ID', $userId)
            ->where($groupQuery)
            ->fetchAll();
    }

    public static function getApplicationFormat(int $number): string {
        return str_pad($number, 6, '0', STR_PAD_LEFT);
    }
}