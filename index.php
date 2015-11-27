<?php 
require 'Slim/Slim.php';
require 'Config/db.php';
require 'Model/Usuario.php';
require 'Model/Cliente.php';
require 'Model/Instructor.php';
require 'Model/Clase.php';
require 'Model/Reto.php';
require 'Model/Banner.php';
require 'Model/Pago.php';
require 'Model/AgendarClase.php';

\Slim\Slim::registerAutoloader(); 
$app = new \Slim\Slim(); 
header('Access-Control-Allow-Origin: *');

// Allow from any origin
    if (isset($_SERVER['HTTP_ORIGIN'])) {
        header("Access-Control-Allow-Origin: {$_SERVER['HTTP_ORIGIN']}");
        header('Access-Control-Allow-Credentials: true');
        header('Access-Control-Max-Age: 0');    // cache for 1 day
    }
    // Access-Control headers are received during OPTIONS requests
    if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_METHOD']))
            header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS");         

        if (isset($_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']))
            header("Access-Control-Allow-Headers: {$_SERVER['HTTP_ACCESS_CONTROL_REQUEST_HEADERS']}");
    }
function login() {
    $request = \Slim\Slim::getInstance()->request();
    $usuario = json_decode($request->getBody());

    $sql_query = "SELECT * FROM usuarios, clientes WHERE usuarios.correo = clientes.correo AND usuarios.correo = '$usuario->correo' AND usuarios.password = '$usuario->password'";
    try {
        $dbCon = getConnection();
        $stmt   = $dbCon->query($sql_query);
        $res  = $stmt->fetchAll(PDO::FETCH_OBJ);
        $dbCon = null;
        if(count($res)>0)
        {
            $res = $res[0];
            $answer = array('estatus' => 'ok' , 'msj'=>"¡Bienvenido al sistema $res->nombre $res->apellido!", 'usuario'=>$res, 'tipoUsuario'=> $res->tipoUsuario);
        } else {
            $answer = array('estatus' => 'ok' , 'msj'=>"Usuario y/o contraseñas incorrecta.");
        }
    } 
    catch(PDOException $e) {
        $answer = array('estatus'=>'error', 'msj' =>  $e->getMessage());
    }

    echo json_encode($answer);

}


function upload(){
    if ( !empty( $_FILES ) ) {
        $tempPath = $_FILES[ 'file' ][ 'tmp_name' ];
        $url = 'imagenes' . DIRECTORY_SEPARATOR . $_FILES[ 'file' ][ 'name' ];
        $uploadPath = dirname( __FILE__ ) . DIRECTORY_SEPARATOR . $url;
        move_uploaded_file( $tempPath, $uploadPath );
        $answer = array( 'url' => 'http://localhost:8080/gym.app/back/imagenes/' . $_FILES[ 'file' ][ 'name' ]);
        echo json_encode($answer);
    } else {
        $answer = array( 'error' => 'No se subio la imagen correctamente');
        echo json_encode($answer);
    }
}


$app->post('/upload','upload');

$app->post('/login','login');

$app->post('/usuarios','addUsuario');

$app->post('/clientes','addCliente');
$app->get('/getClientes','getClientes');
$app->get('/getCliente/:no_reg','getCliente'); 
$app->put('/putClientes','putClientes');
$app->delete('/deleteClientes','deleteClientes');
$app->delete('/deleteUsuarios','deleteUsuarios');

$app->post('/addInstructores','addInstructor');
$app->get('/getInstructores','getInstructores');
$app->put('/putInstructores','putInstructores');
$app->delete('/deleteInstructores','deleteInstructores');
//$app->delete('/deleteInstructores/:id','deleteInstructores');

$app->post('/addClases','addClase');
$app->get('/getClases','getClases');
$app->put('/putClases','putClases');
$app->delete('/deleteClases','deleteClases');

$app->post('/AgendarClase','AgendarClase');
$app->get('/getAgendarClase/:hora/:dia','getAgendarClase'); 
$app->delete('/EliminarClienteAgendado','EliminarClienteAgendado');


$app->get('/getBanner','getBanner');
$app->post('/addBanner','addBanner');
$app->put('/putBanner','putBanner');
$app->delete('/deleteBanner','deleteBanner');

$app->get('/getRetos','getRetos');
$app->post('/addRetos','addRetos');
$app->put('/putRetos','putRetos');
$app->delete('/deleteRetos','deleteRetos');

$app->get('/getPagos','getPagos');
$app->get('/getPago/:no_re','getPago');
$app->post('/addPagos','addPagos');
$app->put('/putPagos','putPagos');
$app->delete('/deletePagos','deletePagos');
$app->post('/pagoExistente','pagoExistente');


$app->run();

?>