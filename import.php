<?php 

include_once 'conexao.php';

$fp = fopen("IMPOSTO_RENDA/participantes.csv", 'r');
$c = 0;
if ($fp) {
	$conn = conexao();
	/* disable autocommit */
	$conn->autocommit(FALSE);

	while ( ( $data = fgetcsv($fp) ) !== FALSE) {
		$email = $data[0]; 
		$nome = utf8_encode($data[1]);
		
		$sql = "INSERT INTO `certificado`.`aluno` (`idcurso`, `aluno_nome`, `aluno_email`) VALUES ('3', '$nome', '$email');";
		if ($conn->query($sql)) {
			$id = $conn->insert_id;
			$sql = "INSERT INTO `certificado`.`participante` (`idevento`, `idaluno`, `tipo`) VALUES ('3', '$id', 'participante');";
			if (!$conn->query($sql)) {
				echo "Erro inserindo participante no banco $nome $email <br>";	
				echo $conn->error;
				$conn->rollback();
			} else {
				echo "Inserido participante $nome <br>";
			}
		} else {
			echo "Erro inserindo aluno no banco $nome $email <br>";
			echo $conn->error;
			$conn->rollback();
		}	
	}
	$conn->commit();
	$conn->autocommit(TRUE);
	$conn->close();
	fclose($fp);
} else {
	echo "Arquivo não encontrado!";
}


// INSERT INTO `certificado`.`evento` (`evento_nome`, `data_inicio`, `carga_horaria`) VALUES ('Palestra Teori Zavascki', '2016-09-23', '5');

?>