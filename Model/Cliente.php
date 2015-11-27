<?php
//para mostrar todos los clientes
function getClientes() { 
	$sql_query = "SELECT usuarios.correo as UsuariosCorreo,
						usuarios.password as UsuariosPassword,
						usuarios.tipoUsuario as UsuariosTipoUsuario,
						clientes.no_registro as ClientesNoRegistro,
						clientes.nombre as ClientesNombre,
						clientes.apellido as ClientesApellido,
						clientes.imgCliente as ClientesImgCliente,
						clientes.celular as ClientesCelular,
						clientes.peso as ClientesPeso,
						clientes.estatura as ClientesEstatura,
						clientes.edad as ClientesEdad,
						clientes.domicilio as ClientesDomicilio,
						clientes.correo as ClientesCorreo
				 FROM clientes, usuarios
				 WHERE usuarios.correo = clientes.correo AND usuarios.tipoUsuario='usuario'";

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


/*function getClientes() { 
	$sql_query = "SELECT *
				 FROM clientes";

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
}*/



//para mostrar solo un cliente
function getCliente($no_reg){
	$sql_query = "SELECT *
					FROM 
						clientes, usuarios
					WHERE 
						clientes.correo = usuarios.correo AND no_registro= '$no_reg'";
	try {
		$dbCon = getConnection();
		$stmt   = $dbCon->query($sql_query);
		$data  = $stmt->fetchAll(PDO::FETCH_OBJ);
		$dbCon = null;
		$client = $data[0];
		echo json_encode($client);
	} 
	catch(PDOException $e) {
		$answer = array( 'error' =>  $e->getMessage());
		echo json_encode($answer);
	}
}

function addCliente() {
	$request = \Slim\Slim::getInstance()->request();
	$client = json_decode($request->getBody());
	$sql = "INSERT INTO usuarios (correo, password, tipoUsuario) VALUES (:correo, :password, :tipoUsuario)";
	try {
		$db = getConnection(); 
		$stmt = $db->prepare($sql);
		$stmt->bindParam("correo", $client->correo);
		$stmt->bindParam("password", $client->password);
		$stmt->bindParam("tipoUsuario", $client->tipoUsuario);
		$stmt->execute();

		$sql = "INSERT INTO clientes (nombre, apellido, imgCliente, celular, peso, estatura, edad, domicilio, correo) VALUES (:nombre, :apellido, :imgCliente, :celular, :peso, :estatura, :edad, :domicilio, :correo)";
			try {
				$db = getConnection(); 
				$stmt = $db->prepare($sql);
				$stmt->bindParam("nombre", $client->nombre);
				$stmt->bindParam("apellido", $client->apellido);
				$stmt->bindParam("imgCliente", $client->imgCliente);
				$stmt->bindParam("celular", $client->celular);
				$stmt->bindParam("peso", $client->peso);
				$stmt->bindParam("estatura", $client->estatura);
				$stmt->bindParam("edad", $client->edad);
				$stmt->bindParam("domicilio", $client->domicilio);
				$stmt->bindParam("correo", $client->correo);
				$stmt->execute();
				$db = null;
				$answer = array('estatus' => 'ok' , 'msj'=>'Usuario registrado.' );
			} catch(PDOException $e) {
				$answer = array( 'error' =>  $e->getMessage());
			}
	} catch(PDOException $e) {
		$answer = array('estatus'=>'error', 'msj'=>'Usuario ya registrado con ese correo');
	}
		echo json_encode($answer);
}

function putClientes() {
	$request = \Slim\Slim::getInstance()->request();
	$req = json_decode($request->getBody());

	$sql = "UPDATE usuarios SET password=:password WHERE correo='$req->correo'";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("password", $req->password);
		$stmt->execute();
		$db = null;
			$sql = "UPDATE clientes SET nombre=:nombre, apellido=:apellido, imgCliente=:imgCliente, celular=:celular, peso=:peso, estatura=:estatura, edad=:edad, domicilio=:domicilio, correo=:correo WHERE no_registro='$req->no_registro'";
			try {
				$db = getConnection();
				$stmt = $db->prepare($sql);
				$stmt->bindParam("nombre", $req->nombre);
				$stmt->bindParam("apellido", $req->apellido);
				$stmt->bindParam("imgCliente", $req->imgCliente);
				$stmt->bindParam("celular", $req->celular);
				$stmt->bindParam("peso", $req->peso);
				$stmt->bindParam("estatura", $req->estatura);
				$stmt->bindParam("edad", $req->edad);
				$stmt->bindParam("domicilio", $req->domicilio);
				$stmt->bindParam("correo", $req->correo);

				$stmt->execute();
				$db = null;
				echo json_encode($req);
			} catch(PDOException $e) {
				$answer = array( 'error' =>  $e->getMessage());
				echo json_encode($answer);
			}
	} catch(PDOException $e) {
		$answer = array( 'error' =>  $e->getMessage());
		echo json_encode($answer);
	}

}



function deleteClientes(){
	$request = \Slim\Slim::getInstance()->request();
	$pac = json_decode($request->getBody());
	$sql_query = "DELETE 
					FROM 
						clientes
					WHERE 
						no_registro = '$pac->no_registro'";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql_query);
		$stmt->bindParam("no_registro", $pac->no_registro);
		$stmt->execute();
		$db = null;
		//validar mensaje de ok y error
		$answer = array('estatus'=>'ok', 'msj'=> ' usuario eliminado exitosamente');
		} 
		catch(PDOException $e) {
			if($e->errorInfo[1] == 1451){
				$answer = array( 'estatus'=>'error','msj' =>  'No es posible eliminar el usuario, el usuario esta en una clase o tiene un pago por hacer' );
			} else {
				$answer = array( 'estatus'=>'error','msj' =>  $e->getMessage());
			}
		}
		echo json_encode($answer);
}

function deleteUsuarios(){
	$request = \Slim\Slim::getInstance()->request();
	$pac = json_decode($request->getBody());
	$sql_query = "DELETE 
					FROM 
						usuarios
					WHERE 
						correo = '$pac->correo'";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql_query);
		$stmt->bindParam("correo", $pac->correo);
		$stmt->execute();
		$db = null;
		//validar mensaje de ok y error
		$answer = array('estatus'=>'ok', 'msj'=> ' usuario eliminado exitosamente');
		} 
		catch(PDOException $e) {
			if($e->errorInfo[1] == 1451){
				$answer = array( 'estatus'=>'error','msj' =>  'No es posible eliminar el usuario, el usuario esta en una clase o tiene un pago por hacer' );
			} else {
				$answer = array( 'estatus'=>'error','msj' =>  $e->getMessage());
			}
		}
		echo json_encode($answer);
}

?>

