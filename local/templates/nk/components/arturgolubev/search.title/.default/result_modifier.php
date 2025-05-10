<?
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use \Bitrix\Main\Loader;
use \Bitrix\Main\Localization\Loc;

use \Arturgolubev\Smartsearch\Hl;

$PREVIEW_WIDTH = $arParams["PREVIEW_WIDTH_NEW"] = intval($arParams["PREVIEW_WIDTH_NEW"]);
if ($PREVIEW_WIDTH <= 0) $PREVIEW_WIDTH = 34;

$PREVIEW_HEIGHT = $arParams["PREVIEW_HEIGHT_NEW"] = intval($arParams["PREVIEW_HEIGHT_NEW"]);
if ($PREVIEW_HEIGHT <= 0) $PREVIEW_HEIGHT = 34;

$arParams["PRICE_VAT_INCLUDE"] = $arParams["PRICE_VAT_INCLUDE"] !== "N";

if(IntVal($arParams["PREVIEW_TRUNCATE_LEN"])<=0)
	$arParams["PREVIEW_TRUNCATE_LEN"] = '200';


if(!is_array($arParams["SHOW_PROPS"])){
	$arParams["SHOW_PROPS"] = [];
}

if(!empty($arParams["SHOW_PROPS"])){
	foreach($arParams["SHOW_PROPS"] as $k=>$prop){
		$arParams["SHOW_PROPS"][$k] = $prop = trim($prop);
		if(!$prop){
			unset($arParams["SHOW_PROPS"][$k]);
		}
	}
	$arParams["SHOW_PROPS"] = array_values($arParams["SHOW_PROPS"]);
}

$arResult["ELEMENTS"] = [];
$arResult["SEARCH"] = [];
foreach($arResult["CATEGORIES"] as $category_id => $arCategory)
{
	foreach($arCategory["ITEMS"] as $i => $arItem)
	{
		if(isset($arItem["ITEM_ID"]))
		{
			$arResult["SEARCH"][] = &$arResult["CATEGORIES"][$category_id]["ITEMS"][$i];
			if($arItem["MODULE_ID"] == "iblock" && substr($arItem["ITEM_ID"], 0, 1) !== "S")
			{				
				$arResult["ELEMENTS"][$arItem["ITEM_ID"]] = $arItem["ITEM_ID"];
			}
			elseif($arItem["MODULE_ID"] == "iblock" && substr($arItem["ITEM_ID"], 0, 1) == "S")
			{
				$arResult["SECTIONS"][$arItem["ITEM_ID"]] = str_replace('S', '', $arItem["ITEM_ID"]);
			}
		}
	}
}

if (!empty($arResult["ELEMENTS"]) && Loader::includeModule("iblock"))
{

	$obParser = new CTextParser;

	if (is_array($arParams["PRICE_CODE"]))
		$arResult["PRICES"] = CIBlockPriceTools::GetCatalogPrices(0, $arParams["PRICE_CODE"]);
	else
		$arResult["PRICES"] = [];

	$arConvertParams = [];
	if ('Y' == $arParams['CONVERT_CURRENCY']){
		if (!Loader::includeModule('currency')){
			$arParams['CONVERT_CURRENCY'] = 'N';
			$arParams['CURRENCY_ID'] = '';
		}else{
			$arCurrencyInfo = CCurrency::GetByID($arParams['CURRENCY_ID']);
			if (!(is_array($arCurrencyInfo) && !empty($arCurrencyInfo))){
				$arParams['CONVERT_CURRENCY'] = 'N';
				$arParams['CURRENCY_ID'] = '';
			}else{
				$arParams['CURRENCY_ID'] = $arCurrencyInfo['CURRENCY'];
				$arConvertParams['CURRENCY_ID'] = $arCurrencyInfo['CURRENCY'];
			}
		}
	}

	$arSelect = [
		"ID",
		"IBLOCK_ID",
		"PREVIEW_TEXT",
		"PREVIEW_PICTURE",
		"DETAIL_PICTURE",
	];
	$arFilter = [
		"IBLOCK_LID" => SITE_ID,
		"IBLOCK_ACTIVE" => "Y",
		"ACTIVE_DATE" => "Y",
		"ACTIVE" => "Y",
		"CHECK_PERMISSIONS" => "Y",
		"MIN_PERMISSION" => "R",
	];
	foreach($arResult["PRICES"] as $value)
	{
		$arSelect[] = $value["SELECT"];
		$arFilter["CATALOG_SHOP_QUANTITY_".$value["ID"]] = 1;
	}
	$arFilter["=ID"] = $arResult["ELEMENTS"];
	$arResult["ELEMENTS"] = [];
	$rsElements = CIBlockElement::GetList([], $arFilter, false, false, $arSelect);
	while($obElement = $rsElements->GetNextElement())
	{
		$arElement = $obElement->GetFields();
		
		$arElement["PRICES"] = CIBlockPriceTools::GetItemPrices($arElement["IBLOCK_ID"], $arResult["PRICES"], $arElement, $arParams['PRICE_VAT_INCLUDE'], $arConvertParams);
		if($arParams["PREVIEW_TRUNCATE_LEN"] > 0)
			$arElement["PREVIEW_TEXT"] = $obParser->html_cut($arElement["PREVIEW_TEXT"], $arParams["PREVIEW_TRUNCATE_LEN"]);

		if(!$arElement["PREVIEW_PICTURE"] && !$arElement["DETAIL_PICTURE"] && Loader::includeModule("catalog")){	
			$mxResult = CCatalogSku::GetProductInfo($arElement["ID"]);
			if (is_array($mxResult) && $mxResult['ID'])
			{
				$rsMainElements = CIBlockElement::GetList([], ["ID"=>$mxResult['ID']], false, false, ["ID", "PREVIEW_PICTURE", "DETAIL_PICTURE"]);
				while($arMainElement = $rsMainElements->Fetch())
				{
					$arElement["PREVIEW_PICTURE"] = $arMainElement["PREVIEW_PICTURE"];
					$arElement["DETAIL_PICTURE"] = $arMainElement["DETAIL_PICTURE"];
				}
			}
		}
		
		if(!empty($arParams["SHOW_PROPS"])){
			$arElement["PROPS"] = $PROPS = [];
			$tmp = $obElement->GetProperties(["id" => 'asc'], ["ID" => $arParams["SHOW_PROPS"]]);
			foreach($tmp as $arProp){
				$PROPS[$arProp["ID"]] = $arProp;
			}

			foreach($arParams["SHOW_PROPS"] as $getProp){
				$tmp = $obElement->GetProperties(["id" => 'asc'], ["CODE" => $getProp]);
				foreach($tmp as $arProp){
					$PROPS[$arProp["CODE"]] = $arProp;
				}
			}

			foreach($arParams["SHOW_PROPS"] as $prop){
				$arProperty = $PROPS[$prop];
			
				if($arProperty){
					if($arProperty['USER_TYPE'] == 'directory' && !empty($arProperty['VALUE'])){
						$arProperty['VALUE'] = Hl::getPropValueField($arProperty, $arProperty['VALUE']);
					}elseif($arProperty["PROPERTY_TYPE"] == 'E'){
						$arSubFilter = [];
						if(!is_array($arProperty["VALUE"])){
							$arProperty["VALUE"] = [$arProperty["VALUE"]];
						}
						
						foreach($arProperty["VALUE"] as $id){
							$id = intval($id);
							if($id){
								$arSubFilter[] = $id;
							}
						}
						
						if(count($arSubFilter)){
							$newValue = [];
							$res = CIBlockElement::GetList(['ID' => $arSubFilter], ["ID" => $arSubFilter], false, false, ["ID", "NAME"]);
							while($arFields = $res->Fetch()){
								$newValue[] = $arFields["NAME"];
							}
						
							$arProperty["VALUE"] = $newValue;
						}
					}
					
					
					$arElement["PROPS"][] = $arProperty;
				}
			}
		}
		
		$arResult["ELEMENTS"][$arElement["ID"]] = $arElement;
	}
}


if (!empty($arResult["SECTIONS"]) && Loader::includeModule("iblock"))
{
	$obParser = new CTextParser;

	$arFilter = [
		"IBLOCK_LID" => SITE_ID,
		"IBLOCK_ACTIVE" => "Y",
		"ACTIVE_DATE" => "Y",
		"ACTIVE" => "Y",
		"CHECK_PERMISSIONS" => "Y",
		"MIN_PERMISSION" => "R",
	];
	
	$sectionIDs = array_values($arResult["SECTIONS"]);
	
	$arFilter["=ID"] = $sectionIDs;

	$arSelect = [
		"ID",
		"IBLOCK_ID",
		"PICTURE",
		"DESCRIPTION",
	];

	$db_list = CIBlockSection::GetList([$by=>$order], $arFilter, false, $arSelect);
	while($ar_result = $db_list->GetNext())
	{
		if($arParams["PREVIEW_TRUNCATE_LEN"] > 0)
			$ar_result["DESCRIPTION"] = $obParser->html_cut($ar_result["DESCRIPTION"], $arParams["PREVIEW_TRUNCATE_LEN"]);
		
		if ($ar_result["PICTURE"] > 0)
			$ar_result["PICTURE"] = CFile::ResizeImageGet($ar_result["PICTURE"], ["width"=>$PREVIEW_WIDTH, "height"=>$PREVIEW_HEIGHT], BX_RESIZE_IMAGE_PROPORTIONAL, true);
		
		$arResult["SECTIONS"]["S".$ar_result["ID"]] = $ar_result;
	}
	
	foreach($sectionIDs as $id)
	{
		$nav = CIBlockSection::GetNavChain(false, $id, ["ID", "NAME"]);
		while($arSectionPath = $nav->GetNext()){
			if($arSectionPath["ID"] == $id) continue;
			$arResult["SECTIONS"]["S".$id]["PATH"] .= $arSectionPath["NAME"].' > ';
		} 
	}
}

foreach($arResult["SEARCH"] as $i=>$arItem)
{
	switch($arItem["MODULE_ID"])
	{
		case "iblock":
			if(array_key_exists($arItem["ITEM_ID"], $arResult["ELEMENTS"]))
			{
				$arElement = &$arResult["ELEMENTS"][$arItem["ITEM_ID"]];

				if ($arParams["SHOW_PREVIEW"] != "N")
				{
					if ($arElement["PREVIEW_PICTURE"] > 0)
						$arElement["PICTURE"] = CFile::ResizeImageGet($arElement["PREVIEW_PICTURE"], ["width"=>$PREVIEW_WIDTH, "height"=>$PREVIEW_HEIGHT], BX_RESIZE_IMAGE_PROPORTIONAL, true);
					elseif ($arElement["DETAIL_PICTURE"] > 0)
						$arElement["PICTURE"] = CFile::ResizeImageGet($arElement["DETAIL_PICTURE"], ["width"=>$PREVIEW_WIDTH, "height"=>$PREVIEW_HEIGHT], BX_RESIZE_IMAGE_PROPORTIONAL, true);
					/*
					else{
						// get picture from property file
						$propertyPictureCode = 'MORE_PHOTO';
						$res = CIBlockElement::GetList([], ["IBLOCK_ID"=>$arElement["IBLOCK_ID"], "ID"=>$arElement["ID"]], false, ["nPageSize"=>1], ["ID", "PROPERTY_".$propertyPictureCode]);
						while($arFields = $res->Fetch()){
							if($arFields['PROPERTY_'.$propertyPictureCode.'_VALUE']){
								$arElement["PICTURE"] = CFile::ResizeImageGet($arFields['PROPERTY_'.$propertyPictureCode.'_VALUE'], ["width"=>$PREVIEW_WIDTH, "height"=>$PREVIEW_HEIGHT], BX_RESIZE_IMAGE_PROPORTIONAL, true);
							}
						}
					}
					*/
				}
			}
			break;
	}

	$arResult["SEARCH"][$i]["ICON"] = true;
}

if(count($arResult["ELEMENTS"]) && CModule::IncludeModule("catalog")){
	$arResult['MEASURES'] = [];
	$res_measure = CCatalogMeasure::getList();
	while($measure = $res_measure->Fetch()) {
		$arResult['MEASURES'][$measure['ID']] = $measure;
	}      
}      

$arResult["SEARCH_HISTORY"] = [];
if($arParams["SHOW_HISTORY"] == 'Y'){
	$arResult["SEARCH_HISTORY"] = \Arturgolubev\Smartsearch\Tools::getSearchHistory(10);
}

// echo '<pre>'; print_r($arResult["SEARCH_HISTORY"]); echo '</pre>';