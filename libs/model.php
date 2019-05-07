<?php
namespace App;

use App\Database;
use PDO;

class Model
{

    protected $table;
    protected $primary_key = 'id';
    protected $limit = 1000;
    protected $timestamps = false;
    protected $read_only = false;
    protected $db = null;

    public function __construct()
    {
        $this->db = Database::getInstance();
        if (!$this->table) {
            $this->table = Inflector::tableize(str_replace(__NAMESPACE__ . '\\', null, get_called_class()));
            // Setup Automatic created and modified field population
            switch (config('database.engine')) {
                case 'sqlite':
                    $sql = sprintf('PRAGMA table_info(`%s`);', $this->table);
                    $fieldKey = 'name';
                case 'mysql':
                    $sql = sprintf('SHOW COLUMNS FROM `%s`;', $this->table);
                    $fieldKey = 'Field';
            }
            $stmt = $this->db->prepare($sql);
            $stmt->execute();

            // Return database column names
            $fields = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($fields as $field) {
                if ($field[$fieldKey] == 'created' || $field[$fieldKey] == 'modified') {
                    $this->timestamps = true;
                    break;
                }
            }
        }
    }


    /**
     * Gets a record at specific ID
     * 
     * @param array $fields Array of fields to return
     * @param int $id The ID of the row you want to retrieve
     * 
     * @return mixed
     */
    public function read($fields = [], $id = null)
    {
        if (!empty($_fields)) {
            $fields = '`' . implode('`, `', $fields) . '`';
        } else {
            $fields = '*';
        }
        $sql = sprintf("SELECT %s FROM `%s` WHERE `%s` = :id LIMIT 1", $fields, $this->table, $this->primary_key);
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Find the all rows based off the provided conditions
     * 
     * @param array $conditions Array of fields and values to look at
     * @param array $fields Array of fields to return
     * @param int $limit Limits the number of rows returned
     * 
     * @return array
     */
    public function find($conditions = [], $fields = [], $limit = 1000)
    {
        $query = '';
        foreach ($conditions as $key => $val) {
            $query .= sprintf("`%s` = '%s' AND", $key, $val);
        }
        if (!empty($fields)) {
            $fields = '`' . implode('`, `', $fields) . '`';
        } else {
            $fields = '*';
        }
        if (empty($query)) {
            $sql = sprintf("SELECT %s FROM `%s` LIMIT %s", $fields, $this->table, $limit);
        } else {
            $query = substr($query, 0, strlen($query) - 4);
            $sql = sprintf("SELECT %s FROM `%s` WHERE %s LIMIT %s", $fields, $this->table, $query, $limit);
        }
        $stmt = $this->db->prepare($sql);

        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        return [];
    }

    /**
     * Find the first row based off the provided conditions
     * 
     * @param array $conditions Array of fields and values to look at
     * @param array $fields Array of fields to return
     * 
     * @return array
     */
    public function findFirst($conditions = [], $fields = [])
    {
        // Setup Fields
        if (!empty($fields)) {
            $fields = '`' . implode('`, `', $fields) . '`';
        } else {
            $fields = '*';
        }
        // Setup WHERE clause
        $query = '';
        foreach ($conditions as $key => $val) {
            $query .= sprintf("`%s` = '%s' AND", $key, $val);
        }

        if (empty($query)) { // Return all
            $sql = sprintf("SELECT %s FROM `%s` LIMIT 1", $fields, $this->table);
        } else { // Return all WHERE
            $query = substr($query, 0, strlen($query) - 4);
            $sql = sprintf("SELECT %s FROM `%s` WHERE %s LIMIT 1", $fields, $this->table, $query);
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Updates or inserts a row into the database
     * 
     * @param array $data Array of columns with the data associated with them
     * @param array $id (Optional) If set the row at the specified ID will be updated
     * 
     * @return mixed
     */
    public function save($data = [], $id = null)
    {
        // If table is set to read-only then return false preventing alterations
        if ($this->read_only) {
            return false;
        }

        // If table contains created/modified fields
        if ($this->timestamps) {
            $now = date('Y-m-d H:i:s');
            $id || $data['created'] = $now; // if id is specified then ignore created field
            $data['modified'] = $now;
        }

        // Create bindings
        $keys = array_keys($data);
        foreach ($data as $key => $value) {
            $data[":$key"] = $value;
            unset($data[$key]);
        }

        // Insert
        if (!$id) {
            $sql = sprintf("INSERT INTO `%s` (%s) VALUES(:%s)", $this->table, implode(',', $keys), implode(',:', $keys));
            $stmt = $this->db->prepare($sql);
            $stmt->execute($data);

            return $this->db->lastInsertId(); // Return ID of new row
        } else { // Update
            $data[":{$this->primary_key}"] = $id;
            $sql = sprintf("UPDATE `%s` SET", $this->table);
            foreach ($keys as $key => $value) {
                $sql .= sprintf(" `%s` = :%s,", $value, $value);
            }
            $sql = substr($sql, 0, strlen($sql) - 1); // Remove trailing comma
            $sql .= sprintf(" WHERE `%s` = :id", $this->primary_key);

            $stmt = $this->db->prepare($sql);
            $stmt->execute($data);

            return $stmt->rowCount(); // Return number of affected rows (essentally true if successful or false if not)
        }
    }
    /**
     * Deletes a row from the database
     * 
     * @param int $id The ID of the row you wish to delete
     * 
     * @return int
     */
    public function delete($id = null)
    {
        $stmt = $this->db->prepare(sprintf("DELETE FROM `%s` WHERE `%s` = :id", $this->table, $this->primary_key));
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount();
    }
    
    /**
     * Executes an SQL statement
     * 
     * @param string $sql The query you want to run
     * 
     * @return array
     */
    public function query($sql)
    {
        $stmt = $this->db->prepare($sql);

        if ($stmt->execute()) {
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }
}
