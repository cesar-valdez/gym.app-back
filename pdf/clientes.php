<?php
require('mysql_table.php');
class PDF extends PDF_MySQL_Table
{
function Header()
{
	//Title
	$this->SetFont('Arial','',16);
	$this->Cell(0,6,'Lista de Clientes',0,1,'C');
	$this->Ln(10);
	//Ensure table header is output
	parent::Header();
}
}
//Connect to database
mysql_connect('localhost','root','');
mysql_select_db('gym');
$pdf=new PDF();
$pdf->AddPage();
//First table: put all columns automatically

$sql_query = "SELECT clientes.no_registro as No_registro,
						 clientes.nombre as Nombre,
						 clientes.apellido as Apellido,
						 clientes.celular as No_Celular,
						 clientes.peso as Peso,
						 clientes.estatura as Estatura,
						 clientes.edad as Edad,
						 clientes.domicilio as Domiclio
				FROM clientes";



$pdf->Table($sql_query);
$pdf->AddPage();

header('Content-type: clientes/pdf');
$pdf->Output('clientes'.".pdf", 'D'); 
?>