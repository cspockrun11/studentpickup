<?php

class StudentPickup
{

    public $testing = false;
    public $dbconfig = array();

    public function setTesting($t) {
        $this->testing = $t;
    }

    public function echodebug($content) {
        if ($this->testing == true) {
            echo "<pre>";
            print_r($content);
            echo "</pre>";
            echo "<pre>";
            debug_backtrace();
            echo "</pre>";
        }
    }

    public function setDbConfig($db) {
        $this->dbconfig = $db;
    }

    public function getStudent($student_id, $dbconfig)
    {
        $mysqli = new mysqli($this->dbconfig['host'], $this->dbconfig['user'], $this->dbconfig['pass'], $this->dbconfig['dbname']);

        $sql = "SELECT * FROM student WHERE id = $student_id;";

        return $mysqli->query($sql);
        
    }

    public function addNewFamily($family_name) {
        $timestamp_created = time();
        $sql = "INSERT INTO student (family_name, timestamp_created) VALUES (\"$family_name\", " . $timestamp_created . ");";

        $mysqli = new mysqli("localhost", "root", "root", "studentpickup");

        $mysqli->query($sql);

        $getSql = "SELECT id FROM student WHERE family_name = \"{$family_name}\" AND timestamp_created = {$timestamp_created};";

        $result = $mysqli->query($getSql);

        $mysqli->close();

        return mysqli_fetch_assoc($result);
    }

    public function saveFamilyQrCode($id, $codeSrc) {
        $mysqli = new mysqli("localhost", "root", "root", "studentpickup");

        $sql = "UPDATE student SET qrcode_link = \"$codeSrc\" WHERE id = $id;";
        $result = $mysqli->query($sql);
        $mysqli->close();
        return $result;
    }

    public function getPickups() {
        $mysqli = new mysqli("localhost", "root", "root", "studentpickup");
        $timestamp = time() - 43200; //12 hrs ago
        $sql = "SELECT p.*, s.family_name FROM pickups p LEFT JOIN student s ON p.student_id = s.id WHERE p.timestamp > {$timestamp};";
       
        $result = mysqli_fetch_all($mysqli->query($sql), MYSQLI_ASSOC);

        return $result;
    }
}