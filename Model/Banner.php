<?php

function getBanner() { 
	$sql_query = "SELECT * FROM banner";
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


function addBanner() {
	$request = \Slim\Slim::getInstance()->request();
	$inst = json_decode($request->getBody());
	$sql = "INSERT INTO banner (id_banner, imgBanner, anuncio) VALUES (:id_banner, :imgBanner, :anuncio)";
	try {
		$db = getConnection(); 
		$stmt = $db->prepare($sql);
		$stmt->bindParam("id_banner", $inst->id_banner);
		$stmt->bindParam("imgBanner", $inst->imgBanner);
		$stmt->bindParam("anuncio", $inst->anuncio);
		$stmt->execute();
		$inst->id_banner = $db->lastInsertId();
		$db = null;
		//validacion para mensaje ok y error
		$answer = array('estatus'=>'ok', 'msj'=> 'anuncio agregado exitosamente');
		} 
		catch(PDOException $e) {
			if($e->errorInfo[1] == 1451){
				$answer = array( 'estatus'=>'error','msj' =>  'No puedes agregar el anuncio' );
			} else {
				$answer = array( 'estatus'=>'error','msj' =>  $e->getMessage());
			}
		}
		echo json_encode($answer);
}


function putBanner() {
	$request = \Slim\Slim::getInstance()->request();
	$req = json_decode($request->getBody());
	$sql = "UPDATE banner SET imgBanner=:imgBanner, anuncio=:anuncio WHERE id_banner='$req->id_banner'";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("imgBanner", $req->imgBanner);
		$stmt->bindParam("anuncio", $req->anuncio);

		$stmt->execute();
		$db = null;
		//validacion con mensaje ok y error
		$answer = array('estatus'=>'ok', 'msj'=> 'anuncio modificado exitosamente');
		} 
		catch(PDOException $e) {
			if($e->errorInfo[1] == 1451){
				$answer = array( 'estatus'=>'error','msj' =>  'No es posible modificar el anuncio' );
			} else {
				$answer = array( 'estatus'=>'error','msj' =>  $e->getMessage());
			}
		}
		echo json_encode($answer);
}



function deleteBanner(){
	$request = \Slim\Slim::getInstance()->request();
	$pac = json_decode($request->getBody());
	$sql_query = "DELETE 
					FROM 
						banner
					WHERE 
						id_banner = '$pac->id_banner'";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql_query);
		$stmt->bindParam("id_banner", $pac->id_banner);
		$stmt->execute();
		$db = null;
		//validacion con mensaje ok y error
		$answer = array('estatus'=>'ok', 'msj'=> 'anuncio eliminado exitosamente');
		} 
		catch(PDOException $e) {
			if($e->errorInfo[1] == 1451){
				$answer = array( 'estatus'=>'error','msj' =>  'No puedes eliminar el anuncio' );
			} else {
				$answer = array( 'estatus'=>'error','msj' =>  $e->getMessage());
			}
		}
		echo json_encode($answer);
}

?>
