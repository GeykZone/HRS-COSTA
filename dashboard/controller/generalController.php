<?php

if (!isset($_COOKIE['hrsCostaToken'])) {  
    ?> <script>window.location.href = "../index.php";</script><?php
}
else if(isset($_COOKIE['hrsCostaToken'])) {
  $hrsCostaToken = $_COOKIE['hrsCostaToken'];
  // SQL query with INNER JOIN and WHERE clause
  $selectFbuserSql = "
  SELECT 
    u.Id AS userId,
    u.username,
    u.email,
    u.role,
    cff.fbUserId,
    at.token AS accessToken,
    at.expirationDate
  FROM 
    user AS u
  LEFT JOIN 
    created_from_facebook AS cff 
    ON u.Id = cff.userId
  LEFT JOIN 
    access_token AS at 
    ON u.Id = at.userId
  WHERE 
    at.token = ?
  ";
  
  $stmt = $conn->prepare($selectFbuserSql);
  $stmt->bind_param("s", $hrsCostaToken);
  getUserAccess($stmt);
}

?>