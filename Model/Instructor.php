<?php

function getInstructores() { 
	$sql_query = "SELECT * FROM instructores";
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



function addInstructor() {
	$request = \Slim\Slim::getInstance()->request();
	$inst = json_decode($request->getBody());
	$sql = "INSERT INTO instructores (id_instructor, nombre, apellido, imgInstructor, correo, celular, peso, estatura, edad, domicilio, clase) VALUES (:id_instructor, :nombre, :apellido, :imgInstructor, :correo, :celular, :peso, :estatura, :edad, :domicilio, :clase)";
	try {
		$db = getConnection(); 
		$stmt = $db->prepare($sql);
		$stmt->bindParam("id_instructor", $inst->id_instructor);
		$stmt->bindParam("nombre", $inst->nombre);
		$stmt->bindParam("apellido", $inst->apellido);
		$stmt->bindParam("imgInstructor", $inst->imgInstructor);
		$stmt->bindParam("correo", $inst->correo);
		$stmt->bindParam("celular", $inst->celular);
		$stmt->bindParam("peso", $inst->peso);
		$stmt->bindParam("estatura", $inst->estatura);
		$stmt->bindParam("edad", $inst->edad);
		$stmt->bindParam("domicilio", $inst->domicilio);
		$stmt->bindParam("clase", $inst->clase);
		$stmt->execute();
		$inst->id_instructor = $db->lastInsertId();
		$db = null;
		//validar mensaje de ok y error
		$answer = array('estatus'=>'ok', 'msj'=> 'instructor agrgegado exitosamente');
		} 
		catch(PDOException $e) {
			if($e->errorInfo[1] == 1451){
				$answer = array( 'estatus'=>'error','msj' =>  'No es posible agregar este instructor' );
			} else {
				$answer = array( 'estatus'=>'error','msj' =>  $e->getMessage());
			}
		}
		echo json_encode($answer);
}


function putInstructores() {
	$request = \Slim\Slim::getInstance()->request();
	$req = json_decode($request->getBody());
	$sql = "UPDATE instructores SET nombre=:nombre, apellido=:apellido, imgInstructor=:imgInstructor, correo=:correo, celular=:celular, peso=:peso, estatura=:estatura, edad=:edad, domicilio=:domicilio, clase=:clase WHERE id_instructor='$req->id_instructor'";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("nombre", $req->nombre);
		$stmt->bindParam("apellido", $req->apellido);
		$stmt->bindParam("imgInstructor", $req->imgInstructor);
		$stmt->bindParam("correo", $req->correo);
		$stmt->bindParam("celular", $req->celular);
		$stmt->bindParam("peso", $req->peso);
		$stmt->bindParam("estatura", $req->estatura);
		$stmt->bindParam("edad", $req->edad);
		$stmt->bindParam("domicilio", $req->domicilio);
		$stmt->bindParam("clase", $req->clase);

		$stmt->execute();
		$db = null;
		//validar mensaje de ok y error
		$answer = array('estatus'=>'ok', 'msj'=> 'instructor modificado exitosamente');
		} 
		catch(PDOException $e) {
			if($e->errorInfo[1] == 1451){
				$answer = array( 'estatus'=>'error','msj' =>  'No es posible modificar los datos de este instructor' );
			} else {
				$answer = array( 'estatus'=>'error','msj' =>  $e->getMessage());
			}
		}
		echo json_encode($answer);
}



function deleteInstructores(){
	$request = \Slim\Slim::getInstance()->request();
	$pac = json_decode($request->getBody());
	$sql_query = "DELETE 
					FROM 
						instructores
					WHERE 
						id_instructor = '$pac->id_instructor'";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql_query);
		$stmt->bindParam("id_instructor", $pac->id_instructor);
		$stmt->execute();
		$db = null;
		/*$answer = array('estatus'=>'ok', 'msj'=> 'Cita eliminada satisfactoriamente');
	} 
	catch(PDOException $e) {
		$answer = array( 'estatus'=>'error','msj' =>  $e->getMessage());
	}
	echo json_encode($answer);*/
		$answer = array('estatus'=>'ok', 'msj'=> 'instructor eliminado exitosamente');
		} 
		catch(PDOException $e) {
			if($e->errorInfo[1] == 1451){
				$answer = array( 'estatus'=>'error','msj' =>  'No puedes eliminarlo, este instructor tiene una clase.' );
			} else {
				$answer = array( 'estatus'=>'error','msj' =>  $e->getMessage());
			}
		}
		echo json_encode($answer);
}


/*
function deleteInstructores($id){

	$sql_query = "DELETE 
					FROM 
						instructores
					WHERE 
						id_instructor = '$id'";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql_query);
		$stmt->bindParam("id_instructor", $id);
		$stmt->execute();
		$db = null;
		echo json_encode($pac);
	} 
	catch(PDOException $e) {
		$answer = array( 'error' =>  $e->getMessage());
		echo json_encode($answer);
	}
}*/


?>
