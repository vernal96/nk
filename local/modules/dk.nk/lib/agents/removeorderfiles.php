<?php

namespace DK\NK\Agents;

use Bitrix\Main\FileTable;
use Bitrix\Main\Type\DateTime as BitrixDateTime;
use CFile;
use Throwable;

class RemoveOrderFiles {

    static function run(): string
    {
        try {
            $files = FileTable::query()
                ->addSelect('ID')
                ->where('TIMESTAMP_X', '<', (new BitrixDateTime())->add('5 minute'))
                ->where('DESCRIPTION', 'order')
                ->fetchCollection();
            foreach ($files as $file) {
                CFile::Delete($file->getId());
            }
        } catch (Throwable) {}
        return __METHOD__ . '();';
    }

}