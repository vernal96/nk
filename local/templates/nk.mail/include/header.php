<?php
if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();
/** @var array $PARAMS */
?>
<table cellpadding="0" cellspacing="0" class="cb" align="center" role="none"
       style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;table-layout:fixed !important;width:100%">
    <tr>
        <td align="center" style="padding:0;Margin:0">
            <table bgcolor="#ffffff" class="co" align="center" cellpadding="0" cellspacing="0"
                   role="none"
                   style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px;background-color:#FFFFFF;width:600px">
                <tr>
                    <td align="left"
                        style="padding:0;Margin:0;padding-top:15px;padding-left:20px;padding-right:20px">
                        <table cellpadding="0" cellspacing="0" width="100%" role="none"
                               style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                            <tr>
                                <td align="center" valign="top" style="padding:0;Margin:0;width:560px">
                                    <table cellpadding="0" cellspacing="0" width="100%"
                                           role="presentation"
                                           style="mso-table-lspace:0pt;mso-table-rspace:0pt;border-collapse:collapse;border-spacing:0px">
                                        <tr>
                                            <td align="center"
                                                style="padding:0;Margin:0;padding-top:10px;padding-bottom:10px;font-size:0px">
                                                <img
                                                        src="https://<?= SITE_SERVER_NAME; ?>/local/templates/nk.mail/images/<?= $PARAMS['image']; ?>"
                                                        alt=""
                                                        style="display:block;border:0;outline:none;text-decoration:none;-ms-interpolation-mode:bicubic"
                                                        width="100">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center" class="ck"
                                                style="padding:0;Margin:0;padding-bottom:10px">
                                                <h1
                                                        style="Margin:0;line-height:38px;mso-line-height-rule:exactly;font-family:arial, 'helvetica neue', helvetica, sans-serif;font-size:38px;font-style:normal;font-weight:bold;color:#212121">
                                                    <?= $PARAMS['title']; ?>
                                                </h1>
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
