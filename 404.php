<?

global $APPLICATION, $IS_CATALOG_PAGE;

use Bitrix\Main\Localization\Loc;

include_once($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/main/include/urlrewrite.php');

CHTTP::SetStatus("404 Not Found");

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/header.php");

if (!defined('ERROR_404')) {
    $APPLICATION->SetTitle(Loc::getMessage("PAGE_NOT_FOUND_TITLE"));
    $APPLICATION->SetPageProperty("TITLE", Loc::getMessage("PAGE_NOT_FOUND_TITLE"));
    $APPLICATION->SetPageProperty("h1", Loc::getMessage("PAGE_NOT_FOUND_TITLE"));
    $APPLICATION->AddChainItem("Страница не найдена", "", false);
}

@define("ERROR_404", "Y");

?>
<section class="section section--pt0">
    <div class="catalog-wrapper">
        <? $section = $APPLICATION->IncludeComponent(
            "dk:catalog.categories",
            ".default",
            [
                "CACHE_TYPE" => "A",
                "CACHE_TIME" => "36000000",
                "COMPOSITE_FRAME_MODE" => "A",
                "COMPOSITE_FRAME_TYPE" => "AUTO",
                "SECTION_CODE" => false,
                "IBLOCK_TYPE" => "catalog",
                "IBLOCK_ID" => "2",
                "DEPTH_LEVEL" => 1,
                "FULL" => false,
                "COMPONENT_TEMPLATE" => ".default",
            ],
            [],
        ); ?>
        <div class="catalog-products catalog-main">
            <div class="content-blocks">
                <div class="text-content white-block">
                    <p>
                        Ошибка <strong>404</strong> или <strong>Not Found</strong> («не найдено») — стандартный код
                        ответа HTTP о том, что клиент был в состоянии общаться с сервером, но сервер не может найти
                        данные согласно запросу. Ошибку 404 не следует путать с ошибкой «Сервер не найден» или иными
                        ошибками, указывающими на ограничение доступа к серверу. Ошибка 404 означает, что запрашиваемый
                        ресурс может быть доступен в будущем, что однако не гарантирует наличие прежнего содержания.
                    </p>
                </div>
                <div>
                    <h2 class="title title--h3 title--min-bottom">Карта сайта:</h2>
                    <div class="text-content white-block">
                        <? $APPLICATION->IncludeComponent("bitrix:main.map", ".default", [
                                "LEVEL" => "3",
                                "COL_NUM" => "1",
                                "SHOW_DESCRIPTION" => "Y",
                                "SET_TITLE" => "Y",
                                "CACHE_TIME" => "36000000"
                            ]
                        ); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<? require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/footer.php"); ?>
