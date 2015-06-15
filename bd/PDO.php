<?php

	class MySQL {

		private $db_host;
		private $db_user;
		private $db_pass;
		private $db_name;

		public function MySQL($db_data)
		{
			$this->db_host = $db_data['host'];
			$this->db_user = $db_data['username'];
			$this->db_pass = $db_data['pass'];
			$this->db_name = $db_data['instance'];
		}
		private function Connection() {

			try {

				$dbh = new PDO("mysql:host=$this->db_host;dbname=$this->db_name;charset=utf8", $this->db_user, $this->db_pass, array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES 'utf8'"));
				
				$dbh->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );

				return $dbh;

			} catch (PDOException $e) {
				echo $e->getMessage();

			}

		}

		public function Manager() {

			$dbh = $this->Connection();

			return $dbh;

		}

	}

?>