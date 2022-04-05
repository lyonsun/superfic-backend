<?php

/**
 * DB class
 */
class DB {
    public $db;

    public function __construct() {
        $this->db = new mysqli(DB_HOST, DB_USERNAME, DB_PASSWORD, DB_DATABASE_NAME);
    }
}
