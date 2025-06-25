<?php

class DKSitemap extends CBitrixComponent
{

    public function executeComponent(): void
    {
        if ($this->startResultCache(3600, 'sitemap')) {
            $menu = $this->getMenu();
            $catalogTree = $this->getIblockTree(IBLOCK_CATALOG);
            $newsTree = $this->getIblockTree(IBLOCK_NEWS);

            $this->addChildrenToMenu($menu, 'CATALOG', $catalogTree);
            $this->addChildrenToMenu($menu, 'NEWS', $newsTree);
            $this->arResult = $menu;
            $this->setResultCacheKeys([]);
            $this->includeComponentTemplate();
        }
    }

    private function addChildrenToMenu(array &$menu, string $name,  array $children): void
    {
        foreach ($menu as &$menuItem) {
            if ($menuItem['params'][$name] === 'Y') {
                $menuItem['children'] = $children;
                break;
            }
        }
    }

    private function getMenu(): array
    {
        global $APPLICATION;
        ob_start();
        $menu = $APPLICATION->IncludeComponent(
            'bitrix:menu', '', MENU_PARAMS, $this, [], true
        );
        ob_end_clean();

        return array_map(function($item) {
            return [
                'text' => $item['TEXT'],
                'link' => $item['LINK'],
                'params' => $item['PARAMS']
            ];
        }, $menu);

    }

    private function getIblockTree(int $iblockId): array {
        $sections = [];
        $elements = [];

        // Получаем разделы
        $sectionResult = CIblockSection::GetTreeList(
            ['GLOBAL_ACTIVE' => 'Y', 'ACTIVE' => 'Y', 'IBLOCK_ID' => $iblockId],
            ['NAME', 'SECTION_PAGE_URL', 'IBLOCK_SECTION_ID', 'ID']
        );
        while ($section = $sectionResult->GetNext()) {
            $sections[] = $section;
        }

        // Получаем элементы
        $elementResult = CIBlockElement::GetList(
            ['SORT' => 'ASC'],
            ['ACTIVE_DATE' => 'Y', 'ACTIVE' => 'Y', 'IBLOCK_ID' => $iblockId],
            false, false,
            ['NAME', 'IBLOCK_SECTION_ID', 'DETAIL_PAGE_URL', 'ID']
        );
        while ($element = $elementResult->GetNext()) {
            $elements[] = $element;
        }

        $sectionMap = [];
        $tree = [];

        // Шаг 1: Подготавливаем разделы (в нужном формате)
        foreach ($sections as &$section) {
            $section = [
                'id' => $section['ID'],
                'parent' => $section['IBLOCK_SECTION_ID'] ?? null,
                'text' => $section['NAME'],
                'link' => $section['SECTION_PAGE_URL'],
                'children' => [],
            ];
            $sectionMap[$section['id']] = &$section;
        }
        unset($section);

        // Шаг 2: Формируем дерево разделов
        foreach ($sectionMap as &$section) {
            if ($section['parent'] && isset($sectionMap[$section['parent']])) {
                $sectionMap[$section['parent']]['children'][] = &$section;
            } else {
                $tree[] = &$section;
            }
        }
        unset($section);

        // Шаг 3: Добавляем элементы в нужные разделы
        foreach ($elements as $element) {
            $sectionId = $element['IBLOCK_SECTION_ID'] ?? null;
            if ($sectionId && isset($sectionMap[$sectionId])) {
                $sectionMap[$sectionId]['children'][] = [
                    'text' => $element['NAME'],
                    'link' => $element['DETAIL_PAGE_URL']
                ];
            }
        }

        return $tree;
    }

}