<?php
	session_start();
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="utf-8">
    <title>Newsletter - zapisz się!</title>
    <meta name="description" content="Używanie PDO - zapis do bazy MySQL">
    <meta name="keywords" content="php, kurs, PDO, połączenie, MySQL">
    <meta http-equiv="X-Ua-Compatible" content="IE=edge">

    <link rel="stylesheet" href="main.css">
    <link href="https://fonts.googleapis.com/css?family=Lobster|Open+Sans:400,700&amp;subset=latin-ext" rel="stylesheet">
	<script src='https://www.google.com/recaptcha/api.js'></script>
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
                <form method="post" action="save.php">
                    <label>Podaj adres e-mail
                        <input type="email" name="email" <?php if((isset($_SESSION['emailError'])) || (isset($_SESSION['emailIsInDb'])) || (isset($_SESSION['errorRecaptcha']))) echo 'value="' . $_SESSION['givenEmail'] . '"'; ?>>
                    </label>
                    <input type="submit" value="Zapisz się!">
					<div class="g-recaptcha" data-sitekey="6LcQGD4UAAAAAA74_qQAJiwlgl8xvau6z4id4EY0"></div>
                </form>
				<a href="save.php"></br>Kliknij aby zrezygnować z subskrypcji.</a>
            </article>
        </main>
    </div>
	<div class="navigationButton">
			<div class="top">
				<a href="admin.php"><img src="img/strzalka.jpg" class="strzalka"></a>
				<figcaption><a href="admin.php">Panel</br>admina</a></figcaption>
			</div>
	</div>
	<div class="clear"></div>
</div>
	<div class="emptyForm">
		<?php
						if(isset($_SESSION['emailIsInDb'])) {
							echo "Odnalazłem ten adres w swojej bazie, </br> jesteś już zapisany :)";
							unset($_SESSION['emailIsInDb']);
						}
						else if(isset($_SESSION['emailNull'])) {
							echo "Podaj mi adres e-mail abym mógł Cię zapisać :)";
							unset($_SESSION['emailNull']);
						}
						else if(isset($_SESSION['emailError'])) {
							echo "Wydaje mi się, że to nie jest poprawny adres :/ Proszę, sprawdź to.";
							unset($_SESSION['emailError']);
						} else if (isset($_SESSION['errorRecaptcha'])){
							echo "Coś z potwierdzeniem Captcha poszło nie tak :/ </br> Spróbuj jeszcze raz, proszę.";
							unset($_SESSION['errorRecaptcha']);
						}					
		?>
	</div>
</body>
</html>