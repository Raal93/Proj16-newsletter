<?php

	session_start();

	if(isset($_POST['emailDelete']) && ($_POST['emailDelete'] != "")) {
		
		$emailDelete = filter_input(INPUT_POST, 'emailDelete', FILTER_VALIDATE_EMAIL);  // zwraca to samo jeśli email prawidlowy i false jeśli nie oraz NULL jesli pusty
	
		if(empty($emailDelete)){ // zmienna po przepuszczeniu jest false
			
			$_SESSION['emailError'] = true;
			$_SESSION['givenEmail'] = $_POST['emailDelete'];
			header('Location: save.php');
			exit();
			
			} else { // email zweryfikowany OK
				require_once 'database.php';
						//sprawdzam czy email jest już w bazie
				$queryEmail = $db -> prepare('SELECT id FROM users WHERE email = :emailDelete');
				$queryEmail -> bindValue(':emailDelete', $emailDelete, PDO::PARAM_STR);
				$queryEmail -> execute();
				$checkedEmail = $queryEmail -> fetch();
				
				if(empty($checkedEmail)) {  // podanego emaila nie ma w bazie
					
					$_SESSION['emailIsNotInDb'] = true;
					$_SESSION['givenEmail'] = $emailDelete;
					header('Location: save.php');
					exit();
					
				} else { // email jest w bazie
					
					$query = $db->prepare('DELETE FROM users WHERE email = :emailDelete');
					$query->bindValue(':emailDelete', $emailDelete, PDO::PARAM_STR);
					$query->execute();
					$emailDeleted = true;
				}
			}
	} else {	// empty form
	
	$_SESSION['emailNull'] = true;
	header('Location: save.php');
	exit();
}

?>
<!DOCTYPE html>
<html lang="pl">
<head>

    <meta charset="utf-8">
    <title>Zapisanie się do newslettera</title>
    <meta name="description" content="Używanie PDO - zapis do bazy MySQL">
    <meta name="keywords" content="php, kurs, PDO, połączenie, MySQL">

    <meta http-equiv="X-Ua-Compatible" content="IE=edge">

    <link rel="stylesheet" href="main.css">
    <link href="https://fonts.googleapis.com/css?family=Lobster|Open+Sans:400,700&amp;subset=latin-ext" rel="stylesheet">
    <!--[if lt IE 9]>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html5shiv/3.7.3/html5shiv.min.js"></script>
    <![endif]-->

</head>

<body>
	<div class="wrapper">
    <div class="container">

        <header>
            <h1>Newsletter</h1>
        </header>

        <main>
            <article>
                <?php if ($emailDeleted) echo "<p>Wypisałem adres " . $emailDelete . " z newslettera.</p>"?>
				<a href="index.php">Kliknij jeśli chcesz wrócić do formularza głównego</a>
            </article>
        </main>

    </div>
	</div>

</body>
</html>