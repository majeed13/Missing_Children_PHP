<?php
/**
 * Created by PhpStorm.
 * User: Mustafa, Anthony, Bo
 * Date: 6/01/2019
 * Time: 11:48 AM
 */
require_once 'config.inc.php';
// Get Customer Number
$id = $_GET['id'];
if ($id === "") {
    header('location: list_missing_children.php');
    exit();
}
if ($id === false) {
    header('location: list_missing_children.php');
    exit();
}
if ($id === null) {
    header('location: list_missing_children.php');
    exit();
}
?>
<html>
<head>
    <title>Missing Children Database</title>
    <link rel="stylesheet" href="base.css">
</head>
<body>
<?php
require_once 'header.inc.php';
?>
<div>
    <h2>Show Missing Children</h2>
    <?php

    // Create connection
    $conn = new mysqli($servername, $username, $password, $database, $port);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

	// Prepare SQL using Parameterized Form (Safe from SQL Injections)
    $sql = "SELECT P.firstName, P.lastName, P.sex, P.ethnicity, P.date, P.heightWhenMissingInches, P.mostRecentWeightLbs, P.eyeColor, P.hairColor, MP.contactAgency, MP.phoneNumber, MP.details FROM Person P " . "INNER JOIN Missing_Person_Event_MissingPerson MP ON P.personID = MP.personID WHERE P.personID = ?";
    $stmt = $conn->stmt_init();
    if (!$stmt->prepare($sql)) {
        echo "failed to prepare";
    }
    else {
		
		// Bind Parameters from User Input
        $stmt->bind_param('s',$id);
		
		// Execute the Statement
        $stmt->execute();
		
		// Process Results Using Cursor
        $stmt->bind_result($firstName,$lastName,$sex,$ethnicity,$date,$heightWhenMissingInches,$mostRecentWeightLbs,$eyeColor,$hairColor,$contactAgency,$phoneNumber,$details);
        echo "<div>";
        while ($stmt->fetch()) {
            echo '<a href="show_customer.php?id='  . $id . '">' . $firstName . '</a><br>' . $lastName . '<br>' . $sex . '<br>' . $ethnicity . '<br>' . $date . '<br>' . $heightWhenMissingInches . '<br>' . $mostRecentWeightLbs . '<br>' . $eyeColor . '<br>' . $hairColor . '<br>' . $contactAgency . '<br>' . $phoneNumber . '<br>' . $details;
        } 
        echo "</div>";
    ?>
        <div>
            <a href="update_customer.php?id=<?= $customerNumber ?>">Update Customer</a>
        </div>
    <?php
    }

    $conn->close();

    ?>
</>
</body>
</html>
