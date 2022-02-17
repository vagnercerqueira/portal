<?php

namespace App\Models;

class CrudModel
{

    private $db;
    private $tb;

    public function __construct($tb)
    {
        $this->db = db_connect();
        $this->tb = $tb;
    }
    public function tbExist()
    {
        return $this->db->tableExists($this->tb);
    }
    public function schema()
    {
        $result = $this->db->getFieldNames($this->tb);

        return $result;
    }

    public function insertItem($data)
    {
        foreach ($data as $k => $v) {
            if ($v === "" && $k !== "ID") {
                $data[$k] = NULL;
            }
        }
        $this->db->table($this->tb)->insert($data);

        $err_db = $this->db->error();
        /* print_r($err_db);
        exit;*/
        $err_db['key'] = $this->db->insertID();
        return $err_db;
        // return $this->db->insertID();
    }

    public function getAnyItems($id)
    {

        $builder = $this->db->table($this->tb);
        $builder->where('id', $id);

        return $builder->get()->getResultArray();
    }

    public function updateItem($id, $data)
    {
        foreach ($data as $k => $v) {
            if ($v === "") {
                $data[$k] = NULL;
            }
        }
        $this->db->table($this->tb)->where('id', $id)->update($data);
        $err_db = $this->db->error();
        return $err_db;
    }

    function get_primary_key_field_name()
    {
        $query = "SHOW KEYS FROM {$this->tb} WHERE Key_name = 'PRIMARY'";
        return $this->db->query($query)->getRow()->Column_name;
    }

    public function deleteItem($id)
    {
        $this->db->table($this->tb)->delete(['id' => $id]);
        $err_db = $this->db->error();
        $err_db['aff'] = $this->db->Affectedrows();
        return $err_db;
    }

    public function batchInsert($data)
    {
        $builder = $this->db->table($this->tb);
        return $builder->insertBatch($data);
    }
}
