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
        if ($customerName === null)
            echo "<div><i>Specify a new name</i></div>";
        else if ($age === false)
            echo "<div><i>Specify a new name</i></div>";
        else if (trim($age) === "")
            echo "<div><i>Specify a new name</i></div>";
        else {
			
            /* perform update using safe parameterized sql */
            $sql = "UPDATE Person SET Age = ? WHERE CustomerNumber = ?";
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
    $sql = "SELECT P.personID, P.firstName, P.lastName, P.sex, P.ethnicity, P.date, P.heightWhenMissingInches, P.mostRecentWeightLbs, P.eyeColor, P.hairColor, MP.contactAgency, MP.phoneNumber, MP.details FROM Person P " . "INNER JOIN Missing_Person_Event_MissingPerson MP ON P.personID = MP.personID WHERE P.personID = ?";
    $stmt = $conn->stmt_init();
    if (!$stmt->prepare($sql)) {
        echo "failed to prepare";
    }
    else {
        $stmt->bind_param('s',$id);
        $stmt->execute();
        $stmt->bind_result($customerNumber,$customerName,$streetName,$cityName,$stateCode,$postalCode);
        ?>
        <form method="post">
            <input type="hidden" name="id" value="<?= $id ?>">
        <?php
        while ($stmt->fetch()) {
            echo '<a href="show_customer.php?id='  . $customerNumber . '">' . $customerName . '</a><br>' .
             $streetName . ',' . $stateCode . '  ' . $postalCode;
        }
    ?><br><br>
            New Name: <input type="text" name="customerName">
            <button type="submit">Update</button>
        </form>
    <?php
    }

    $conn->close();

    ?>
</>
</body>
</html>
