<?php
/**
 * Created by PhpStorm.
 * User: mt
 * Date: 02.04.17
 * Time: 10:05
 */

require( 'connectDB.php' );

$id = isset($_GET["id"]) ? $_GET["id"] : "";
$where = empty($id) ? "" : " WHERE c_id = " . $id;

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<!-- Required meta tags -->
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

	<!-- Bootstrap CSS -->
	<link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/font-awesome.min.css">

	<title>FH-Todo</title>

</head>
<body>
<div class="jumbotron jumbotron-fluid">

	<div class="container">
		<h1 class="display-3">FH-Todo</h1>
		<p class="lead">This is an awesome app where you can see your todos.</p>

		<div id="successWrapper" class="alert alert-success alert-dismissible fade" role="alert">
			<div class="contentWrapper">

			</div>
		</div>

	</div>
</div>

<div class="container">
	<!-- Content here -->

<?

$sqlCompanies    = "SELECT c_id, c_name FROM tbl_company" . $where;
$resultCompanies = $conn->query( $sqlCompanies );

if ( $resultCompanies->num_rows > 0 ) {

	while ( $rowCompanies = $resultCompanies->fetch_assoc() ) {

		echo '<h2>' . $rowCompanies['c_name'] . ' &#91;' . $rowCompanies["c_id"] . '&#93;</h2>';

		echo '<p>';

		echo '<button style="cursor:pointer;" class="btn btn-primary" type="button" data-toggle="collapse" data-target="#content'.$rowCompanies['c_id'].'" aria-expanded="false" aria-controls="content'.$rowCompanies['c_id'].'">';
			echo 'Show Users and Todos';
		echo '</button>';

		echo '<button data-company-id="'.$rowCompanies['c_id'].'" type="button" class="btn btn-danger startDockerMigration" style="margin-left: 10px;cursor: pointer;">Start Docker Migration</button>';

		echo '</p>';

		$sqlUsers    = "SELECT u_id, u_firstName, u_lastName, u_email FROM tbl_user WHERE fk_c_id = " . $rowCompanies['c_id'];
		$resultUsers = $conn->query( $sqlUsers );

		echo '<div class="collapse" id="content'.$rowCompanies['c_id'].'">';
		echo '<div class="card card-block">';

			if ( $resultUsers->num_rows > 0 ) {

				echo '<table class="table">';

				echo '<thead>';
					echo '<tr>';
						echo '<th style="width:30%">User</th>';
						echo '<th style="width: 70%">To-Do</th>';
					echo '</tr>';
				echo '</thead>';

				while ( $rowUsers = $resultUsers->fetch_assoc() ) {

					echo '<tbody>';

					echo '<tr>';

					echo '<td>';
					echo '<b>ID: </b>' . $rowUsers["u_id"];
					echo '<br/><b>Name: </b>' . $rowUsers["u_firstName"] . ' ' . $rowUsers["u_lastName"];
					echo '<br/><b>E-Mail: </b>' . $rowUsers["u_email"];
					echo '</td>';

					echo '<td>';

					$sqlTodos    = "SELECT t_id, t_name, t_description FROM tbl_todo WHERE t_deleted_at IS NULL AND fk_u_id = " . $rowUsers["u_id"];
					$resultTodos = $conn->query( $sqlTodos );

					if ( $resultTodos->num_rows > 0 ) {

						echo '<ol>';

						while ( $rowTodos = $resultTodos->fetch_assoc() ) {

							echo '<li>';
							echo $rowTodos["t_name"] . ' &#91;' . $rowTodos["t_id"] . '&#93;';

							echo '</br>' . $rowTodos["t_description"];

							$sqlComments    = "SELECT c_id, c_text FROM tbl_comment WHERE c_deleted_at IS NULL AND fk_t_id = " . $rowTodos["t_id"];
							$resultComments = $conn->query( $sqlComments );

							if ( $resultComments && $resultComments->num_rows > 0 ) {

							} else {
								echo "No comments found";
							}

							echo '</li>';

						}

						echo '</ol>';

					} else {
						echo "No todos found";
					}


					echo '</td>';

					echo '</tr>';

					echo '</tbody>';

				}

				echo '</table>';

			} else {
				echo "No Users found";
			}

			echo "</div>";
		echo "</div>";

	}

} else {
	echo "No Companies found";
}

$conn->close();

?>

</div>

<!-- jQuery first, then Tether, then Bootstrap JS. -->
<script src="js/jquery-3.1.1.slim.min.js"></script>
<script src="js/tether-1.4.0.min.js"></script>
<script src="js/bootstrap-4.0.0.min.js"></script>
<script src="js/functions.js"></script>

</body>
</html>