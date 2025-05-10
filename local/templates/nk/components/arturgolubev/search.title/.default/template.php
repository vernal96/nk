<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
$this->setFrameMode(true);

/* hints */
$arResult["HINTS"] = [];
if (is_array($arParams["ANIMATE_HINTS"])) {
    foreach ($arParams["ANIMATE_HINTS"] as $k => $v) {
        $v = trim($v);
        if ($v) {
            $arResult["HINTS"][] = $v;
        }
    }
}

if (count($arResult["HINTS"])) {
    CJSCore::Init(["ag_smartsearch_type"]);
    $arParams["INPUT_PLACEHOLDER"] = '';
    $arParams["ANIMATE_HINTS_SPEED"] = (intval($arParams["ANIMATE_HINTS_SPEED"]) ? intval($arParams["ANIMATE_HINTS_SPEED"]) : 1);
}
/* end hints */

$INPUT_ID = trim($arParams["~INPUT_ID"]);
if (strlen($INPUT_ID) <= 0)
    $INPUT_ID = "smart-title-search-input";
$INPUT_ID = CUtil::JSEscape($INPUT_ID);

$CONTAINER_ID = trim($arParams["~CONTAINER_ID"]);
if (strlen($CONTAINER_ID) <= 0)
    $CONTAINER_ID = "smart-title-search";

$CONTAINER_ID = CUtil::JSEscape($CONTAINER_ID);

$PRELOADER_ID = $CONTAINER_ID . "_preloader_item";
$CLEAR_ID = $CONTAINER_ID . "_clear_item";
$VOICE_ID = $CONTAINER_ID . "_voice_item";

if ($arParams["SHOW_INPUT"] !== "N"):?>
    <form action="<? echo $arResult["FORM_ACTION"] ?>" id="<? echo $CONTAINER_ID ?>" class="search">
        <label class="search__input input input--no-border">
            <input
                    type="text"
                    placeholder="<?= $arParams["INPUT_PLACEHOLDER"] ?>"
                    autocomplete="off"
                    name="q"
                    id="<?= $INPUT_ID; ?>"
                    value="<?= htmlspecialcharsbx($_REQUEST["q"]) ?>"
            >
        </label>
        <div class="search__buttons">
            <div class="search__loader" id="<?= $PRELOADER_ID ?>" style="display: none;">
                <span class="search__loader button button--link button--pl0 button--pr0">
                    <span class="loader loader--gray"></span>
                </span>
            </div>
            <div class="search__clean" id="<? echo $CLEAR_ID ?>" style="display: none;">
                <span class="button button--link">
                    <i class="icon icon--clean"></i>
                </span>
            </div>
            <? if ($arParams['VOICE_INPUT'] == 'Y'): ?>
                <div class="search__voice-input" id="<? echo $VOICE_ID ?>">
                    <span class="button button--link">
                        <i class="icon icon--voice-input"></i>
                    </span>
                </div>
            <? endif; ?>
            <button name="s" type="submit" class="button button--pl-minimal button--link">
                <i class="icon icon--search"></i>
            </button>
        </div>
    </form>
<? endif ?>

<script>
    BX.ready(function () {
        new JCTitleSearchAG({
            // 'AJAX_PAGE' : '/your-path/fast_search.php',
            'AJAX_PAGE': '<?echo CUtil::JSEscape(POST_FORM_ACTION_URI)?>',
            'CONTAINER_ID': '<?echo $CONTAINER_ID?>',
            'INPUT_ID': '<?echo $INPUT_ID?>',
            'PRELODER_ID': '<?echo $PRELOADER_ID?>',
            'CLEAR_ID': '<?echo $CLEAR_ID?>',
            'VOICE_ID': '<?=($arParams['VOICE_INPUT'] == 'Y') ? $VOICE_ID : ''?>',
            'POPUP_HISTORY': '<?=($arParams['SHOW_HISTORY'] == 'Y') ? $arParams['SHOW_HISTORY_POPUP'] : 'N'?>',
            'POPUP_HISTORY_TITLE': '<?=GetMessage("CT_BST_SEARCH_HISTORY")?>',
            'PAGE': '<?=$arParams["PAGE"]?>',
            'MIN_QUERY_LEN': 2
        });

        <?if(count($arResult["HINTS"])):?>
        new Typed('#<?echo $INPUT_ID?>', {
            strings: <?=CUtil::PhpToJSObject($arResult["HINTS"]);?>,
            typeSpeed: <?=$arParams["ANIMATE_HINTS_SPEED"] * 20?>,
            backSpeed: <?=$arParams["ANIMATE_HINTS_SPEED"] * 10?>,
            backDelay: 500,
            startDelay: 1000,
            // smartBackspace: true,
            bindInputFocusEvents: true,
            attr: 'placeholder',
            loop: true
        });
        <?endif;?>
    });
</script>