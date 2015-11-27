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

$sql_query = "SELECT clientes.no_registro as No_Reg,
					 clientes.nombre as Nombre,
					 clientes.apellido as Apellido,
					 agendarclase.horaAg as Hora,
					 agendarclase.diaAg as Dia,
					 clientes.celular as No_DeCelular,
					 clientes.edad as Edad,
					 clientes.domicilio as Domicilio
				FROM agendarclase, clientes
				WHERE
					agendarclase.no_registro = clientes.no_registro";



$pdf->Table($sql_query);
$pdf->AddPage();

header('Content-type: agendados/pdf');
$pdf->Output('agendados'.".pdf", 'D'); 
?>