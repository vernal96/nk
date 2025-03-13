<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();

use Bitrix\Main\Config\Option;
use Bitrix\Main\Type\DateTime as BitrixDateTime;

?>
<tr>
    <td align="left"
        style="Margin:0;padding-bottom:10px;padding-top:15px;padding-left:20px;padding-right:20px">
        <table cellpadding="0" cellspacing="0" width="100%" role="none"
               style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
            <tr>
                <td align="left" style="padding:0;Margin:0;width:560px">
                    <table cellpadding="0" cellspacing="0" width="100%" role="presentation"
                           style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                        <tr>
                            <td align="center"
                                style="padding:0;Margin:0;padding-top:10px;padding-bottom:10px">
                                <p
                                        style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:21px;color:#212121;font-size:14px">
                                    У вас остались вопросы? Свяжитесь с нами по номеру телефона&nbsp;<br><a
                                            href="tel:<?= \DK\NK\Helper\Main::getPhone(Option::get(NK_MODULE_NAME, "PHONE")); ?>"
                                            style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:underline;color:#5C68E2;font-size:14px"><?= Option::get(NK_MODULE_NAME, "PHONE"); ?></a>
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </td>
</tr>
</table>
</td>
</tr>
</table>
<table cellpadding="0" cellspacing="0" class="es-footer" align="center" role="none"
       style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%;background-color:transparent;background-repeat:repeat;background-position:center top">
    <tr>
        <td align="center" style="padding:0;Margin:0">
            <table class="es-footer-body" align="center" cellpadding="0" cellspacing="0"
                   style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:transparent;width:640px"
                   role="none">
                <tr>
                    <td align="left"
                        style="Margin:0;padding-top:20px;padding-bottom:20px;padding-left:20px;padding-right:20px">
                        <table cellpadding="0" cellspacing="0" width="100%" role="none"
                               style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                            <tr>
                                <td align="left" style="padding:0;Margin:0;width:600px">
                                    <table cellpadding="0" cellspacing="0" width="100%"
                                           role="presentation"
                                           style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                        <tr>
                                            <td align="center"
                                                style="padding:0;Margin:0;padding-bottom:35px"><p
                                                        style="Margin:0;-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;line-height:18px;color:#333333;font-size:12px">
                                                    ©️&nbsp;<?= Option::get(NK_MODULE_NAME, "YEAR_START"); ?>
                                                    - <?= (new BitrixDateTime())->format("Y"); ?> <?= SITE_NAME; ?></p>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td style="padding:0;Margin:0">
                                                <table cellpadding="0" cellspacing="0" width="100%"
                                                       class="es-menu" role="presentation"
                                                       style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                                    <tr class="links">
                                                        <td align="center" valign="top" width="33.33%"
                                                            style="Margin:0;padding-left:5px;padding-right:5px;padding-top:5px;padding-bottom:5px;border:0"
                                                            bgcolor="transparent"><a target="_blank"
                                                                                     href="https://<?= SITE_SERVER_NAME; ?>/"
                                                                                     style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:none;display:block;font-family:arial, 'helvetica neue', helvetica, sans-serif;color:#999999;font-size:12px">Наш
                                                                сайт</a></td>
                                                        <td align="center" valign="top" width="33.33%"
                                                            style="Margin:0;padding-left:5px;padding-right:5px;padding-top:5px;padding-bottom:5px;border:0;border-left:1px solid #cccccc">
                                                            <a target="_blank"
                                                               href="https://<?= SITE_SERVER_NAME; ?>/catalog/"
                                                               style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:none;display:block;font-family:arial, 'helvetica neue', helvetica, sans-serif;color:#999999;font-size:12px">Наш
                                                                каталог</a></td>
                                                        <td align="center" valign="top" width="33.33%"
                                                            style="Margin:0;padding-left:5px;padding-right:5px;padding-top:5px;padding-bottom:5px;border:0;border-left:1px solid #cccccc">
                                                            <a target="_blank"
                                                               href="https://<?= SITE_SERVER_NAME; ?>/about/"
                                                               style="-webkit-text-size-adjust:none;-ms-text-size-adjust:none;mso-line-height-rule:exactly;text-decoration:none;display:block;font-family:arial, 'helvetica neue', helvetica, sans-serif;color:#999999;font-size:12px">О
                                                                нас</a></td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
</tr>
</table>
</div>
</body>
</html>