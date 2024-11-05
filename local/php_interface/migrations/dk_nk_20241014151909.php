<?php

namespace Sprint\Migration;


use Bitrix\Main\Application;
use DK\NK\Entity\CartTable;

class dk_nk_20241014151909 extends Version
{
    protected $author = "admin";

    protected $description = "Добавляет таблицу корзин пользователей";

    protected $moduleVersion = "4.15.1";

    public function up(): void
    {
        $connection = Application::getConnection();
        if (!$connection->isTableExists(CartTable::getTableName())) {
            CartTable::getEntity()->createDbTable();
        }
    }

    public function down(): void
    {
        $connection = Application::getConnection();
        if ($connection->isTableExists(CartTable::getTableName())) {
            $connection->dropTable(CartTable::getTableName());
        }
    }
}
