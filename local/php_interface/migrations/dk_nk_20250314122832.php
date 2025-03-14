<?php

namespace Sprint\Migration;


class dk_nk_20250314122832 extends Version
{
    protected $author = "admin";

    protected $description = "Пользовательские поля";

    protected $moduleVersion = "4.18.2";

    /**
     * @throws Exceptions\HelperException
     * @return bool|void
     */
    public function up()
    {
        $helper = $this->getHelperManager();
        $helper->UserTypeEntity()->saveUserTypeEntity(array (
  'ENTITY_ID' => 'IBLOCK_catalog:orders_SECTION',
  'FIELD_NAME' => 'UF_NAME',
  'USER_TYPE_ID' => 'string',
  'XML_ID' => 'NAME',
  'SORT' => '100',
  'MULTIPLE' => 'N',
  'MANDATORY' => 'Y',
  'SHOW_FILTER' => 'N',
  'SHOW_IN_LIST' => 'Y',
  'EDIT_IN_LIST' => 'Y',
  'IS_SEARCHABLE' => 'N',
  'SETTINGS' => 
  array (
    'SIZE' => 70,
    'ROWS' => 1,
    'REGEXP' => '',
    'MIN_LENGTH' => 0,
    'MAX_LENGTH' => 0,
    'DEFAULT_VALUE' => '',
  ),
  'EDIT_FORM_LABEL' => 
  array (
    'en' => 'Name',
    'ru' => 'Имя',
  ),
  'LIST_COLUMN_LABEL' => 
  array (
    'en' => 'Name',
    'ru' => 'Имя',
  ),
  'LIST_FILTER_LABEL' => 
  array (
    'en' => 'Name',
    'ru' => 'Имя',
  ),
  'ERROR_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
  'HELP_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
));
        $helper->UserTypeEntity()->saveUserTypeEntity(array (
  'ENTITY_ID' => 'IBLOCK_catalog:orders_SECTION',
  'FIELD_NAME' => 'UF_PHONE',
  'USER_TYPE_ID' => 'string',
  'XML_ID' => 'PHONE',
  'SORT' => '100',
  'MULTIPLE' => 'N',
  'MANDATORY' => 'Y',
  'SHOW_FILTER' => 'N',
  'SHOW_IN_LIST' => 'Y',
  'EDIT_IN_LIST' => 'Y',
  'IS_SEARCHABLE' => 'N',
  'SETTINGS' => 
  array (
    'SIZE' => 20,
    'ROWS' => 1,
    'REGEXP' => '',
    'MIN_LENGTH' => 0,
    'MAX_LENGTH' => 0,
    'DEFAULT_VALUE' => '',
  ),
  'EDIT_FORM_LABEL' => 
  array (
    'en' => 'Phone',
    'ru' => 'Телефон',
  ),
  'LIST_COLUMN_LABEL' => 
  array (
    'en' => 'Phone',
    'ru' => 'Телефон',
  ),
  'LIST_FILTER_LABEL' => 
  array (
    'en' => 'Phone',
    'ru' => 'Телефон',
  ),
  'ERROR_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
  'HELP_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
));
        $helper->UserTypeEntity()->saveUserTypeEntity(array (
  'ENTITY_ID' => 'IBLOCK_catalog:orders_SECTION',
  'FIELD_NAME' => 'UF_EMAIL',
  'USER_TYPE_ID' => 'string',
  'XML_ID' => 'EMAIL',
  'SORT' => '100',
  'MULTIPLE' => 'N',
  'MANDATORY' => 'N',
  'SHOW_FILTER' => 'N',
  'SHOW_IN_LIST' => 'Y',
  'EDIT_IN_LIST' => 'Y',
  'IS_SEARCHABLE' => 'N',
  'SETTINGS' => 
  array (
    'SIZE' => 20,
    'ROWS' => 1,
    'REGEXP' => '',
    'MIN_LENGTH' => 0,
    'MAX_LENGTH' => 0,
    'DEFAULT_VALUE' => '',
  ),
  'EDIT_FORM_LABEL' => 
  array (
    'en' => 'Email',
    'ru' => 'Email',
  ),
  'LIST_COLUMN_LABEL' => 
  array (
    'en' => 'Email',
    'ru' => 'Email',
  ),
  'LIST_FILTER_LABEL' => 
  array (
    'en' => 'Email',
    'ru' => 'Email',
  ),
  'ERROR_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
  'HELP_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
));
        $helper->UserTypeEntity()->saveUserTypeEntity(array (
  'ENTITY_ID' => 'IBLOCK_catalog:orders_SECTION',
  'FIELD_NAME' => 'UF_TYPE',
  'USER_TYPE_ID' => 'enumeration',
  'XML_ID' => 'TYPE',
  'SORT' => '100',
  'MULTIPLE' => 'N',
  'MANDATORY' => 'Y',
  'SHOW_FILTER' => 'N',
  'SHOW_IN_LIST' => 'Y',
  'EDIT_IN_LIST' => 'Y',
  'IS_SEARCHABLE' => 'N',
  'SETTINGS' => 
  array (
    'DISPLAY' => 'LIST',
    'LIST_HEIGHT' => 1,
    'CAPTION_NO_VALUE' => '',
    'SHOW_NO_VALUE' => 'N',
  ),
  'EDIT_FORM_LABEL' => 
  array (
    'en' => 'Entity type',
    'ru' => 'Тип лица',
  ),
  'LIST_COLUMN_LABEL' => 
  array (
    'en' => 'Entity type',
    'ru' => 'Тип лица',
  ),
  'LIST_FILTER_LABEL' => 
  array (
    'en' => 'Entity type',
    'ru' => 'Тип лица',
  ),
  'ERROR_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
  'HELP_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
  'ENUM_VALUES' => 
  array (
    0 => 
    array (
      'VALUE' => 'Физическое лицо',
      'DEF' => 'Y',
      'SORT' => '500',
      'XML_ID' => 'pht',
    ),
    1 => 
    array (
      'VALUE' => 'Юридическое лицо',
      'DEF' => 'N',
      'SORT' => '500',
      'XML_ID' => 'jur',
    ),
  ),
));
        $helper->UserTypeEntity()->saveUserTypeEntity(array (
  'ENTITY_ID' => 'IBLOCK_catalog:orders_SECTION',
  'FIELD_NAME' => 'UF_DELIVERY',
  'USER_TYPE_ID' => 'enumeration',
  'XML_ID' => 'DELIVERY',
  'SORT' => '100',
  'MULTIPLE' => 'N',
  'MANDATORY' => 'Y',
  'SHOW_FILTER' => 'N',
  'SHOW_IN_LIST' => 'Y',
  'EDIT_IN_LIST' => 'Y',
  'IS_SEARCHABLE' => 'N',
  'SETTINGS' => 
  array (
    'DISPLAY' => 'LIST',
    'LIST_HEIGHT' => 1,
    'CAPTION_NO_VALUE' => '',
    'SHOW_NO_VALUE' => 'N',
  ),
  'EDIT_FORM_LABEL' => 
  array (
    'en' => 'Delivery type',
    'ru' => 'Тип получения',
  ),
  'LIST_COLUMN_LABEL' => 
  array (
    'en' => 'Delivery type',
    'ru' => 'Тип получения',
  ),
  'LIST_FILTER_LABEL' => 
  array (
    'en' => 'Delivery type',
    'ru' => 'Тип получения',
  ),
  'ERROR_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
  'HELP_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
  'ENUM_VALUES' => 
  array (
    0 => 
    array (
      'VALUE' => 'Доставка',
      'DEF' => 'Y',
      'SORT' => '500',
      'XML_ID' => 'delivery',
    ),
    1 => 
    array (
      'VALUE' => 'Самовывоз',
      'DEF' => 'N',
      'SORT' => '500',
      'XML_ID' => 'self',
    ),
  ),
));
        $helper->UserTypeEntity()->saveUserTypeEntity(array (
  'ENTITY_ID' => 'IBLOCK_catalog:orders_SECTION',
  'FIELD_NAME' => 'UF_USER',
  'USER_TYPE_ID' => 'integer',
  'XML_ID' => 'USER',
  'SORT' => '100',
  'MULTIPLE' => 'N',
  'MANDATORY' => 'N',
  'SHOW_FILTER' => 'N',
  'SHOW_IN_LIST' => 'Y',
  'EDIT_IN_LIST' => 'Y',
  'IS_SEARCHABLE' => 'N',
  'SETTINGS' => 
  array (
    'SIZE' => 20,
    'MIN_VALUE' => 0,
    'MAX_VALUE' => 0,
    'DEFAULT_VALUE' => NULL,
  ),
  'EDIT_FORM_LABEL' => 
  array (
    'en' => 'User Id',
    'ru' => 'Id пользователя',
  ),
  'LIST_COLUMN_LABEL' => 
  array (
    'en' => 'User Id',
    'ru' => 'Id пользователя',
  ),
  'LIST_FILTER_LABEL' => 
  array (
    'en' => 'User Id',
    'ru' => 'Id пользователя',
  ),
  'ERROR_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
  'HELP_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
));
        $helper->UserTypeEntity()->saveUserTypeEntity(array (
  'ENTITY_ID' => 'IBLOCK_catalog:orders_SECTION',
  'FIELD_NAME' => 'UF_CITY',
  'USER_TYPE_ID' => 'hlblock',
  'XML_ID' => 'CITY',
  'SORT' => '100',
  'MULTIPLE' => 'N',
  'MANDATORY' => 'N',
  'SHOW_FILTER' => 'N',
  'SHOW_IN_LIST' => 'Y',
  'EDIT_IN_LIST' => 'Y',
  'IS_SEARCHABLE' => 'N',
  'SETTINGS' => 
  array (
    'DISPLAY' => 'LIST',
    'LIST_HEIGHT' => 1,
    'HLBLOCK_ID' => 'DeliveryCities',
    'HLFIELD_ID' => 'UF_NAME',
    'DEFAULT_VALUE' => '',
  ),
  'EDIT_FORM_LABEL' => 
  array (
    'en' => 'City',
    'ru' => 'Город',
  ),
  'LIST_COLUMN_LABEL' => 
  array (
    'en' => 'City',
    'ru' => 'Город',
  ),
  'LIST_FILTER_LABEL' => 
  array (
    'en' => 'City',
    'ru' => 'Город',
  ),
  'ERROR_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
  'HELP_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
));
        $helper->UserTypeEntity()->saveUserTypeEntity(array (
  'ENTITY_ID' => 'IBLOCK_catalog:orders_SECTION',
  'FIELD_NAME' => 'UF_STREET',
  'USER_TYPE_ID' => 'string',
  'XML_ID' => 'STREET',
  'SORT' => '100',
  'MULTIPLE' => 'N',
  'MANDATORY' => 'N',
  'SHOW_FILTER' => 'N',
  'SHOW_IN_LIST' => 'Y',
  'EDIT_IN_LIST' => 'Y',
  'IS_SEARCHABLE' => 'N',
  'SETTINGS' => 
  array (
    'SIZE' => 20,
    'ROWS' => 1,
    'REGEXP' => '',
    'MIN_LENGTH' => 0,
    'MAX_LENGTH' => 0,
    'DEFAULT_VALUE' => '',
  ),
  'EDIT_FORM_LABEL' => 
  array (
    'en' => 'Street',
    'ru' => 'Улица',
  ),
  'LIST_COLUMN_LABEL' => 
  array (
    'en' => 'Street',
    'ru' => 'Улица',
  ),
  'LIST_FILTER_LABEL' => 
  array (
    'en' => 'Street',
    'ru' => 'Улица',
  ),
  'ERROR_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
  'HELP_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
));
        $helper->UserTypeEntity()->saveUserTypeEntity(array (
  'ENTITY_ID' => 'IBLOCK_catalog:orders_SECTION',
  'FIELD_NAME' => 'UF_HOUSE',
  'USER_TYPE_ID' => 'string',
  'XML_ID' => 'HOUSE',
  'SORT' => '100',
  'MULTIPLE' => 'N',
  'MANDATORY' => 'N',
  'SHOW_FILTER' => 'N',
  'SHOW_IN_LIST' => 'Y',
  'EDIT_IN_LIST' => 'Y',
  'IS_SEARCHABLE' => 'N',
  'SETTINGS' => 
  array (
    'SIZE' => 20,
    'ROWS' => 1,
    'REGEXP' => '',
    'MIN_LENGTH' => 0,
    'MAX_LENGTH' => 0,
    'DEFAULT_VALUE' => '',
  ),
  'EDIT_FORM_LABEL' => 
  array (
    'en' => 'House',
    'ru' => 'Дом',
  ),
  'LIST_COLUMN_LABEL' => 
  array (
    'en' => 'House',
    'ru' => 'Дом',
  ),
  'LIST_FILTER_LABEL' => 
  array (
    'en' => 'House',
    'ru' => 'Дом',
  ),
  'ERROR_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
  'HELP_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
));
        $helper->UserTypeEntity()->saveUserTypeEntity(array (
  'ENTITY_ID' => 'IBLOCK_catalog:orders_SECTION',
  'FIELD_NAME' => 'UF_CORPUS',
  'USER_TYPE_ID' => 'string',
  'XML_ID' => 'CORPUS',
  'SORT' => '100',
  'MULTIPLE' => 'N',
  'MANDATORY' => 'N',
  'SHOW_FILTER' => 'N',
  'SHOW_IN_LIST' => 'Y',
  'EDIT_IN_LIST' => 'Y',
  'IS_SEARCHABLE' => 'N',
  'SETTINGS' => 
  array (
    'SIZE' => 20,
    'ROWS' => 1,
    'REGEXP' => '',
    'MIN_LENGTH' => 0,
    'MAX_LENGTH' => 0,
    'DEFAULT_VALUE' => '',
  ),
  'EDIT_FORM_LABEL' => 
  array (
    'en' => 'Corpus',
    'ru' => 'Корпус',
  ),
  'LIST_COLUMN_LABEL' => 
  array (
    'en' => 'Corpus',
    'ru' => 'Корпус',
  ),
  'LIST_FILTER_LABEL' => 
  array (
    'en' => 'Corpus',
    'ru' => 'Корпус',
  ),
  'ERROR_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
  'HELP_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
));
        $helper->UserTypeEntity()->saveUserTypeEntity(array (
  'ENTITY_ID' => 'IBLOCK_catalog:orders_SECTION',
  'FIELD_NAME' => 'UF_ENTRANCE',
  'USER_TYPE_ID' => 'string',
  'XML_ID' => 'ENTRANCE',
  'SORT' => '100',
  'MULTIPLE' => 'N',
  'MANDATORY' => 'N',
  'SHOW_FILTER' => 'N',
  'SHOW_IN_LIST' => 'Y',
  'EDIT_IN_LIST' => 'Y',
  'IS_SEARCHABLE' => 'N',
  'SETTINGS' => 
  array (
    'SIZE' => 20,
    'ROWS' => 1,
    'REGEXP' => '',
    'MIN_LENGTH' => 0,
    'MAX_LENGTH' => 0,
    'DEFAULT_VALUE' => '',
  ),
  'EDIT_FORM_LABEL' => 
  array (
    'en' => 'Entrance',
    'ru' => 'Подъезд',
  ),
  'LIST_COLUMN_LABEL' => 
  array (
    'en' => 'Entrance',
    'ru' => 'Подъезд',
  ),
  'LIST_FILTER_LABEL' => 
  array (
    'en' => 'Entrance',
    'ru' => 'Подъезд',
  ),
  'ERROR_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
  'HELP_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
));
        $helper->UserTypeEntity()->saveUserTypeEntity(array (
  'ENTITY_ID' => 'IBLOCK_catalog:orders_SECTION',
  'FIELD_NAME' => 'UF_OFFICE',
  'USER_TYPE_ID' => 'string',
  'XML_ID' => 'OFFICE',
  'SORT' => '100',
  'MULTIPLE' => 'N',
  'MANDATORY' => 'N',
  'SHOW_FILTER' => 'N',
  'SHOW_IN_LIST' => 'Y',
  'EDIT_IN_LIST' => 'Y',
  'IS_SEARCHABLE' => 'N',
  'SETTINGS' => 
  array (
    'SIZE' => 20,
    'ROWS' => 1,
    'REGEXP' => '',
    'MIN_LENGTH' => 0,
    'MAX_LENGTH' => 0,
    'DEFAULT_VALUE' => '',
  ),
  'EDIT_FORM_LABEL' => 
  array (
    'en' => 'Apartment/office',
    'ru' => 'Квартира/офис',
  ),
  'LIST_COLUMN_LABEL' => 
  array (
    'en' => 'Apartment/office',
    'ru' => 'Квартира/офис',
  ),
  'LIST_FILTER_LABEL' => 
  array (
    'en' => 'Apartment/office',
    'ru' => 'Квартира/офис',
  ),
  'ERROR_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
  'HELP_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
));
        $helper->UserTypeEntity()->saveUserTypeEntity(array (
  'ENTITY_ID' => 'IBLOCK_catalog:orders_SECTION',
  'FIELD_NAME' => 'UF_MARKET',
  'USER_TYPE_ID' => 'iblock_element',
  'XML_ID' => 'MARKET',
  'SORT' => '100',
  'MULTIPLE' => 'N',
  'MANDATORY' => 'N',
  'SHOW_FILTER' => 'N',
  'SHOW_IN_LIST' => 'Y',
  'EDIT_IN_LIST' => 'Y',
  'IS_SEARCHABLE' => 'N',
  'SETTINGS' => 
  array (
    'DISPLAY' => 'LIST',
    'LIST_HEIGHT' => 1,
    'IBLOCK_ID' => 'content:market',
    'DEFAULT_VALUE' => '',
    'ACTIVE_FILTER' => 'N',
  ),
  'EDIT_FORM_LABEL' => 
  array (
    'en' => 'Market',
    'ru' => 'Магазин',
  ),
  'LIST_COLUMN_LABEL' => 
  array (
    'en' => 'Market',
    'ru' => 'Магазин',
  ),
  'LIST_FILTER_LABEL' => 
  array (
    'en' => 'Market',
    'ru' => 'Магазин',
  ),
  'ERROR_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
  'HELP_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
));
        $helper->UserTypeEntity()->saveUserTypeEntity(array (
  'ENTITY_ID' => 'IBLOCK_catalog:orders_SECTION',
  'FIELD_NAME' => 'UF_COMMENT',
  'USER_TYPE_ID' => 'string',
  'XML_ID' => 'COMMENT',
  'SORT' => '100',
  'MULTIPLE' => 'N',
  'MANDATORY' => 'N',
  'SHOW_FILTER' => 'N',
  'SHOW_IN_LIST' => 'Y',
  'EDIT_IN_LIST' => 'Y',
  'IS_SEARCHABLE' => 'N',
  'SETTINGS' => 
  array (
    'SIZE' => 70,
    'ROWS' => 2,
    'REGEXP' => '',
    'MIN_LENGTH' => 0,
    'MAX_LENGTH' => 0,
    'DEFAULT_VALUE' => '',
  ),
  'EDIT_FORM_LABEL' => 
  array (
    'en' => 'Comment',
    'ru' => 'Комментарий',
  ),
  'LIST_COLUMN_LABEL' => 
  array (
    'en' => 'Comment',
    'ru' => 'Комментарий',
  ),
  'LIST_FILTER_LABEL' => 
  array (
    'en' => 'Comment',
    'ru' => 'Комментарий',
  ),
  'ERROR_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
  'HELP_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
));
        $helper->UserTypeEntity()->saveUserTypeEntity(array (
  'ENTITY_ID' => 'IBLOCK_catalog:orders_SECTION',
  'FIELD_NAME' => 'UF_FILES',
  'USER_TYPE_ID' => 'file',
  'XML_ID' => 'FILES',
  'SORT' => '100',
  'MULTIPLE' => 'Y',
  'MANDATORY' => 'N',
  'SHOW_FILTER' => 'N',
  'SHOW_IN_LIST' => 'Y',
  'EDIT_IN_LIST' => 'Y',
  'IS_SEARCHABLE' => 'N',
  'SETTINGS' => 
  array (
    'SIZE' => 20,
    'LIST_WIDTH' => 0,
    'LIST_HEIGHT' => 0,
    'MAX_SHOW_SIZE' => 0,
    'MAX_ALLOWED_SIZE' => 0,
    'EXTENSIONS' => 
    array (
    ),
    'TARGET_BLANK' => 'Y',
  ),
  'EDIT_FORM_LABEL' => 
  array (
    'en' => 'Files',
    'ru' => 'Файлы',
  ),
  'LIST_COLUMN_LABEL' => 
  array (
    'en' => 'Files',
    'ru' => 'Файлы',
  ),
  'LIST_FILTER_LABEL' => 
  array (
    'en' => 'Files',
    'ru' => 'Файлы',
  ),
  'ERROR_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
  'HELP_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
));
    }

}
