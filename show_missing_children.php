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
    if ($conn->connect_error) 
    {
        die("Connection failed: " . $conn->connect_error);
    }
    
    
    // print the image of the person
    $sql = "SELECT I.path FROM Person P " . "INNER JOIN Image I ON P.image = I.imageID WHERE P.personID = ?";
    
    $stmt = $conn->stmt_init();
    
    if (!$stmt->prepare($sql)) 
    {
        echo "failed to prepare";
    } else {
        // bind 
        $stmt->bind_param('s',$id);
        
        // execute
        $stmt->execute();
        
        // process result
        $stmt->bind_result( $imgPath );
       
        	while ( $stmt->fetch() )
        	{
        		if ( empty($imgPath) )
        			echo '[NO PICTURE AVAILABLE]';
        		else
        			echo "<img src='$imgPath' style='width:300px;height:300px;'><br>";
        	}
        
        
    }

	// Prepare SQL using Parameterized Form (Safe from SQL Injections)
    $sql = "SELECT P.personID, P.firstName, P.lastName, P.age, P.sex, P.ethnicity, P.date, P.heightWhenMissingInches, P.mostRecentWeightLbs, P.eyeColor, P.hairColor, MP.contactAgency, MP.phoneNumber, MP.details FROM Person P " . "INNER JOIN Missing_Person_Event_MissingPerson MP ON P.personID = MP.personID WHERE P.personID = ?";
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
        $stmt->bind_result($personID,$firstName,$lastName,$age,$sex,$ethnicity,$date,$heightWhenMissingInches,$mostRecentWeightLbs,$eyeColor,$hairColor,$contactAgency,$phoneNumber,$details);

        echo "<div>";
        while ($stmt->fetch()) {
            echo 'Name: ' . '<a href="show_missing_children.php?id='  . $personID . '">' . $firstName . ' ' . (empty($lastName) ? 'Info Not Available' : $lastName) . '</a>' .'<br>' . 'Age: ' . (empty($age) ? 'Info Not Available' : $age) . '<br>'. 'Sex: ' . $sex . '<br>' . 'Ethnicity: ' . (empty($ethnicity) ? 'Info Not Available' : $ethnicity) . '<br>' . 'Date of Birth: ' . (empty($date) ? 'Info Not Available' : $date) . '<br>' . 'Height when missing (inches): ' . (empty($heightWhenMissingInches) ? 'Info Not Available' : $heightWhenMissingInches) . '<br>' . 'Weight when missing (pounds): ' . (empty($mostRecentWeightLbs) ? 'Info Not Available' : $mostRecentWeightLbs) . '<br>' . 'Eye Color: ' . $eyeColor . '<br>' . 'Hair Color: ' . (empty($hairColor) ? 'Info Not Available' : $hairColor) . '<br>' . 'Contact Agency: ' . $contactAgency . '<br>' . 'Contact Phone Number: ' . $phoneNumber . '<br>' . 'Additional Details: ' . $details;
        } 
        echo "</div>";
    ?>
        <div>
            <a href="update_child.php?id=<?= $personID ?>">Update Child Age</a>
        </div>
    <?php
    }

    $conn->close();

    ?>
</>
</body>
</html>
