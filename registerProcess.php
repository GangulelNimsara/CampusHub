<?php

require "includes/db.php";

$username = $_POST['username'] ?? '';
$firstName = $_POST['firstName'] ?? '';
$lastName = $_POST['lastName'] ?? '';
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirmPassword'] ?? '';
$email = $_POST['email'] ?? '';
$mobile = $_POST['mobile'] ?? '';

function generateStudentID(int $lastInsertedId, string $prefix = 'STU', int $paddingLength = 5): string {
    $currentYear = date("Y");
    $paddedSequence = str_pad($lastInsertedId, $paddingLength, '0', STR_PAD_LEFT);
    return "{$prefix}-{$currentYear}-{$paddedSequence}";
}

function invalidPassword($password){
    $uppercase = preg_match('@[A-Z]@', $password);
    $lowercase = preg_match('@[a-z]@', $password);
    $number = preg_match('@[0-9]@', $password);
    $specialChars = preg_match('@[^\w]@', $password);

    if(!$uppercase || !$lowercase || !$number || !$specialChars) {
        return true;
    } else {
        return false;
    }
}

if(empty($username) || empty($firstName) || empty($lastName) || empty($password) || empty($confirmPassword) || empty($email) || empty($mobile)){
    echo "Please fill all the fields";
}else if(empty($username)){
    echo "Please enter username";
}else if(strlen($username)>12){
    echo "Username should be less than 12 characters";
}else if(empty($firstName)){
    echo "Please enter first name";
}else if(strlen($firstName)>100){
    echo "First name should be less than 100 characters";
}else if(empty($lastName)){
    echo "Please enter last name";
}else if(strlen($lastName)>100){
    echo "Last name should be less than 100 characters";
}else if(empty($password)){
    echo "Please enter password";
}else if(strlen($password)<8){
    echo "Password should be at least 8 characters";
}else if(invalidPassword($password)){
    echo "Password should contain at least one uppercase letter, one lowercase letter, one number, and one special character";
}else if(empty($confirmPassword)){
    echo "Please confirm password";
}else if($password !== $confirmPassword){
    echo "Passwords do not match";
}else if(empty($email)){
    echo "Please enter email";
}else if(strlen($email)>150){
    echo "Email should be less than 150 characters";
}else if(filter_var($email, FILTER_VALIDATE_EMAIL) === false){
    echo "Please enter valid email";
}else if(empty($mobile)){
    echo "Please enter mobile number";
}else if(strlen($mobile)>20){
    echo "Mobile number should be less than 20 characters";
}else if(!preg_match("/^\+[1-9]\d{1,14}$/", $mobile)){
    echo "Please enter valid mobile number";
}else {
    $result = Database::search("SELECT * FROM `users` WHERE `username` = '".$username."' OR `email` = '".$email."' OR `mobile` = '".$mobile."'");
    if($result && $result->num_rows > 0){
        echo "Username, email, or mobile number already exists";
    }else{

        $date = new DateTime();
        $timeZone = new DateTimeZone("Asia/Colombo");
        $date->setTimezone($timeZone);
        $formattedDate = $date->format("Y-m-d");

        $idResult = Database::search("SELECT `id` FROM `users` ORDER BY `id` DESC LIMIT 1");
        $nextId = 1;
        if($idResult && $idResult->num_rows > 0){
            $lastRow = $idResult->fetch_assoc();
            $nextId = (int)$lastRow['id'] + 1;
        }

        $studentId = generateStudentID($nextId);

        Database::iud("INSERT INTO `users` (`status_id`, `studentId`, `username`, `first_name`, `last_name`, `email`, `mobile`, `password`, `joined_date`) VALUES ('1', '".$studentId."', '".$username."', '".$firstName."', '".$lastName."', '".$email."', '".$mobile."', '".$password."', '".$formattedDate."')");
        echo "success";
    }
}

?>