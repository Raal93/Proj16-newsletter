<?php

	session_start();

if(isset($_POST['email']) && ($_POST['email'] != "")) {
	$email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);  // zwraca to samo jeśli email prawidlowy i false jeśli nie oraz NULL jesli pusty
	
	
		//sprawdzenie Recaptcha
		$secretKey = "6LcQGD4UAAAAAElvbPnpB6YWGoGNbTDzoLl8lzVG";
		$responseKey = $_POST['g-recaptcha-response'];
		$url = "https://www.google.com/recaptcha/api/siteverify?secret={$secretKey}&response={$responseKey}";
		
		$arrContextOptions=array("ssl"=>array("verify_peer"=>false,"verify_peer_name"=>false,),);
		$response = file_get_contents($url, false, stream_context_create($arrContextOptions));
		$response = json_decode($response); 
	
	if(empty($email)){ // zmienna jest pusta lub false
		
		$_SESSION['emailError'] = true;
		$_SESSION['givenEmail'] = $_POST['email'];
		header('Location: index.php');
		exit();
		
	} else if ($response -> success == false){ // błędne potwierdzenie recaptcha
		$_SESSION['errorRecaptcha'] = true;
		$_SESSION['givenEmail'] = $email;
		header('Location: index.php');
		exit(); 
		
	} else { // email zweryfikowany OK
		require_once 'database.php';
				//sprawdzam czy email jest już w bazie
		$queryEmail = $db -> prepare('SELECT id FROM users WHERE email = :email');
		$queryEmail -> bindValue(':email', $email, PDO::PARAM_STR);
		$queryEmail -> execute();
		$checkedEmail = $queryEmail -> fetch();
		
		if(empty($checkedEmail)) {  // podanego emaila nie ma jeszcze w bazie
			$query = $db->prepare('INSERT INTO users VALUES (NULL, :email)');
			$query->bindValue(':email', $email, PDO::PARAM_STR);
			$query->execute();
			$_SESSION['givenEmail'] = $_POST['email'];
		} else { // email jest już w bazie
			$_SESSION['emailIsInDb'] = true;
			$_SESSION['givenEmail'] = $email;
			header('Location: index.php');
			exit();
		}
	}
	
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
                <p>Dziękujemy za zapisanie się na listę mailową naszego newslettera!</p>
				<p>Aby zrezygnować z subskrypcji wpisz swój adres email, a następnie potwierdź wybór przyciskiem.</p>
				<form method="post" action="delete.php">
					<label>
						Podaj adres e-mail
						<input type="email" name="emailDelete" <?php if(isset($_SESSION['givenEmail'])) echo 'value = "' . $_SESSION['givenEmail'] . '"'?>>
					</label>
					<input type="submit" value="Zrezygnuj">
				</form>	
				<a href="index.php"></br>Kliknij aby wrócić do głównego formularza.</a>				
            </article>
        </main>

    </div>
	<div class="clear"></div>
	</div>
	<div class="emptyForm">
		<?php
						if(isset($_SESSION['emailIsNotInDb'])) {
							echo "Twojego adresu e-mail nie ma w bazie.</br>Nie jesteś zapisany.";
							unset($_SESSION['emailIsNotInDb']);
						}
						else if(isset($_SESSION['emailNull'])) {
							echo "Podaj mi adres e-mail abym mógł Cię wypisać.";
							unset($_SESSION['emailNull']);
						}
						else if(isset($_SESSION['emailError'])) {
							echo "Wydaje mi się, że to nie jest poprawny adres :/ Proszę, sprawdź to.";
							unset($_SESSION['emailError']);
						}		
		?>
	</div>

</body>
</html>