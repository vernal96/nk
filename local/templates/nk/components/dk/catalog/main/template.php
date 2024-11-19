<?php
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Localization\Loc;

?>
<section class="section main-catalog container">
    <h2 class="title title--h2" data-page-loader-start><?= Loc::getMessage("DK_NK_CATALOG_TITLE"); ?></h2>
    <div class="catalog-wrapper" id="catalogMainWrapper">
        <div class="catalog-mobile-categories">
            <div class="catalog-category">
                <div class="catalog-category__image content-loader content-loader--ah"></div>
                <div class="catalog-category__label content-loader"></div>
            </div>
        </div>
        <div class="catalog-categories-outer">
            <ul class="catalog-categories scroll">
                <li>
                    <div class="catalog-category">
                        <div class="catalog-category__image content-loader content-loader--ah content-loader--white"></div>
                        <span class="catalog-category__label content-loader content-loader--white"></span>
                    </div>
                    <ul class="catalog-categories__children">
                        <li>
                            <div class="catalog-category">
                                <div class="catalog-category__image content-loader content-loader--ah content-loader--white"></div>
                                <span class="catalog-category__label content-loader content-loader--white"></span>
                            </div>
                        </li>
                        <li>
                            <div class="catalog-category">
                                <div class="catalog-category__image content-loader content-loader--ah content-loader--white"></div>
                                <span class="catalog-category__label content-loader content-loader--white"></span>
                            </div>
                        </li>
                    </ul>
                </li>
                <? for ($i = 0; $i < 12; $i++) : ?>
                    <li>
                        <div class="catalog-category">
                            <div class="catalog-category__image content-loader content-loader--ah content-loader--white"></div>
                            <span class="catalog-category__label content-loader content-loader--white"></span>
                        </div>
                    </li>
                <? endfor; ?>
            </ul>
        </div>
        <div class="catalog-products catalog-main">
            <div class="catalog-products__main">
                <? for ($i = 0; $i < 6; $i++) : ?>
                    <div class="product">
                        <div class="product__header">
                            <div class="product__image content-loader content-loader--white"></div>
                            <div class="product__title">
                                <div class="content-loader"></div>
                                <div class="content-loader"></div>
                            </div>
                        </div>
                        <span class="product__price content-loader content-loader--30 content-loader--14h"></span>
                        <div class="content-loader product__button content-loader--input-h"></div>
                    </div>
                <? endfor; ?>
            </div>
        </div>
    </div>
</section>