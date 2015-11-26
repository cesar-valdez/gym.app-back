<?php

function addUsuario() {
	$request = \Slim\Slim::getInstance()->request();
	$user = json_decode($request->getBody());
	$sql = "INSERT INTO usuarios (correo, password, tipoUsuario) VALUES (:correo, :password, :tipoUsuario)";
	try {
		$db = getConnection(); 
		$stmt = $db->prepare($sql);
		$stmt->bindParam("correo", $user->correo);
		$stmt->bindParam("password", $user->password);
		$stmt->bindParam("tipoUsuario", $user->tipoUsuario);
		$stmt->execute();
		$db = null;
		echo json_encode($user);
	} catch(PDOException $e) {
		$answer = array( 'error' =>  $e->getMessage());
		echo json_encode($answer);
	}
}

?>