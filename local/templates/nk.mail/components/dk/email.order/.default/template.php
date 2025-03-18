<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use DK\NK\Helper\Main as MainHelper;

/** @var array $PARAMS */
/** @var array $arResult */
/** @var array $arParams */
/** @var EmailOrderComponent $compoent */
?>
<? MainHelper::include('header', [
    'image' => 'order_success.png',
    'title' => $arParams['TITLE']
], EMAIL_TEMPLATE_PATH); ?>

<table cellpadding="0" cellspacing="0" class="cb" align="center" role="none"
       style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%">
    <tr>
        <td align="center" style="padding:0;Margin:0">
            <table bgcolor="#ffffff" class="co" align="center" cellpadding="0" cellspacing="0" role="none"
                   style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FFFFFF;width:600px">

                <tr>
                    <td align="left" style="padding:20px;Margin:0">
                        <table cellpadding="0" cellspacing="0" width="100%" role="none"
                               style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                            <tr>
                                <td align="center" valign="top" style="padding:0;Margin:0;width:560px">
                                    <table cellpadding="0" cellspacing="0" width="100%" role="presentation"
                                           style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                        <tr>
                                            <td align="center" class="ck" style="padding:0;Margin:0">
                                                <h2
                                                        style="Margin:0;line-height:26.4px;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;font-size:22px;font-style:normal;font-weight:bold;color:#212121">
                                                    №&nbsp;<span
                                                            style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#283f8c;font-size:22px"><?= $arResult['NUMBER']; ?></span>
                                                </h2>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>

                <? if ($arParams['USER_DATA']) : ?>
                    <tr>
                        <td align="left"
                            style="padding:0;Margin:0;padding-bottom:20px;padding-left:20px;padding-right:20px">
                            <table cellpadding="0" cellspacing="0" width="100%" role="none"
                                   style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                <tr>
                                    <td align="center" valign="top" style="padding:0;Margin:0;width:560px">
                                        <table cellpadding="0" cellspacing="0" width="100%" role="presentation"
                                               style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                            <tr>
                                                <td align="left" style="padding:0;Margin:0">
                                                    <p
                                                            style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:28px;color:#333333;font-size:14px">
                                                        <u><strong>Данные покупателя:</strong></u>
                                                    </p>

                                                    <table border="0" align="left" cellspacing="0" cellpadding="0"
                                                           style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;width:100%"
                                                           class="es-table    cke_show_border" role="presentation">
                                                        <? foreach ($arResult['USER_DATA'] as $label => $value) : ?>
                                                            <tr>
                                                                <th style="font-size:14px;text-align:left;width:50%"
                                                                    scope="row">
                                                                    <p
                                                                            style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;font-size:14px">
                                                                        <strong><?= $label; ?></strong></p>
                                                                </th>
                                                                <td style="padding:0;Margin:0;font-size:14px;width:50%">
                                                                    <p
                                                                            style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;font-size:14px">
                                                                        <?= $value; ?></p>
                                                                </td>
                                                            </tr>
                                                        <? endforeach; ?>
                                                    </table>


                                                    <p
                                                            style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:28px;color:#333333;font-size:14px;margin-top:20px;">
                                                        <u><strong>Данные доставки:</strong></u>
                                                    </p>
                                                    <table border="0" align="left" cellspacing="0" cellpadding="0"
                                                           style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;width:100%"
                                                           class="es-table    cke_show_border" role="presentation">
                                                        <? foreach ($arResult['DELIVERY_DATA'] as $label => $value) : ?>
                                                            <tr>
                                                                <th style="font-size:14px;text-align:left;width:50%"
                                                                    scope="row">
                                                                    <p
                                                                            style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;font-size:14px">
                                                                        <strong><?= $label; ?></strong></p>
                                                                </th>
                                                                <td style="padding:0;Margin:0;font-size:14px;width:50%">
                                                                    <p
                                                                            style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;font-size:14px">
                                                                        <?= $value; ?></p>
                                                                </td>
                                                            </tr>
                                                        <? endforeach; ?>
                                                    </table>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                <? endif; ?>

                <? if (!empty($arResult['ITEMS'])) : ?>
                    <?
                    foreach ($arResult['ITEMS'] as $item) {
                        MainHelper::include("product", $item, EMAIL_TEMPLATE_PATH);
                    }
                    ?>
                    <tr>
                        <td align="left"
                            style="padding:0;Margin:0;padding-top:10px;padding-left:20px;padding-right:20px">
                            <table cellpadding="0" cellspacing="0" width="100%" role="none"
                                   style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                <tr>
                                    <td class="by" align="center" style="padding:0;Margin:0;width:560px">
                                        <table cellpadding="0" cellspacing="0" width="100%"
                                               style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;border-top:2px solid #efefef;border-bottom:2px solid #efefef"
                                               role="presentation">
                                            <tr>
                                                <td align="right" class="cj"
                                                    style="padding:0;Margin:0;padding-top:10px;padding-bottom:10px">
                                                    <p
                                                            style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#333333;font-size:14px">
                                                        Сумма заказа:&nbsp;<strong><?= $arResult['TOTAL_SUM'] ?></strong>
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                <? endif; ?>
            </table>
        </td>
    </tr>
</table>