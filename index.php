<?php
/**
 * Created by PhpStorm.
 * User: mt
 * Date: 02.04.17
 * Time: 10:05
 */

require( 'connectDB.php' );

$sqlCompanies    = "SELECT c_id, c_name FROM tbl_company";
$resultCompanies = $conn->query( $sqlCompanies );

if ( $resultCompanies->num_rows > 0 ) {

	while ( $rowCompanies = $resultCompanies->fetch_assoc() ) {

		echo '<h2>' . $rowCompanies['c_name'] . ' &#91;' . $rowCompanies["c_id"] . '&#93;</h2>';

		$sqlUsers    = "SELECT u_id, u_firstName, u_lastName, u_email FROM tbl_user WHERE fk_c_id = " . $rowCompanies['c_id'];
		$resultUsers = $conn->query( $sqlUsers );

		if ( $resultUsers->num_rows > 0 ) {

			echo '<table border="1" width="100%">';

			echo '<tr>';
			echo '<th style="width:30%">User</th>';
			echo '<th style="width: 70%">To-Do</th>';
			echo '</tr>';

			while ( $rowUsers = $resultUsers->fetch_assoc() ) {

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

						if ( $resultComments->num_rows > 0 ) {

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

			}

			echo '</table>';

		} else {
			echo "No Users found";
		}

	}

} else {
	echo "No Companies found";
}

$conn->close();