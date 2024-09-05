<?php
$currentDate = date("Y-m-d");

$searchTokenSql = "SELECT `Id`, `token`, `expirationDate` FROM `access_token` WHERE DATE(`expirationDate`) = ?";
$stmt = $conn->prepare($searchTokenSql);
$stmt->bind_param("s", $currentDate);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    
    $deleteSql = "DELETE FROM `access_token` WHERE DATE(`expirationDate`) = ?";
    $deleteStmt = $conn->prepare($deleteSql);

    if ($deleteStmt === false) {
        die("Failed to prepare the DELETE statement: " . $conn->error);
    }
    $deleteStmt->bind_param("s", $currentDate);
    $deleteStmt->execute();
} 

if (!isset($_COOKIE['hrsCostaToken'])) {  
    ?> <script>window.location.href = "../index.php";</script><?php
}
else if(isset($_COOKIE['hrsCostaToken'])) {
  $hrsCostaToken = $_COOKIE['hrsCostaToken'];
  // SQL query with INNER JOIN and WHERE clause
	$selectUserSql = "
	SELECT 
		u.Id AS userId,
		u.username,
		u.email,
		u.role,
		cff.fbUserId,
		cfg.googleUserId,
		at.token AS accessToken,
		at.expirationDate
	FROM 
		user AS u
	LEFT JOIN 
		created_from_facebook AS cff 
		ON u.Id = cff.userId
	LEFT JOIN 
		created_from_google AS cfg 
		ON u.Id = cfg.userId
	LEFT JOIN 
		access_token AS at 
		ON u.Id = at.userId
	WHERE 
		at.token = ?
	";
	
  $stmt = $conn->prepare($selectUserSql);
  $stmt->bind_param("s", $hrsCostaToken);
  getUserAccess($stmt);

  if(isset($response['notfound'])) {
	$expirationTime = time() - 3600;
    $cookieName = 'hrsCostaToken';
	setcookie($cookieName, '', $expirationTime, "/");
	?> <script>window.location.href = "../index.php";</script><?php
  }
}

?>