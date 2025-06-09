<?php

namespace DK\NK\Helper;

use CIBlockSection;

class SEO {

    public static function getEcommerceProductFormat($size, $element): array {

        $categoryTree = CIblockSection::GetNavChain($element['IBLOCK_ID'], $element['IBLOCK_SECTION_ID'], ['NAME'], true);

        return [
            'id' => $size['ID'],
            'name' => sprintf('%s %s', $element['NAME'], $size['UF_NAME']),
            'price' => (double)$size['UF_PRICE_1'],
            'category' => implode(' / ', array_column($categoryTree, 'NAME'))
        ];
    }

}