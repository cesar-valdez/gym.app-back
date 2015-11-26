<?php

function getClases() { 
	$sql_query = "SELECT * FROM clases";
	try {
		$dbCon = getConnection();
		$stmt   = $dbCon->query($sql_query);
		$data  = $stmt->fetchAll(PDO::FETCH_OBJ);
		$dbCon = null;
		echo json_encode($data);
	} 
	catch(PDOException $e) {
		echo json_encode($answer);
	}
}


function addClase() {
	$request = \Slim\Slim::getInstance()->request();
	$inst = json_decode($request->getBody());

	$dias = $inst->dias;

	$answer = array();

	for($i = 0; $i < count($dias); $i++){
		$inst->dia = $dias[$i];

		$sql = "INSERT INTO clases (dia, hora, clase, imgClase, horaDuracion, minDuracion, id_instructor, no_registro) VALUES (:dia, :hora, :clase, :imgClase, :horaDuracion, :minDuracion, :id_instructor, :no_registro)";
		try {
			$db = getConnection(); 
			$stmt = $db->prepare($sql);
			$stmt->bindParam("dia", $inst->dia);
			$stmt->bindParam("hora", $inst->hora);
			$stmt->bindParam("clase", $inst->clase);
			$stmt->bindParam("imgClase", $inst->imgClase);
			$stmt->bindParam("horaDuracion", $inst->horaDuracion);
			$stmt->bindParam("minDuracion", $inst->minDuracion);
			$stmt->bindParam("id_instructor", $inst->id_instructor);
			$stmt->bindParam("no_registro", $inst->no_registro);
			$stmt->execute();
			$db = null;
			$success = array('estatus' =>  'success', 'dia'=>$inst->dia, 'hora'=> $inst->hora);
			array_push($answer, $success);
		} catch(PDOException $e) {
			$err = array('estatus'=>'error','dia'=>$inst->dia, 'hora'=> $inst->hora);
			array_push($answer, $err);
		}
	}

	echo json_encode($answer);
}


function putClases() {
	$request = \Slim\Slim::getInstance()->request();
	$req = json_decode($request->getBody());
	$sql = "UPDATE clases SET hora=:newHora, clase=:clase, id_instructor=:id_instructor,imgClase=:imgClase, horaDuracion=:horaDuracion, minDuracion=:minDuracion WHERE dia='$req->dia' AND hora='$req->hora'";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("newHora", $req->newHora);
		$stmt->bindParam("clase", $req->clase);
		$stmt->bindParam("id_instructor", $req->id_instructor);
		$stmt->bindParam("imgClase", $req->imgClase);
		$stmt->bindParam("horaDuracion", $req->horaDuracion);
		$stmt->bindParam("minDuracion", $req->minDuracion);

		$stmt->execute();
		$db = null;
		//validar mensaje de ok y error
		$answer = array('estatus'=>'ok', 'msj'=> 'clase modificada exitosamente');
		} 
		catch(PDOException $e) {
			if($e->errorInfo[1] == 1451){
				$answer = array( 'estatus'=>'error','msj' =>  'No es posible modificar los datos de la clase' );
			} else {
				$answer = array( 'estatus'=>'error','msj' =>  $e->getMessage());
			}
		}
		echo json_encode($answer);
}


function deleteClases(){
	$request = \Slim\Slim::getInstance()->request();
	$pac = json_decode($request->getBody());
	$sql_query = "DELETE 
					FROM 
						clases
					WHERE 
						hora = '$pac->hora' AND dia = '$pac->dia'";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql_query);
		$stmt->bindParam("id_instructor", $pac->id_instructor);
		$stmt->execute();
		$db = null;
		//validar mensaje de ok y error
		$answer = array('estatus'=>'ok', 'msj'=> 'clase eliminada exitosamente');
		} 
		catch(PDOException $e) {
			if($e->errorInfo[1] == 1451){
				$answer = array( 'estatus'=>'error','msj' =>  'No es posible eliminar la clase' );
			} else {
				$answer = array( 'estatus'=>'error','msj' =>  $e->getMessage());
			}
		}
		echo json_encode($answer);
}




?>
