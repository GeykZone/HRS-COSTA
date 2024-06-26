<?php
// Check if the specified file exists
if (file_exists('../../connection/connect.php')) {
    include('../../connection/connect.php');
} else {
    include('../connection/connect.php');
}

$response = array();

// Retrieve JSON-encoded POST data
$inputData = json_decode(file_get_contents('php://input'), true);

// Function for inserting user details
function insertUser($userInsertSQL) {
    global $conn;
    global $response;
    try {
        if ($conn->query($userInsertSQL) === true) {
            return $conn->insert_id;
        }
        return false;
    } catch (Exception $e) {
        $response['insertUserError'] = "Error: " . $e->getMessage();
        return false;
    }
}

// Function for inserting token
function insertToken($tokenInsertSQL) {
    global $response;
    global $conn;
    try {
        if ($conn->query($tokenInsertSQL) === true) {
            return true;
        }
        return false;
    } catch (Exception $e) {
        $response['insertTokenError'] = "Error: " . $e->getMessage();
        return false;
    }
}

// set cookie
function userCookie($cookieName, $cookieValue, $expirationTime) {
    setcookie($cookieName, $cookieValue, $expirationTime, "/");
}

function getUserAccess($stmt) {

    global $response;
 
    // Execute the query
    $stmt->execute();
    $result = $stmt->get_result();
    
    // Check if there are any results
    if ($result->num_rows > 0) {
        // Loop through the results
        while ($row = $result->fetch_assoc()) {
            $response['userId'] = (isset($row['userId']) ? $row['userId'] : (isset($row['loginUser_Id']) ? $row['loginUser_Id'] : null));
            $response['username'] = (isset($row['username']) ? $row['username'] : null);
            $response['email'] = (isset($row['email']) ? $row['email'] : null);
            $response['fbUserId'] = (isset($row['fbUserId']) ? $row['fbUserId'] : null);
            $response['googleUserId'] = (isset($row['googleUserId']) ? $row['googleUserId'] : null);
            $response['accessToken'] = (isset($row['accessToken']) ? $row['accessToken'] : null);
            $response['role'] = (isset($row['role']) ? $row['role'] : null);
            $response['expirationDate'] = (isset($row['expirationDate']) ? $row['expirationDate'] : null);
        }
    } else {
        $response['notfound'] = true;
    }
}

// logout session
if (isset($inputData['logout'])) {
    $expirationTime = time() - 3600;// Calculate the expiration time for 30 days from now
    $cookieName = 'hrsCostaToken';
    $cookieValue = (isset($_COOKIE['hrsCostaToken']) ? $_COOKIE['hrsCostaToken'] : null);

    if($cookieValue !== null) {
        
        $selectUserTokenSql = "SELECT `Id` FROM `access_token` WHERE `token` = ?";
        $stmt = $conn->prepare($selectUserTokenSql);
        $stmt->bind_param("s", $cookieValue);
        getUserAccess($stmt);
    }

    if(!isset($response['notfound'])) {
        $deleteTokenSql = "DELETE FROM `access_token` WHERE `token` = ?";
        $stmt = $conn->prepare($deleteTokenSql);
        $stmt->bind_param("s", $cookieValue);
        $executionResult = $stmt->execute();

        if ($executionResult) {
            setcookie($cookieName, '', $expirationTime, "/");
            $response['logout'] = true ;
        } else {
            $response['ErrorDeletingToken: '] = $stmt->error ;
        }
    }
    
    echo json_encode($response);
}

// Manual sign-up
if (isset($inputData['manualSignUp'])) {
    $newEmail = $inputData['newEmail'];
    $newPassword = $inputData['newPassword'];
    $newRole = $inputData['newRole'];
    $newUserName = $inputData['newUserName'];
    $newAccessToken = $inputData['newAccessToken'];
    $expirationTime = time() + (30 * 86400);// Calculate the expiration time for 30 days from now
    $cookieName = 'hrsCostaToken';
    $cookieValue = $newAccessToken;
    $userTokenExpiry = date("Y-m-d", $expirationTime); // Format: Year-Month-Da

    $newHashedPassword = encrypt_decrypt('encrypt', $newPassword);

    // Use prepared statements to avoid SQL injection
    $userInsertSQL = "INSERT INTO `user`(`username`, `email`, `password`, `role`, `createdDate`, `lastModifiedDate`)
                     VALUES ('$newUserName', '$newEmail', '$newHashedPassword', '$newRole', NOW(), NOW())";
    $newlyCreatedUserId = insertUser($userInsertSQL);

    if ($newlyCreatedUserId) {
        // Insert token after user creation
        $tokenInsertSQL = "INSERT INTO `access_token`(`token`, `userId`,`expirationDate`) 
        VALUES ('$newAccessToken', '$newlyCreatedUserId','$userTokenExpiry')";

        if (insertToken($tokenInsertSQL)) {
            $response['userId'] = $newlyCreatedUserId;
            userCookie($cookieName, $cookieValue, $expirationTime); 
        }
    }
    // Return the response as JSON
    echo json_encode($response);
}

// Manual Login
if (isset($inputData['manualLogin'])) {
    $emailOrUsername = $inputData['emailOrUsername'];
    $password = $inputData['password'];
    $hashedPassword = encrypt_decrypt('encrypt', $password);
    $newAccessToken = $inputData['loginAccessToken'];
    $expirationTime = time() + (30 * 86400);// Calculate the expiration time for 30 days from now
    $cookieName = 'hrsCostaToken';
    $cookieValue = $newAccessToken;
    $userTokenExpiry = date("Y-m-d", $expirationTime); // Format: Year-Month-Da

    $selectUserTokenSql = "SELECT `loginUser`.`Id` AS `loginUser_Id` FROM `user` AS loginUser WHERE (`loginUser`.`username` = ? OR `loginUser`.`email` = ?) AND `loginUser`.`password` = ?";
    $stmt = $conn->prepare($selectUserTokenSql);
    $stmt->bind_param("sss", $emailOrUsername,$emailOrUsername,$hashedPassword);
    getUserAccess($stmt);

    if(!isset($response['notfound'])) {

        $userId = $response['userId'];
        // Insert token after user creation
        $tokenInsertSQL = "INSERT INTO `access_token`(`token`, `userId`,`expirationDate`) 
        VALUES ('$newAccessToken', '$userId','$userTokenExpiry')";

        if (insertToken($tokenInsertSQL)) {
            userCookie($cookieName, $cookieValue, $expirationTime); 
        }
    }
    // Return the response as JSON
    echo json_encode($response);
}

// Facebook sign-up
if (isset($inputData['fromFacebook'])) {
    $fbUserId = $inputData['fbUSerId'];
    $newAccessToken = $inputData['fbaAccessToken'];
    $newRole = $inputData['newRole'];
    $newUserName = $inputData['fbUsername'];
    $expirationTime = time() + (30 * 86400);// Calculate the expiration time for 30 days from now
    $cookieName = 'hrsCostaToken';
    $cookieValue = $newAccessToken;
    $userTokenExpiry = date("Y-m-d", $expirationTime); // Format: Year-Month-Day

    $selectFbuserSql = "SELECT `userId` FROM `created_from_facebook` WHERE `fbUserId` = ?";
    $stmt = $conn->prepare($selectFbuserSql);
    $stmt->bind_param("s", $fbUserId);
    getUserAccess($stmt);

    if(isset($response['notfound'])) {
        $newHashedPassword = encrypt_decrypt('encrypt', $fbUserId);
        $email = $inputData['fbuserEmail'];
        $userInsertSQL = "INSERT INTO `user`(`username`, `role`, `createdDate`, `lastModifiedDate`, `password`, `email`) 
        VALUES ('$newUserName', '$newRole', NOW(), NOW(), '$newHashedPassword', '$email')";
        $newlyCreatedUserId = insertUser($userInsertSQL);

        if ($newlyCreatedUserId) {
            $tokenInsertSQL = "INSERT INTO `access_token`(`token`, `userId`,`expirationDate`) 
            VALUES ('$newAccessToken', '$newlyCreatedUserId','$userTokenExpiry')";
    
            if (insertToken($tokenInsertSQL)) {
                
                $fbAccountInsertSQL = "INSERT INTO `created_from_facebook`(`fbUserId`, `userId`)
                VALUES ('$fbUserId', '$newlyCreatedUserId')";
                try {
                    $conn->query($fbAccountInsertSQL);
                    $response['fbloginStatus'] = $inputData['fbloginStatus'];
                    $response['userId']  = $newlyCreatedUserId;
                    userCookie($cookieName, $cookieValue, $expirationTime);
                } catch (Exception $e) {
                    $response['insertFbUserError'] = "Error: " . $e->getMessage();
                }
               
            }
        }

    }
    else {
        $response['facebookLoggedIn'] = true;

        $tokenInsertSQL = "INSERT INTO `access_token`(`token`, `userId`,`expirationDate`) VALUES ('$newAccessToken', '".$response['userId']."','$userTokenExpiry')";
        if(insertToken($tokenInsertSQL)) {
            $response['fbloginStatus'] = $inputData['fbloginStatus'];
            userCookie($cookieName, $cookieValue, $expirationTime); 
        }
    }

    // Return the response as JSON
    echo json_encode($response);
}

// google sign-up
if (isset($inputData['fromGoogle'])) {
    $googleUSerId = $inputData['googleUSerId'];
    $newAccessToken = $inputData['googleAccessToken'];
    $newRole = $inputData['newRole'];
    $newUserName = $inputData['googleUsername'];
    $expirationTime = time() + (30 * 86400);// Calculate the expiration time for 30 days from now
    $cookieName = 'hrsCostaToken';
    $cookieValue = $newAccessToken;
    $userTokenExpiry = date("Y-m-d", $expirationTime); // Format: Year-Month-Day

    $selectGoogleuserSql = "SELECT `userId` FROM `created_from_google` WHERE `googleUserId` = ?";
    $stmt = $conn->prepare($selectGoogleuserSql);
    $stmt->bind_param("s", $googleUSerId);
    getUserAccess($stmt);

    if(isset($response['notfound'])) {
        $newHashedPassword = encrypt_decrypt('encrypt', $googleUSerId);
        $email = $inputData['googleuserEmail'];
        $userInsertSQL = "INSERT INTO `user`(`username`, `role`, `createdDate`, `lastModifiedDate`, `password`, `email`) 
        VALUES ('$newUserName', '$newRole', NOW(), NOW(), '$newHashedPassword', '$email')";
        $newlyCreatedUserId = insertUser($userInsertSQL);

        if ($newlyCreatedUserId) {
            $tokenInsertSQL = "INSERT INTO `access_token`(`token`, `userId`,`expirationDate`) 
            VALUES ('$newAccessToken', '$newlyCreatedUserId','$userTokenExpiry')";

            if (insertToken($tokenInsertSQL)) {
                            
                $googleAccountInsertSQL = "INSERT INTO `created_from_google`(`googleUserId`, `userId`)
                VALUES ('$googleUSerId', '$newlyCreatedUserId')";
                try {
                    $conn->query($googleAccountInsertSQL);
                    $response['googleloginStatus'] = $inputData['googleloginStatus'];
                    $response['userId']  = $newlyCreatedUserId;
                    userCookie($cookieName, $cookieValue, $expirationTime);
                } catch (Exception $e) {
                    $response['insertgoogleUserError'] = "Error: " . $e->getMessage();
                }
            
            }
        }
    }
    else {
        $response['googleLoggedIn'] = true;

        $tokenInsertSQL = "INSERT INTO `access_token`(`token`, `userId`,`expirationDate`) VALUES ('$newAccessToken', '".$response['userId']."','$userTokenExpiry')";
        if(insertToken($tokenInsertSQL)) {
            $response['googleloginStatus'] = $inputData['googleloginStatus'];
            userCookie($cookieName, $cookieValue, $expirationTime); 
        }
    }

    // Return the response as JSON
    echo json_encode($response);
}
?>
