<?php
/**
 * Created by PhpStorm.
 * User: Mustafa
 * Date: 6/01/2019
 * Time: 11:48 AM
 */
require_once 'config.inc.php';

?>
<html>
<head>
    <title>Sample PHP Database Program</title>
    <link rel="stylesheet" href="base.css">
</head>
<body>
<?php
require_once 'header.inc.php';
?>
<div>
    <h2>Customer List</h2>
    <?php
    // Create connection
    $conn = new mysqli($servername, $username, $password, $database, $port);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

	// Prepare SQL Statement
    $sql = "SELECT P.personID, P.firstName, P.lastName FROM Person P INNER JOIN Missing_Person_Event_MissingPerson MP on P.personID = MP.personID ORDER BY P.lastName";
    $stmt = $conn->stmt_init();
    if (!$stmt->prepare($sql)) {
        echo "failed to prepare";
    }
    else {
		
		// Execute the Statement
        $stmt->execute();
		
		// Loop Through Result
        $stmt->bind_result($personID,$firstName,$lastName);
        echo "<ul>";
        while ($stmt->fetch()) {
            echo '<li><a href="show_missing_children.php?id='  . $personID . '">' . $firstName . " " . $lastName . '</a></li>';
        }
        echo "</ul>";
    }

	// Close Connection
    $conn->close();

    ?>
</div>
</body>
</html>
