<?php
class Purge {
    protected $mysqli;
    public function __construct() {
        $this->mysqli = new mysqli(Config::$db_server,Config::$db_username,Config::$db_password,Config::$db_database);
    }
    public function softDelete() {
        $queries = array(
            "UPDATE ".Config::$tables['placeCode_table']." SET delete_flag=TRUE WHERE place_id not in (SELECT place_id from ".Config::$tables['place_table']." WHERE delete_flag=FALSE)",
            "UPDATE ".Config::$tables['placeCategory_table']." SET delete_flag=TRUE WHERE place_id not in (SELECT place_id from ".Config::$tables['place_table']." WHERE delete_flag=FALSE)",
            "UPDATE ".Config::$tables['placeCategory_table']." SET delete_flag=TRUE WHERE category_id not in (SELECT category_id from ".Config::$tables['category_table']." WHERE delete_flag=FALSE)",
            "UPDATE ".Config::$tables['categorySection_table']." SET delete_flag=TRUE WHERE section_id not in (SELECT section_id from ".Config::$tables['section_table']." WHERE delete_flag=FALSE)",
            "UPDATE ".Config::$tables['categorySection_table']." SET delete_flag=TRUE WHERE placeCategory_id not in (SELECT placeCategory_id from ".Config::$tables['placeCategory_table']." WHERE delete_flag=FALSE)",
            "UPDATE ".Config::$tables['contractorMapping_table']." SET delete_flag=TRUE WHERE contractor_id not in (SELECT contractor_id from ".Config::$tables['contractor_table']." WHERE delete_flag=FALSE)",
            "UPDATE ".Config::$tables['contractorMapping_table']." SET delete_flag=TRUE WHERE categorySection_id not in (SELECT categorySection_id from ".Config::$tables['categorySection_table']." WHERE delete_flag=FALSE)",
            "UPDATE ".Config::$tables['contractorRating_table']." SET delete_flag=TRUE WHERE contractor_id not in (SELECT contractor_id from ".Config::$tables['contractor_table']." WHERE delete_flag=FALSE)",
            "UPDATE ".Config::$tables['contractorRating_table']." SET delete_flag=TRUE WHERE place_id not in (SELECT place_id from ".Config::$tables['place_table']." WHERE delete_flag=FALSE)"
        );
        foreach($queries as $key=>$value) {
            echo $value;
            $this->mysqli->query($value);
        }
        return TRUE;
    }
    public function hardDelete() {
        $this->softDelete();
        foreach(Config::$tables as $key=>$value) {
            $query = "DELETE FROM ".$value." WHERE delete_flag=TRUE";
            $this->mysqli->query($query);
        }
        return TRUE;
    }
    public function __destruct() {
        $this->mysqli->close();
    }
}
?>