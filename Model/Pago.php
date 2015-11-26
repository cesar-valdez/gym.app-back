<?php

function getPagos() { 
	$sql_query = "SELECT pagos.id_pago as PagosId,
						 pagos.fechaPago as PagosFechaPago,
						 pagos.fechaPagado as PagosFechaPagado,
						 pagos.EstadoPagado as PagosEstado,
						 pagos.montoPagado as PagosMonto,
						 clientes.no_registro as ClientesRegistro,
						 clientes.nombre as ClientesNombre,
						 clientes.apellido as ClientesApellido
				FROM pagos, clientes
				WHERE
					pagos.no_registro = clientes.no_registro";
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


//para mostrar solo un pago
function getPago($no_re) { 
	$sql_query = "SELECT pagos.id_pago as PagosId,
						 pagos.fechaPago as PagosFechaPago,
						 pagos.fechaPagado as PagosFechaPagado,
						 pagos.EstadoPagado as PagosEstado,
						 pagos.montoPagado as PagosMonto,
						 clientes.no_registro as ClientesRegistro,
						 clientes.nombre as ClientesNombre,
						 clientes.apellido as ClientesApellido
				FROM pagos, clientes
				WHERE
					pagos.no_registro='$no_re' AND clientes.no_registro='$no_re'";
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



function addPagos() {
	$request = \Slim\Slim::getInstance()->request();
	$inst = json_decode($request->getBody());
	$sql = "INSERT INTO pagos (id_pago, fechaPago, fechaPagado, estadoPagado, montoPagado, no_registro) VALUES (:id_pago, :fechaPago, :fechaPagado, :estadoPagado, :montoPagado, :no_registro)";
	try {
		$db = getConnection(); 
		$stmt = $db->prepare($sql);
		$stmt->bindParam("id_pago", $inst->id_pago);
		$stmt->bindParam("fechaPago", $inst->fechaPago);
		$stmt->bindParam("fechaPagado", $inst->fechaPagado);
		$stmt->bindParam("estadoPagado", $inst->estadoPagado);
		$stmt->bindParam("montoPagado", $inst->montoPagado);
		$stmt->bindParam("no_registro", $inst->no_registro);
		$stmt->execute();
		$inst->id_pago = $db->lastInsertId();
		$db = null;
		echo json_encode($inst);
	} catch(PDOException $e) {
		$answer = array( 'error' =>  $e->getMessage());
		echo json_encode($answer);
	}
}


function putPagos() {
	$request = \Slim\Slim::getInstance()->request();
	$req = json_decode($request->getBody());
	$sql = "UPDATE pagos SET fechaPagado=:fechaPagado, estadoPagado=:estadoPagado, montoPagado=:montoPagado WHERE id_pago='$req->id_pago'";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$stmt->bindParam("fechaPagado", $req->fechaPagado);
		$stmt->bindParam("estadoPagado", $req->estadoPagado);
		$stmt->bindParam("montoPagado", $req->montoPagado);

		$stmt->execute();
		$db = null;
		echo json_encode($req);
	} catch(PDOException $e) {
		$answer = array( 'error' =>  $e->getMessage());
		echo json_encode($answer);
	}
}



function deletePagos(){
	$request = \Slim\Slim::getInstance()->request();
	$pac = json_decode($request->getBody());
	$sql_query = "DELETE 
					FROM 
						pagos
					WHERE 
						id_pago = '$pac->id_pago'";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql_query);
		$stmt->bindParam("id_pago", $pac->id_pago);
		$stmt->execute();
		$db = null;
		echo json_encode($pac);
	} 
	catch(PDOException $e) {
		$answer = array( 'error' =>  $e->getMessage());
		echo json_encode($answer);
	}
}


function pagoExistente() {
    $request = \Slim\Slim::getInstance()->request();
    $cliente = json_decode($request->getBody());

    $sql_query = "SELECT * FROM pagos WHERE no_registro = '$cliente->no_registro'";
    try {
        $dbCon = getConnection();
        $stmt   = $dbCon->query($sql_query);
        $res  = $stmt->fetchAll(PDO::FETCH_OBJ);
        $dbCon = null;
        if(count($res)>0){
			$answer = array( 'estatus' =>  true, 'fechaPago' =>  $res[count($res) - 1]->fechaPago );
            echo json_encode($answer);
        } else {
			$answer = array( 'estatus' =>  false);
            echo json_encode($answer);
        }
    } 
    catch(PDOException $e) {
        $answer = array( 'error' =>  $e->getMessage());
        echo json_encode($answer);
    }

}

?>



