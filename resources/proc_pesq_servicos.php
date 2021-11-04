<?php
include_once './conexao.php';

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "3911839_emerj";

$conn = mysqli_connect($servername, $username, $password, $dbname);

$draw = $requestData['draw'] ?? 1;

//Receber a requisão da pesquisa
$requestData= $_REQUEST;

//echo "<pre>";
//print_r($requestData);
//echo "</pre>";

//Indice da coluna na tabela visualizar resultado => nome da coluna no banco de dados
$columns = array(
	0 =>'servico_id',
	1 => 'servico_nm',
	2 => 'servico_des',
  3 => 'tipodeservico_nm'
);

//Obtendo registros de número total sem qualquer pesquisa
$result_user = "SELECT servico_id, servico_nm, servico_des, tipodeservico_nm FROM tb_servico, tb_tipodeservico WHERE tipodeservico_id = id_tipodeservico";
$resultado_user =mysqli_query($conn, $result_user);
$qnt_linhas = mysqli_num_rows($resultado_user);

//Obter os dados a serem apresentados
$result_usuarios = "SELECT servico_id, servico_nm, servico_des, tipodeservico_nm FROM tb_servico, tb_tipodeservico WHERE tipodeservico_id = id_tipodeservico";
if( !empty($requestData['search']['value']) ) {   // se houver um parâmetro de pesquisa, $requestData['search']['value'] contém o parâmetro de pesquisa
	$result_usuarios.=" AND servico_nm LIKE '".$requestData['search']['value']."%' ";
	$result_usuarios.=" OR servico_des LIKE '".$requestData['search']['value']."%' ";
}

$resultado_usuarios=mysqli_query($conn, $result_usuarios);
$totalFiltered = mysqli_num_rows($resultado_usuarios);
//Ordenar o resultado
if( !empty($requestData['order'][0]['column']) ) {
  $result_usuarios.=" ORDER BY ". $columns[$requestData['order'][0]['column']]."   ".$requestData['order'][0]['dir']."  LIMIT ".$requestData['start']." ,".$requestData['length']."   ";
  $resultado_usuarios=mysqli_query($conn, $result_usuarios);
}
// Ler e criar o array de dados
$dados = array();
while( $row_usuarios =mysqli_fetch_array($resultado_usuarios) ) {
	$dado = array();
	$dado[] = $row_usuarios["servico_nm"];
	$dado[] = $row_usuarios["servico_des"];
  $dado[] = $row_usuarios["tipodeservico_nm"];
	$dados[] = $dado;
}


//Cria o array de informações a serem retornadas para o Javascript
$json_data = array(
	"draw" => intval($draw),//para cada requisição é enviado um número como parâmetro
	"recordsTotal" => intval( $qnt_linhas ),  //Quantidade de registros que há no banco de dados
	"recordsFiltered" => intval( $totalFiltered ), //Total de registros quando houver pesquisa
	"data" => $dados   //Array de dados completo dos dados retornados da tabela
);

echo json_encode($json_data);  //enviar dados como formato json
