<?php

namespace app\helpers;

/**
 * DataLoader - Helper class to load static JSON data files
 * This replaces database queries with file-based data storage
 */
class DataLoader
{
    private static $dataPath = __DIR__ . '/../data/';
    private static $cache = [];

    /**
     * Load data from JSON file
     * @param string $filename The JSON file name (without .json extension)
     * @return array The loaded data
     */
    public static function load($filename)
    {
        // Check cache first
        if (isset(self::$cache[$filename])) {
            return self::$cache[$filename];
        }

        $filepath = self::$dataPath . $filename . '.json';

        if (!file_exists($filepath)) {
            return [];
        }

        $json = file_get_contents($filepath);
        $data = json_decode($json, true);

        // Cache the data
        self::$cache[$filename] = $data ?: [];

        return self::$cache[$filename];
    }

    /**
     * Save data to JSON file
     * @param string $filename The JSON file name (without .json extension)
     * @param array $data The data to save
     * @return bool Success status
     */
    public static function save($filename, $data)
    {
        $filepath = self::$dataPath . $filename . '.json';

        $json = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        $result = file_put_contents($filepath, $json);

        // Update cache
        if ($result !== false) {
            self::$cache[$filename] = $data;
        }

        return $result !== false;
    }

    /**
     * Find a single record by ID
     * @param string $filename The JSON file name
     * @param int $id The record ID
     * @return array|null The found record or null
     */
    public static function findById($filename, $id)
    {
        $data = self::load($filename);

        foreach ($data as $record) {
            if (isset($record['id']) && $record['id'] == $id) {
                return $record;
            }
        }

        return null;
    }

    /**
     * Find records by a field value
     * @param string $filename The JSON file name
     * @param string $field The field name
     * @param mixed $value The field value
     * @return array The found records
     */
    public static function findBy($filename, $field, $value)
    {
        $data = self::load($filename);
        $results = [];

        foreach ($data as $record) {
            if (isset($record[$field]) && $record[$field] == $value) {
                $results[] = $record;
            }
        }

        return $results;
    }

    /**
     * Find a single record by field value
     * @param string $filename The JSON file name
     * @param string $field The field name
     * @param mixed $value The field value
     * @return array|null The found record or null
     */
    public static function findOneBy($filename, $field, $value)
    {
        $results = self::findBy($filename, $field, $value);
        return !empty($results) ? $results[0] : null;
    }

    /**
     * Get all records
     * @param string $filename The JSON file name
     * @return array All records
     */
    public static function findAll($filename)
    {
        return self::load($filename);
    }

    /**
     * Add a new record
     * @param string $filename The JSON file name
     * @param array $record The record to add
     * @return bool Success status
     */
    public static function insert($filename, $record)
    {
        $data = self::load($filename);

        // Auto-increment ID if not set
        if (!isset($record['id'])) {
            $maxId = 0;
            foreach ($data as $item) {
                if (isset($item['id']) && $item['id'] > $maxId) {
                    $maxId = $item['id'];
                }
            }
            $record['id'] = $maxId + 1;
        }

        $data[] = $record;
        return self::save($filename, $data);
    }

    /**
     * Update a record by ID
     * @param string $filename The JSON file name
     * @param int $id The record ID
     * @param array $updates The fields to update
     * @return bool Success status
     */
    public static function update($filename, $id, $updates)
    {
        $data = self::load($filename);
        $updated = false;

        foreach ($data as $key => $record) {
            if (isset($record['id']) && $record['id'] == $id) {
                $data[$key] = array_merge($record, $updates);
                $updated = true;
                break;
            }
        }

        if ($updated) {
            return self::save($filename, $data);
        }

        return false;
    }

    /**
     * Delete a record by ID
     * @param string $filename The JSON file name
     * @param int $id The record ID
     * @return bool Success status
     */
    public static function delete($filename, $id)
    {
        $data = self::load($filename);
        $newData = [];
        $deleted = false;

        foreach ($data as $record) {
            if (!isset($record['id']) || $record['id'] != $id) {
                $newData[] = $record;
            } else {
                $deleted = true;
            }
        }

        if ($deleted) {
            return self::save($filename, $newData);
        }

        return false;
    }

    /**
     * Clear cache for a specific file or all files
     * @param string|null $filename The JSON file name or null for all
     */
    public static function clearCache($filename = null)
    {
        if ($filename === null) {
            self::$cache = [];
        } else {
            unset(self::$cache[$filename]);
        }
    }
}
