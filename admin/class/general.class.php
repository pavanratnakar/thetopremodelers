<?php
include_once(Config::$site_path.'admin/class/purge.class.php');
class General {
    protected $mysqli;
    protected $utils;
    protected $table;
    protected $id;
    protected $type;
    protected $select_type;
    protected $purge;
    /**
    * Constructor creates DB object, UTIL object and sets class specific Table
    */
    public function __construct($type) {
        $this->mysqli = new mysqli(Config::$db_server,Config::$db_username,Config::$db_password,Config::$db_database);
        $this->utils = new Utils();
        $this->table = Config::$tables[$type.'_table'];
        $this->type = $type;
        $this->id = $type.'_id';
        $this->select_type = $this->type."_title";
        $this->purge = new Purge();
    }
    /**
    * Returns array for drop down
    * @return array for select
    */
    public function getSelect() {
        $i=1;
        $query =  "SELECT ".$this->type."_id as id, ".$this->select_type." as name FROM ".$this->table." WHERE delete_flag=FALSE ORDER BY ".$this->select_type;
        if ($result = $this->mysqli->query($query)) {
            while ($row = $result->fetch_object()){
                $return_array .= $row->id.':'.$row->name;
                if($result->num_rows!=$i){
                    $return_array .=';';
                }
                $i++;
            }
        }
        return $return_array;
    }
    public function deleteDetails($id) {
        $id=$this->mysqli->real_escape_string($id);
        if ($result = $this->mysqli->query("UPDATE ".$this->table." SET delete_flag=TRUE WHERE ".$this->id."='".$id."'")) {
            if ($this->mysqli->affected_rows>0) {
                $this->purge->softDelete();
                return TRUE;
            }
        }
        return FALSE;
    }
    public function __destruct() {
        $this->mysqli->close();
    }
}
?>