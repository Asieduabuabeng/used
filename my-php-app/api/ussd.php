<?php
header("Content-type: text/plain");

// Database Credentials
$host = "localhost";
$db = "Mental Health USS";
$user = "root";
$pass = "";
//Create Connection
$conn = new mysqli($host, $user, $pass, $db);
//check connection
if ($conn->connect_error) {
    die("Connection Failed: " . $conn->connect_error);
}
// Reads the variables sent via POST
$sessionId   = $_POST["sessionId"];  
$serviceCode = $_POST["serviceCode"];  
$text = $_POST["text"];

//Explode the text to understand the user's response
$textArray = explode("*", $text);
$level = count($textArray);

//Initialize the response
$response = "";

//This is the first menu screen
if ( $text == "" ) {
$response  = "CON Hi welcome. Your mental health is a priority, don't be afraid to seek help  \n";
$response .= "1. Enter 1 to continue";
}
// Menu for a user who selects '1' from the first menu
// Will be brought to this second menu screen
else if ($textArray == "1") {
$response  = "CON  Why are you here today? \n";
$response .= "1. Emergency Support \n";
$response .= "2. Report a Case \n";
}
//Menu if user selects 1 which is Emergency contact
else if ($textArray == "1*1") {
$response = "CON Please call our emergency line: 0555487865 \n";
$response .= "1. Call Now \n";
$response .= "2. Main Menu \n";
}
else if ($textArray == "1*1*1") {
$response = "END Please dial 0555487865 from your phone to get immediate help. \n";
}
else if ($textArray == "1*1*2") {
$response = "CON Why are you here today? \n";
$response .= "1. Emergency Support \n";
$response .= "2. Report a Case \n";
}
//Menu if user selects 2 which is Report a case
else if ($textArray == "1*2") {
    if ($level == 1){
$response = "CON Enter name of victim: \n";
}
else if ($level == 2){
    $response = "CON Enter victim's phone number: \n";
}
else if ($level == 3){
    $response = "CON Enter victim's college: \n";
}
else if ($level == 4){
    $response = "CON Enter victim's department: \n";
}
else if ($level == 5){
    $response = "CON Enter victim's residence: \n";
}
else if ($level == 6){
    $response = "CON Describe the case. \n";
}

//Save information to database
$studentName = $textArray[1];
$phoneNumber = $textArray[2];
$college = $textArray[3];
$department = $textArray[4];
$residence = $textArray[5];

$stmt = $conn->prepare("INSERT INTO reports (phoneNumber, studentName, college, department, hostel) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $studentName, $phoneNumber, $college, $department, $residence);
if ($stmt->execute()) {
    $response = "END Thank you for rporting. We will get back to you shortly. \n";
}
else {
    $response = "END Error reporting case. Please try again later. \n";
}
$stmt->close();

} else {
    $response = "END Invalid Choice. \n";
}
$conn->close();

//Send response back to Africa's Talking
echo $response;
?>

