<?php
namespace System;
class Database extends Utils{

    public $db;
    function __construct() {
        parent::__construct();
        $this->db = new \MeekroDB(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME, DB_PORT, CHARSET);
      }
}