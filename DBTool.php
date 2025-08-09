<?php
defined('_JEXEC') or die;

/**
 * Класс для работы с БД в Joomla 3
 */
class DBTool
{
    /** Возвращает строку таблицы
     * @param string $tableName название таблицы
     * @param int $id id строки
     * @param array $fields ассоциативный массив полей строки
     * @return object|null строка таблицы
     */
    public static function getObjectById(string $tableName, int $id, array $fields=[]): object|null
    {
        $fieldsStr = count($fields)>0 ? implode(', ', $fields) : '*';
        $conn = JFactory::getDBO();
        $conn->setQuery("SELECT $fieldsStr FROM $tableName WHERE id=$id");
        $conn->query();
        return $conn->loadObject();
    }

    /**
     * Возвращает строку таблицы по заданному полю
     * @param $tableName string название таблицы
     * @param $columnName string название колонки
     * @param $columnValue string значение колонки
     * @param $fields array ассоциативный массив полей строки
     * @return object|null строка таблицы
     */
    public static function getObject(string $tableName, string $columnName, mixed $columnValue, array $fields=[]): object|null
    {
        if ($tableName==='' || $columnName==='' || empty($columnValue)) {
            return null;
        }
        $conn = JFactory::getDBO();
        $fieldsStr = count($fields)>0 ? implode(', ', $fields) : '*';
        $conn->setQuery("SELECT $fieldsStr FROM $tableName WHERE $columnName='$columnValue'");
        return $conn->loadObject();
    }
    
    /**
     * Вставляет новую запись
     * @param string $tableName имя таблицы
     * @param array $fields ассоциативный массив полей таблицы
     * @return int|null id строки
    */
    public static function insert(string $tableName, array $fields): int|null
    {
        $conn = JFactory::getDBO();

        // формирование полей запроса
        $fieldKeysStr = '';
        $fieldValuesStr = '';
        foreach ($fields as $key => $value) {
            $fieldKeysStr .= "$key, ";
            $fieldValuesStr .= "'$value', ";
        }
        $fieldKeysStr = mb_substr($fieldKeysStr, 0, mb_strlen($fieldKeysStr) - 2);
        $fieldValuesStr = mb_substr($fieldValuesStr, 0, mb_strlen($fieldValuesStr) - 2);

        $conn->setQuery("INSERT INTO $tableName($fieldKeysStr) VALUES ($fieldValuesStr)");
        $conn->query();

        return $conn->insertid();
    }

    /** Обновляет запись по ID
     * @param string $tableName имя таблицы
     * @param array $fields ассоциативный массив полей таблицы
     * @param int $id id строки
     * @return bool результат обновления
    */
    public static function update(string $tableName, array $fields, int $id): bool
    {
        $conn = JFactory::getDBO();

        // формирование полей запроса
        $fieldsStr = '';
        foreach ($fields as $key => $value) {
            $fieldsStr .= "$key='$value', ";
        }
        $fieldsStr = mb_substr($fieldsStr, 0, mb_strlen($fieldsStr) - 2);

        $conn->setQuery("UPDATE $tableName SET $fieldsStr WHERE id=$id");
        return $conn->query();
    }

    /**
     * Удаляет запись
     * @param string $tableName имя таблицы
     * @param int $id id строки
     * @return bool результат удаления
    */
    public static function delete(string $tableName, int $id): bool
    {
        $dbConn = JFactory::getDBO();
        if (empty(DBTool::getObjectById($tableName, $id))) {
            return false;
        }
        $dbConn->setQuery("DELETE FROM $tableName WHERE id=$id");
        $dbConn->execute();
        return true;
    }
}
