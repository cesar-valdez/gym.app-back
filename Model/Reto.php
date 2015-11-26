<?php

function getRetos() { 
	$sql_query = "SELECT * FROM retos";
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


function addRetos() {
	$request = \Slim\Slim::getInstance()->request();
	$inst = json_decode($request->getBody());
	$sql = "INSERT INTO retos (id_reto, reto, imgReto, fechaInicio, no_registro, descripcion) VALUES (:id_reto, :reto, :imgReto, :fechaInicio, :no_registro, :descripcion)";
	try {
		$db = getConnection(); 
		$stmt = $db->prepare($sql);
		$stmt->bindParam("id_reto", $inst->id_reto);
		$stmt->bindParam("reto", $inst->reto);
		$stmt->bindParam("imgReto", $inst->imgReto);
		$stmt->bindParam("fechaInicio", $inst->fechaInicio);
		$stmt->bindParam("no_registro", $inst->no_registro);
		$stmt->bindParam("descripcion", $inst->descripcion);
		$stmt->execute();
		$inst->id_reto = $db->lastInsertId();
		$db = null;
		//validar mensaje de ok y error
		$answer = array('estatus'=>'ok', 'msj'=> 'reto agregado exitosamente');
		} 
		catch(PDOException $e) {
			if($e->errorInfo[1] == 1451){
				$answer = array( 'estatus'=>'error','msj' =>  'No es posible agregar este reto' );
			} else {
				$answer = array( 'estatus'=>'error','msj' =>  $e->getMessage());
			}
		}
		echo json_encode($answer);
}

function putRetos() {
	$request = \Slim\Slim::getInstance()->request();
	$req = json_decode($request->getBody());
	$sql = "UPDATE retos SET reto=:reto, imgReto=:imgReto, fechaInicio=:fechaInicio, no_registro=:no_registro, descripcion=:descripcion WHERE id_reto='$req->id_reto'";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("reto", $req->reto);
		$stmt->bindParam("imgReto", $req->imgReto);
		$stmt->bindParam("fechaInicio", $req->fechaInicio);
		$stmt->bindParam("no_registro", $req->no_registro);
		$stmt->bindParam("descripcion", $req->descripcion);
		
		$stmt->execute();
		$db = null;
		//validar mensaje de ok y error
		$answer = array('estatus'=>'ok', 'msj'=> 'reto modificado exitosamente');
		} 
		catch(PDOException $e) {
			if($e->errorInfo[1] == 1451){
				$answer = array( 'estatus'=>'error','msj' =>  'No es posible modificar este reto' );
			} else {
				$answer = array( 'estatus'=>'error','msj' =>  $e->getMessage());
			}
		}
		echo json_encode($answer);
}


function deleteRetos(){
	$request = \Slim\Slim::getInstance()->request();
	$pac = json_decode($request->getBody());
	$sql_query = "DELETE 
					FROM 
						retos
					WHERE 
						id_reto = '$pac->id_reto'";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql_query);
		$stmt->bindParam("id_reto", $pac->id_reto);
		$stmt->execute();
		$db = null;
		//validar mensaje de ok y error
		$answer = array('estatus'=>'ok', 'msj'=> 'reto eliminado exitosamente');
		} 
		catch(PDOException $e) {
			if($e->errorInfo[1] == 1451){
				$answer = array( 'estatus'=>'error','msj' =>  'No es posible eliminar este reto' );
			} else {
				$answer = array( 'estatus'=>'error','msj' =>  $e->getMessage());
			}
		}
		echo json_encode($answer);
}


?>
