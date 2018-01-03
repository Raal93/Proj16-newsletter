<?php

	session_start();
	
	unset($_SESSION['loggedId']);
	header('Location: admin.php');