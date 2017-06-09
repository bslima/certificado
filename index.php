<?php 
	session_start();
	include_once 'conexao.php'; 
	header('Content-Type: text/html; charset=UTF-8');
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Insira seus dados para imprimir seu certificado.</title>
	<link rel="stylesheet" type="text/css" href="bootstrap/css/bootstrap.min.css">

	<style type="text/css">
		
		.borda {
			border-radius: 5px;
			border-width: 1px;
			border-color: black;
			border-style: solid;
			margin: 10px;
		}
		

	</style>
</head>

<body>
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-5 borda">
				<div class="row">
					<div class="col-sm-offset-2 col-sm-8">
						<img class="img-thumbnail" style="margin-bottom: 20px;" src="logo.png">
					</div>
				</div>
				<div class="row">
					<div class="col-sm-8 col-sm-offset-2">
						<?php
							if (isset($_SESSION["erro"])) {
								$erro = $_SESSION["erro"];
								echo "<div class='alert alert-danger' role='alert'>$erro</div>";
								session_unset();
							}
						?>
					</div>
				</div>
				<div class="row">
					<form class="form-horizontal" method="POST" action="certificado.php">
						<div class="form-group">
							<label for="inputEmail" class="col-sm-2 control-label">E-mail</label>
							<div class="col-sm-8">
								<input type="email" name="email" id="inputEmail" 
									class="form-control" placeholder="Email">
							</div>
						</div>
						<div class="form-group">
							<label for="selectEvento" class="col-sm-2 control-label">Evento</label>
							<div class="col-sm-8">
								<select class="form-control" name="evento" id="selectEvento">
									<?php
										$conn = conexao();
										$sql = "SELECT evento_nome, idevento FROM evento;";			
										$res = $conn->query($sql);
										while ($row = $res->fetch_assoc()) {
											$id = $row["idevento"];
											$nome = $row["evento_nome"];
										    echo "<option value='$id'>$nome</option>";
										}
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
						    <div class="col-sm-offset-2 col-sm-10">
						      <button type="submit" class="btn btn-primary">Gerar Certificado!</button>
						    </div>
						 </div>
					</form>		
				</div>
			</div>
		</div>
	</div>

	<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
    <!-- Include all compiled plugins (below), or include individual files as needed -->
    <script src="bootstrap/js/bootstrap.min.js"></script>
</body>
</html>