<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title><?php echo $page_title; ?></title>

    <!-- Bootstrap CSS -->
	<link href="../libs/js/bootstrap/dist/css/bootstrap.css" rel="stylesheet" media="screen" type="text/css"/>

	<!-- jquery ui css -->
	<link rel="stylesheet" href="./../libs/js/jquery-ui-1.11.4.custom/jquery-ui.min.css" type="text/css"/>
	<link rel="stylesheet" href="./../assets/style/main.css" type="text/css"/>

</head>
<body>

	<?php include_once "navigation.php"; ?>

    <!-- container -->
    <div class="container">

		<?php
		// show page header
		echo "<div class='page-header'>";
			echo "<h1>{$page_title}</h1>";
		echo "</div>";
		?>
