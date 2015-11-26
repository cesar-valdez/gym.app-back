<?php
function AgendarClase() {
	$request = \Slim\Slim::getInstance()->request();
	$inst = json_decode($request->getBody());

		$sql = "INSERT INTO agendarclase (diaAg, horaAg, claseAg, imgClaseAg, horaDuracionAg, minDuracionAg, id_instructor, no_registro) VALUES (:diaAg, :horaAg, :claseAg, :imgClaseAg, :horaDuracionAg, :minDuracionAg, :id_instructor, :no_registro)";
		try {
			$db = getConnection(); 
			$stmt = $db->prepare($sql);
			$stmt->bindParam("diaAg", $inst->diaAg);
			$stmt->bindParam("horaAg", $inst->horaAg);
			$stmt->bindParam("claseAg", $inst->claseAg);
			$stmt->bindParam("imgClaseAg", $inst->imgClaseAg);
			$stmt->bindParam("horaDuracionAg", $inst->horaDuracionAg);
			$stmt->bindParam("minDuracionAg", $inst->minDuracionAg);
			$stmt->bindParam("id_instructor", $inst->id_instructor);
			$stmt->bindParam("no_registro", $inst->no_registro);
			$stmt->execute();
			//$inst->id_agendar = $db->lastInsertId();
			$db = null;
			echo json_encode($inst);
		} catch(PDOException $e) {
			$answer = array( 'error' =>  $e->getMessage());
			echo json_encode($answer);
		}
}

function getAgendarClase($hora, $dia) { 
	$sql_query = "SELECT agendarclase.id_agendar as agendarclaseIDAgendar,
						 agendarclase.horaAg as agendarclaseHora,
						 agendarclase.diaAg as agendarclaseDia,
						 clientes.no_registro as ClientesRegistro,
						 clientes.nombre as ClientesNombre,
						 clientes.apellido as ClientesApellido,
						 clientes.celular as ClientesCelular,
						 clientes.peso as ClientesPeso,
						 clientes.estatura as ClientesEstatura,
						 clientes.edad as ClientesEdad,
						 clientes.domicilio as ClientesDomicilio
				FROM agendarclase, clientes
				WHERE
					agendarclase.no_registro = clientes.no_registro AND horaAg = '$hora' AND diaAg = '$dia'";
	try {
		$dbCon = getConnection();
		$stmt   = $dbCon->query($sql_query);
		$data  = $stmt->fetchAll(PDO::FETCH_OBJ);
		$dbCon = null;
		echo json_encode($data);
	} 
	catch(PDOException $e) {
		$answer = array( 'error' =>  $e->getMessage());
		echo json_encode($answer);
	}
}

?>