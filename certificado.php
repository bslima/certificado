<?php 
	session_start();
	include_once 'conexao.php'; 
	header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
	<link href="https://fonts.googleapis.com/css?family=Trocchi" rel="stylesheet">
	<style type="text/css">
		
		
		body {
		    height: 100%;
		    width: 1200px;
		    border-style: solid;
		    border-color: black;
		    border-width: 2px;
		}

		.container {
			position: relative;
		    width: 100%;
		    display: block;
		}

		.content {
			margin: auto;
			width: 30%;
		}

		.logo {
			padding-top: 20px;
			width: 300px;
			height: 90px;
			
		}

		.texto {
			font-family: 'Trocchi', serif;
		}
		.certificado {
			font-size: 40px;
		}
		.descricao {
			word-wrap: break-word;
			font-size: 30px;
			margin-left: 100px;
			width: 80%
		}
		.cargo, .nome {
			font-weight: bold;
		}
		.data {
			position: absolute;
			right: 50%;
		}
		.ass {
			display: flex;
			margin-left: 100px;
			position: relative;
		}

		.qrcode {
			float: left;
		}
		
		.rodape {
			background-color: #b53346;
			width: 1200px;
			height: 20px;
		}

		@media print
		{    
		    .no-print, .no-print *
		    {
		        display: none !important;
		        width: 200px;
		        height: 200px;
		    }
		}

	</style>
</head>
<body>

<?php
$conn = conexao();
$aluno = $_POST["email"];
$evento = $_POST["evento"];

// $aluno = "bslima19@gmail.com";
// $evento = 1;

$sql = "SELECT idparticipantes, aluno_nome, evento_nome, data_inicio, data_fim,carga_horaria  FROM evento ".
		"INNER JOIN participante ON evento.idevento = participante.idevento ".
		"INNER JOIN aluno on participante.idaluno = aluno.idaluno ".
		"WHERE aluno.aluno_email = '$aluno' AND evento.idevento = $evento";

$res = $conn->query($sql);


$row = $res->fetch_assoc();

if (!isset($row['aluno_nome'])) {
	$_SESSION['erro'] = "E-mail não encontrado na base de dados para o evento selecionado!";
	header("location: index.php");
}

?>	

	<div class="container">
		<div class="content">
			<img class="logo" src="logo.png">
			<p class="texto certificado">
				CERTIFICADO
			</p>
	    </div>
	    <p class="texto descricao">
				<?php
				$nome = $row['aluno_nome'];
				$evento = $row['evento_nome'];
				$data_inicio = $row['data_inicio'];
				$data_inicio = date_create($data_inicio);
				$data_inicio = date_format($data_inicio, "d/m/Y");
				$carga_horaria = $row['carga_horaria'];
				if (isset($row['data_fim'])) {
					$data_fim = $row['data_fim'];
					$data_fim = date_create($data_fim);
					$data_fim = date_format($data_fim, "d/m/Y");
					$texto_data = "entre os dias $data_inicio e $data_fim";
				} else {
					$texto_data = "no dia $data_inicio";
				}
				$texto = "Certificamos que $nome 
				participou do $evento realizado
				$texto_data, nesta Instituição de Ensino Superior,
				com carga horária de $carga_horaria horas.";
				echo $texto;
				?>
		</p>	
	</div>
	<div class="texto data">
		São Luis, 
		<?php 
			$date = date_create(); 
			echo date_format($date, "d/m/Y");
		?>
	</div>
	<br/>
	<br/>
	<br/>
	<div class="ass">
		<div class="ass1">
				<img src="assinatura.png">
		</div>
		<div class="qrcode">
				<?php
					$url = "http://" . $_SERVER['SERVER_NAME'] . "/certificado/validar.php?cd=".$row['idparticipantes'];
				?>
				<img src="https://chart.googleapis.com/chart?chs=200x200&cht=qr&chl="<?php echo $url;?>"&choe=UTF-8" title="Validação" />
				<p>
				Certifique validade: <a href="<?php echo $url ?>"> <?php echo $url ?> </a> </p> 
		</div>
	</div>
	<div class="rodape">
		
	</div>
	<button class="no-print" onclick="window.print();">IMPRIMIR</button>

</body>
</html>