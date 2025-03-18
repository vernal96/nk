<?php

namespace Sprint\Migration;


use Bitrix\Main\Application;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\DB\SqlQueryException;
use Bitrix\Main\SystemException;
use DK\NK\Entity\FeedbackTable;

class dk_nk_20250318210658 extends Version
{
    protected $author = "admin";

    protected $description = "Добавляет таблицу заявок";

    protected $moduleVersion = "4.18.2";

    /**
     * @throws SystemException
     * @throws ArgumentException
     */
    public function up(): void
    {
        $connection = Application::getConnection();
        if (!$connection->isTableExists(FeedbackTable::getTableName())) {
            FeedbackTable::getEntity()->createDbTable();
        }
    }

    /**
     * @throws SqlQueryException
     */
    public function down(): void
    {
        $connection = Application::getConnection();
        if ($connection->isTableExists(FeedbackTable::getTableName())) {
            $connection->dropTable(FeedbackTable::getTableName());
        }
    }
}
