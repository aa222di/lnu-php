<?php

namespace model;

class Database {

	public $db; // PDO object

	public function __construct( $host, $dbname ,$user, $pwd) {

			try {
			    $this->db = new \PDO('mysql:host=' . $host . ';dbname=' . $dbname . '', $user, $pwd);
			    $this->db->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
			} catch (\PDOException $e) {
			    print "Error!: " . $e->getMessage() . "<br/>";
			    die();
			}
	}
}