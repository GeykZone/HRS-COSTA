<?php
include('connect.php');
$response = array();

// Retrieve JSON-encoded POST data
$inputData = json_decode(file_get_contents('php://input'), true);

//manual sign-up
if (isset($inputData['manualSignUp'])) {
    $newEmail = $inputData['newEmail'];
    $newPassword = $inputData['newPassword'];
    $newRole = $inputData['newRole'];
    $newUserName = $inputData['newUserName'];
    $newAccessToken = $inputData['newAccessToken'];
    $newHashedPassword = encrypt_decrypt('encrypt', $newPassword);

    // Insert into 'user' table
    $userInsertSQL = "INSERT INTO `user`(`username`, `email`, `password`, `role`, `createdDate`, `lastModifiedDate`)
                     VALUES ('$newUserName', '$newEmail', '$newHashedPassword', '$newRole', NOW(), NOW())";

    try {
        if ($conn->query($userInsertSQL) === TRUE) {
            $lastInsertId = $conn->insert_id;
            $response['message'] = 'User created successfully';
            $response['userId'] = $lastInsertId;

            // Insert into 'access_token' table
            $tokenInsertSQL = "INSERT INTO `access_token`(`token`, `userId`) 
                               VALUES ('$newAccessToken', '$lastInsertId')";

            if ($conn->query($tokenInsertSQL) !== TRUE) {
                throw new Exception("Error inserting access token");
            }
        } else {
            throw new Exception("Error inserting user");
        }
    } catch (Exception $e) {
        $response['error'] = "Error: " . $e->getMessage();
    }
}

echo json_encode($response);
?>
