<?php
/**
 * Created by PhpStorm.
 * User: MKochanski
 * Date: 7/24/2018
 * Time: 3:07 PM
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
    <h2>Update Child Age</h2>
    <?php

    // Create connection
    $conn = new mysqli($servername, $username, $password, $database, $port);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

	// Check the Request is an Update from User -- Submitted via Form
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $age = $_POST['Age'];
        if ($age === null)
            echo "<div><i>Specify a new name 1</i></div>";
        else if ($age === false)
            echo "<div><i>Specify a new name 2</i></div>";
        else if (trim($age) === "")
            echo "<div><i>Specify a new age 3</i></div>";
        else {
			
            /* perform update using safe parameterized sql */
            $sql = "UPDATE Person SET age = ? WHERE personID = ?";
            $stmt = $conn->stmt_init();
            if (!$stmt->prepare($sql)) {
                echo "failed to prepare";
            } else {
				
				// Bind user input to statement
                $stmt->bind_param('ss', $age,$id);
				
				// Execute statement and commit transaction
                $stmt->execute();
                $conn->commit();
            }
        }
    }

    /* Refresh the Data */
    //$sql = "SELECT CustomerNumber,CustomerName,StreetAddress,CityName,StateCode,PostalCode FROM customer C " .
      //  "INNER JOIN address A ON C.defaultAddressID = A.addressID WHERE CustomerNumber = ?";
    $sql = "SELECT P.personID, P.firstName, P.lastName, P.age, P.sex, P.ethnicity, P.date, P.heightWhenMissingInches, P.mostRecentWeightLbs, P.eyeColor, P.hairColor, MP.contactAgency, MP.phoneNumber, MP.details FROM Person P " . "INNER JOIN Missing_Person_Event_MissingPerson MP ON P.personID = MP.personID WHERE P.personID = ?";
    $stmt = $conn->stmt_init();
    if (!$stmt->prepare($sql)) {
        echo "failed to prepare";
    }
    else {
        $stmt->bind_param('s',$id);
        $stmt->execute();
        $stmt->bind_result($personID,$firstName,$lastName,$age,$sex,$ethnicity,$date,$heightWhenMissingInches,$mostRecentWeightLbs,$eyeColor,$hairColor,$contactAgency,$phoneNumber,$details);
        if ($heightWhenMissingInches === null) {
            $heightWhenMissingInches = "Info Not Available";
        }
        if ($mostRecentWeightLbs === null) {
            $mostRecentWeightLbs = "Info Not Available";
        }
        ?>
        <form method="post">
            <input type="hidden" name="id" value="<?= $id ?>">
        <?php
        while ($stmt->fetch()) {
            echo 'Name: ' . '<a href="show_missing_children.php?id='  . $personID . '">' . $firstName . ' ' . $lastName . '</a>' .'<br>' . 'Age: ' . $age . '<br>'. 'Sex: ' . $sex . '<br>' . 'Ethnicity: ' . $ethnicity . '<br>' . 'Date of Birth: ' . $date . '<br>' . 'Height when missing (inches): ' . $heightWhenMissingInches . '<br>' . 'Weight when missing (pounds): ' . $mostRecentWeightLbs . '<br>' . 'Eye Color: ' . $eyeColor . '<br>' . 'Hair Color: ' . $hairColor . '<br>' . 'Contact Agency: ' . $contactAgency . '<br>' . 'Contact Phone Number: ' . $phoneNumber . '<br>' . 'Additional Details: ' . $details;
        }
    ?><br><br>
            New Age: <input type="text" name="Age">
            <button type="submit">Update</button>
        </form>
    <?php
    }

    $conn->close();

    ?>
</>
</body>
</html>
