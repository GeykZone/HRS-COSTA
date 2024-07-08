<?php 
include ('dynamicFunctions.php');
// listen for notification
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['listening'])) {

        $userId = isset($_POST['userId']) ? $_POST['userId'] : null;
    
        // Query to get new rows from the check_ins table where notificationStatus is 'unread' and status is 'pending'
        $query = "SELECT ci.Id AS checkInId, 
                  ci.roomId AS checkInRoomId, 
                  ci.checkInDate AS checkInCheckInDate, 
                  ci.checkOutDate AS checkInCheckOutDate, 
                  ci.paidAmount AS checkInPaidAmount,
                  ci.userId AS checkInUserId, 
                  ci.queueDateTime AS checkInQueueDateTime, 
                  ci.status AS checkInStatus, 
                  ci.checkInQuantity AS checkInQuantity, 
                  ci.paymentMethodId AS checkInPaymentMethodId, 
                  ci.totalAmount AS checkInTotalAmount, 
                  ci.customerfullName AS checkInCustomerFullName, 
                  ci.customerCompleteAddress AS checkInCustomerCompleteAddress, 
                  ci.customerContactInfo AS checkInCustomerContactInfo,
                  r.name AS roomName,
                  u.userName As username
                  FROM check_ins AS ci
                  LEFT JOIN rooms AS r
                  ON ci.roomId = r.Id
                  LEFT JOIN user AS u
                  ON ci.userId = u.Id
                  WHERE notificationStatus = 'unread'";

        if ($userId) {
            $query .= " AND userId = '$userId'  AND (status = 'approved' OR status = 'rejected')";
        }
        else {
            $query .= "AND ( status = 'pending')";
        }
    
        $query .= " ORDER BY queueDateTime DESC LIMIT 10"; // Adjust the limit as needed
    
        $result = $conn->query($query);
    
        $newRows = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $newRows[] = $row;
            }
        }
    
        // Return the results as a JSON response
        echo json_encode(['newRows' => $newRows, 'rowCount' => count($newRows)]);
        exit();
}

// Check if the $response variable is already defined
if (!isset($response)) {
    $response = array();
}
// Check if the $inputData variable is already defined
if (!isset($inputData)) {
    $inputData = json_decode(file_get_contents('php://input'), true);
}

if (isset($inputData['approve'])) {
    global  $conn;
    $ReservationCheckInId = $inputData['ReservationCheckInId'];

    $table = "check_ins";

    $data = [
        'status' => 'approved',
    ];

    $conditions = [
        'id' => $ReservationCheckInId
    ];

    if (updateRecord($table, $data, $conditions, $conn)) {
       $response['approved'] = true;
    } else {
       $response['approved'] = false;
    }


   echo json_encode($response);
}

if (isset($inputData['reject'])) {
    global  $conn;
    $ReservationCheckInId = $inputData['ReservationCheckInId'];
    $rejectionReason = $inputData['rejectionReason'];

    $table = "check_ins";

    $data = [
        'status' => 'rejected',
        'message' => $rejectionReason
    ];

    $conditions = [
        'id' => $ReservationCheckInId
    ];

    if (updateRecord($table, $data, $conditions, $conn)) {
       $response['rejected'] = true;
    } else {
       $response['rejected'] = false;
    }


   echo json_encode($response);
}

if (isset($inputData['markAsRead'])) {
    global  $conn;
    $ReservationCheckInId = $inputData['ReservationCheckInId'];

    $table = "check_ins";

    $data = [
        'notificationStatus' => 'read',
    ];

    $conditions = [
        'id' => $ReservationCheckInId
    ];

    if (updateRecord($table, $data, $conditions, $conn)) {
       $response['read'] = true;
    } else {
       $response['read'] = false;
    }


   echo json_encode($response);
}

?>