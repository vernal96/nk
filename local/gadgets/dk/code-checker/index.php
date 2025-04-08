<?php

use Bitrix\Main\Application;
use Bitrix\Main\Data\Cache;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\SystemException;
use Bitrix\Main\UI\Extension;

$cache = Cache::createInstance();
$taggedCache = Application::getInstance()->getTaggedCache();
$cachePath = 'code-checker';

function getDuplicates(CIBlockResult $result, string $urlName): array
{
    $urls = [];
    while ($item = $result->GetNext()) {
        $urls[$item[$urlName]][] = [
            'ID' => $item['ID'],
            'NAME' => $item['NAME'],
        ];
    }
    return array_filter($urls, fn($items) => count($items) > 1);
}

if ($cache->initCache(CACHE_TIME, 'code-checker', $cachePath)) {
    $cache->output(); // Выводим HTML пользователю в браузер
} elseif ($cache->startDataCache()) {

    $taggedCache->startTagCache($cachePath);
    $taggedCache->registerTag('iblock_id_' . IBLOCK_CATALOG);

    Extension::load(['ui.alerts', 'ui.layout-form']);

    $iblock = CIBlock::GetByID(IBLOCK_CATALOG)->Fetch();

    $iblockCode = $iblock['CODE'];

    $sectionResult = CIBlockSection::GetList([], [
        'IBLOCK_ID' => IBLOCK_CATALOG,
        'GLOBAL_ACTIVE' => 'Y',
    ], false, ['ID', 'NAME', 'SECTION_PAGE_URL']);

    $elementResult = CIBlockElement::GetList([], [
        'IBLOCK_ID' => IBLOCK_CATALOG,
        'ACTIVE' => 'Y',
        'SECTION_GLOBAL_ACTIVE' => 'Y'
    ], false, false, ['ID', 'NAME', 'DETAIL_PAGE_URL']);

    $sectionDuplicates = getDuplicates($sectionResult, 'SECTION_PAGE_URL');
    $elementDuplicates = getDuplicates($elementResult, 'DETAIL_PAGE_URL');

    ?>
    <? if ($sectionDuplicates) : ?>
        <div class="ui-form-row">
            <strong>
                <?= Loc::getMessage('CODE_CHECKER_SECTION_TITLE'); ?>
            </strong>
        </div>
        <? foreach ($sectionDuplicates as $url => $items) : ?>
            <div class="ui-alert ui-alert-danger">
        <span class="ui-alert-message">
            <strong>
                <?= $url; ?>
            </strong>
            <? foreach ($items as $item) : ?>
                <br>
                <a href="/bitrix/admin/iblock_section_edit.php?IBLOCK_ID=<?= IBLOCK_CATALOG; ?>&type=<?= $iblockCode; ?>&ID=<?= $item['ID']; ?>"
                   target="_blank">[<?= $item['ID']; ?>] <?= $item['NAME']; ?></a>
            <? endforeach; ?>
        </span>
            </div>
        <? endforeach; ?>
    <? else : ?>
        <div class="ui-alert ui-alert-success">
        <span class="ui-alert-message">
            <strong>
                <?= Loc::getMessage('CODE_CHECKER_SECTION_OK'); ?>
            </strong>
        </span>
        </div>
    <? endif; ?>
    <br>
    <? if ($elementDuplicates) : ?>
        <div class="ui-form-row">
            <strong>
                <?= Loc::getMessage('CODE_CHECKER_ELEMENT_TITLE'); ?>
            </strong>
        </div>
        <? foreach ($elementDuplicates as $url => $items) : ?>
            <div class="ui-alert ui-alert-danger">
        <span class="ui-alert-message">
            <strong>
                <?= $url; ?>
            </strong>
            <? foreach ($items as $item) : ?>
                <br>
                <a href="/bitrix/admin/iblock_element_edit.php?IBLOCK_ID=<?= IBLOCK_CATALOG; ?>&type=<?= $iblockCode; ?>&ID=<?= $item['ID']; ?>"
                   target="_blank">[<?= $item['ID']; ?>] <?= $item['NAME']; ?></a>
            <? endforeach; ?>
        </span>
            </div>
        <? endforeach; ?>
    <? else : ?>
        <div class="ui-alert ui-alert-success">
        <span class="ui-alert-message">
            <strong>
                <?= Loc::getMessage('CODE_CHECKER_ELEMENT_OK'); ?>
            </strong>
        </span>
        </div>
    <? endif; ?>
    <?
    try {
        $taggedCache->endTagCache();
    } catch (SystemException $e) {
        addUncaughtExceptionToLog($e);
    }
    $cache->endDataCache();
}
?>