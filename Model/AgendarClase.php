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
			//validar mensaje de ok y error
		$answer = array('estatus'=>'ok', 'msj'=> 'su clase se agendo exitosamente');
		} 
		catch(PDOException $e) {
			if($e->errorInfo[1] == 1062){
				$answer = array( 'estatus'=>'error','msj' =>  'ya habias agendado esta clase' );
			} else {
				$answer = array( 'estatus'=>'error','msj' =>  $e->getMessage());
			}
		}
		echo json_encode($answer);
}

function getAgendarClase($hora, $dia) { 
	$sql_query = "SELECT agendarclase.horaAg as agendarclaseHora,
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

function EliminarClienteAgendado() { 
	$request = \Slim\Slim::getInstance()->request();
	$pac = json_decode($request->getBody());
	$sql_query = "DELETE 
					FROM 
						agendarClase
					WHERE 
						no_registro = '$pac->no_registro' AND horaAg = '$pac->horaAg' AND diaAg = '$pac->diaAg'";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql_query);
		$stmt->bindParam("no_registro", $pac->no_registro);
		$stmt->execute();
		$db = null;
		/*$answer = array('estatus'=>'ok', 'msj'=> 'Cita eliminada satisfactoriamente');
	} 
	catch(PDOException $e) {
		$answer = array( 'estatus'=>'error','msj' =>  $e->getMessage());
	}
	echo json_encode($answer);*/
		$answer = array('estatus'=>'ok', 'msj'=> 'el cliente agendado se elimino exitosamente');
		} 
		catch(PDOException $e) {
			if($e->errorInfo[1] == 1451){
				$answer = array( 'estatus'=>'error','msj' =>  'No puedes eliminar a este cliente.' );
			} else {
				$answer = array( 'estatus'=>'error','msj' =>  $e->getMessage());
			}
		}
		echo json_encode($answer);
}

?>