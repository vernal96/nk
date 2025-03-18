<?php

namespace Sprint\Migration;


use Bitrix\Main\Application;
use Bitrix\Main\ArgumentException;
use Bitrix\Main\DB\SqlQueryException;
use Bitrix\Main\SystemException;
use DK\NK\Entity\OrderItemsTable;
use DK\NK\Entity\OrderTable;

class dk_nk_20250315005309 extends Version
{
    protected $author = "admin";

    protected $description = "Добавляет таблицы заказов";

    protected $moduleVersion = "4.18.2";

    /**
     * @throws ArgumentException
     * @throws SystemException
     */
    public function up(): void
    {
        $connection = Application::getConnection();
        if (!$connection->isTableExists(OrderTable::getTableName())) {
            OrderTable::getEntity()->createDbTable();
        }
        if (!$connection->isTableExists(OrderItemsTable::getTableName())) {
            OrderItemsTable::getEntity()->createDbTable();
        }
    }

    /**
     * @throws SqlQueryException
     */
    public function down(): void
    {
        $connection = Application::getConnection();
        if ($connection->isTableExists(OrderTable::getTableName())) {
            $connection->dropTable(OrderTable::getTableName());
        }
        if ($connection->isTableExists(OrderItemsTable::getTableName())) {
            $connection->dropTable(OrderItemsTable::getTableName());
        }
    }
}
