<?php

if( isset( $_REQUEST[ 'Submit' ] ) ) {
	// Get input
	$id = $_REQUEST[ 'id' ];

	switch ($_DVWA['SQLI_DB']) {
		case MYSQL:
            		// Prepared Statement para evitar SQLi
            		$stmt = $GLOBALS["___mysqli_ston"]->prepare("SELECT first_name, last_name FROM users WHERE user_id = ?;");
            		$stmt->bind_param("s", $id);
            		$stmt->execute();
            		$result = $stmt->get_result();

            		// Mensaje de error genérico si falla (sin exponer detalles técnicos)
            		if (!$result) {
                		echo "<pre>Error al procesar la solicitud.</pre>";
                		break;
            		}

            		// Mapeo de resultados
            		while( $row = $result->fetch_assoc() ) {
                		$first = $row["first_name"];
                		$last  = $row["last_name"];

                		$html .= "<pre>ID: {$id}<br />First name: {$first}<br />Surname: {$last}</pre>";
            		}

            		$stmt->close();
            		mysqli_close($GLOBALS["___mysqli_ston"]);
            		break;

		case SQLITE:
            		global $sqlite_db_connection;

            		#$sqlite_db_connection = new SQLite3($DVWA['SQLITE_DB']);
            		#$sqlite_db_connection->enableExceptions(true);

            		try {
                		// 1. Preparar la estructura fija de la consulta SQL
                		$stmt = $sqlite_db_connection->prepare("SELECT first_name, last_name FROM users WHERE user_id = :id;");
                
                		// 2. Vincular la variable $id tratándola estrictamente como texto/cadena
                		$stmt->bindValue(':id', $id, SQLITE3_TEXT);
                
                		// 3. Ejecutar la consulta preparada
                		$results = $stmt->execute();
            		} catch (Exception $e) {
                		// Mensaje genérico para no exponer detalles de la BD (evita Information Exposure)
                		echo "<pre>Error en la consulta.</pre>";
                		exit();
            		}

            		if ($results) {
                		while ($row = $results->fetchArray()) {
                    			// Get values
                    			$first = $row["first_name"];
                    			$last  = $row["last_name"];

                    			// Feedback for end user
                    			$html .= "<pre>ID: {$id}<br />First name: {$first}<br />Surname: {$last}</pre>";
                		}
            		}
            		break;
	} 
}

?>
