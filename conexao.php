<?php
			function conexao()
			{

				$local = true;

				if ($local) {
					$servername = "127.0.0.1";
					$username = "certificado";
					$password = "1234";
				} else {
					$servername = "moodle.c3xuf7uapyec.us-east-1.rds.amazonaws.com";
					$username = "certificado";
					$password = "CertificadoUndb@2017";
				}
				$port = 3306;
				$dbname = "certificado";

				// Create connection
				$conn = new mysqli($servername, $username, $password, $dbname, $port);
				$conn->set_charset('utf8');
				// Check connection
				if ($conn->connect_error) {
				    die("Connection failed: " . $conn->connect_error);
				}
				return $conn;
			}


?>