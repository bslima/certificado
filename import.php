<?php 

include_once 'conexao.php';
header('Content-Type: text/html; charset=UTF-8');

$fp = fopen("import/JORNADA_ODONTO/participantes.csv", 'r');
if ($fp) {
	$conn = conexao();
	/* disable autocommit */
	$conn->autocommit(FALSE);
	
	//Prepara os statments
	$sql_aluno = "INSERT INTO `certificado`.`aluno` (`idcurso`, `aluno_nome`, `aluno_email`) VALUES (?, ?, ?);";
	$insert_aluno = $conn->prepare($sql_aluno);

	$sql_certificado = "INSERT INTO `certificado`.`participante` (`idevento`, `idaluno`, `tipo`) VALUES (?, ?, ?);";
	$insert_certificado = $conn->prepare($sql_certificado);

	//Dados para os inserts

	$idcurso = 4; 
	$idevento = 4;
	$idaluno = null;
	$tipo = "participante";
	$existe = false;

	while ( ( $data = fgetcsv($fp) ) !== FALSE) {
		$email = $data[0]; 
		$nome = utf8_encode($data[1]);
		
		//verificar se já existe um aluno com esse email
		$sql = "SELECT idaluno FROM aluno where aluno_email = '".$email."'";
		$res = $conn->query($sql);
		$row = $res->fetch_assoc();
		
		//Aluno já existe no bacno
		if(isset($row["idaluno"])) {
			$idaluno = $row["idaluno"];
			$existe = true;
		} else {
			//aluno não existe, insere no banco
			$insert_aluno->bind_param("iss",$idcurso,$nome,$email);
			if( $insert_aluno->execute() ) {
				$idaluno = $conn->insert_id;	
			} else {
				echo $conn->error;
				$insert_aluno->close();
				$conn->rollback();
				$conn->close();
				die("Erro inserindo participante no banco $nome $email <br>");
			}			
		}

		$res->close();

		if($existe) {
			//verificar se já existe um aluno com esse email
			$sql = "SELECT idparticipantes FROM participante where idaluno = $idaluno and idevento = $idevento;";
			$res = $conn->query($sql);
			$row = $res->fetch_assoc();
			if (!isset($row["idparticipantes"])) {
				$existe = false;
			}
			$res->close();
		}

		//Certificado não existe na base, pode inserir
		if (!$existe) {
			//Insere na tabela de certificados
			$insert_certificado->bind_param("iis",$idevento,$idaluno,$tipo);
			if (!$insert_certificado->execute()) {	
				echo $conn->error;
				$insert_certificado->close();
				$conn->rollback();
				$conn->close();
				die("Erro inserindo participante no banco $nome $email <br>");
			} else {
				echo "Inserido participante $nome <br>";
			}	
		} else {
			echo "Aluno $nome já existe nesse evento!</br>";
		}

		

	}
	$conn->commit();
	$conn->autocommit(TRUE);
	$insert_certificado->close();
	$insert_aluno->close();
	$conn->close();
	fclose($fp);
} else {
	echo "Arquivo não encontrado!";
}

?>