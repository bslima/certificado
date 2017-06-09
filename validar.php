<?php 

include_once 'conexao.php';
header('Content-Type: text/html; charset=utf-8');

if (!isset($_GET['cd'])) {
	die("Certificado não encontrado!");
}

$conn = conexao();
$id = $_GET["cd"];

$sql = "SELECT aluno_nome, evento_nome, data_inicio, data_fim,carga_horaria  FROM evento ".
		"INNER JOIN participante ON evento.idevento = participante.idevento ".
		"INNER JOIN aluno on participante.idaluno = aluno.idaluno ".
		"WHERE participante.idparticipantes = '$id'";

$res = $conn->query($sql);

$row = $res->fetch_assoc();

if (!isset($row['aluno_nome'])) {
	die("Certificado não encontrado!");
} else {
	echo "<h2>Dados do Certificado<h2><br>";
	echo "<p>Nome: ".$row['aluno_nome']."</p>";
	echo "<p>Evento: ".$row['evento_nome']."</p>";
	echo "<p>Data: ".date_format(date_create($row['data_inicio']), "j/m/Y")."</p>";
	echo "<p>Carga horária: ".$row['carga_horaria']."</p>";
	echo "<p>OBS: Verifique se os dados no certificado estão de acordo com os dados mostrados.</p>";
}



?>