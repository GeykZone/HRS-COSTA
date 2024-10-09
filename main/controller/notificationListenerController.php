<?php 
include ('dynamicFunctions.php');

$date = new DateTime();
$currentDate = $date->format('Y-m-d');

// listen for notification
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['listening'])) {

        $userId = isset($_POST['userId']) ? $_POST['userId'] : null;
    
        // Query to get new rows from the check_ins table where notificationStatus is 'unread' and status is 'pending'
        $query = "SELECT ci.Id AS checkInId, 
                  ci.roomId AS checkInRoomId, 
                  ci.checkInDate AS checkInCheckInDate, 
                  ci.checkOutDate AS checkInCheckOutDate, 
                  ci.userId AS checkInUserId, 
                  ci.queueDateTime AS checkInQueueDateTime, 
                  ci.status AS checkInStatus, 
                  ci.checkInQuantity AS checkInQuantity, 
                  ci.paymentMethodId AS checkInPaymentMethodId, 
                  ci.totalAmount AS checkInTotalAmount, 
                  ci.customerfullName AS checkInCustomerFullName, 
                  ci.customerCompleteAddress AS checkInCustomerCompleteAddress, 
                  ci.customerContactInfo AS checkInCustomerContactInfo,
                  ci.isPartial AS isPartial,
                  ci.partialPayment AS partialPayment,
                  r.name AS roomName,
                  u.userName As username
                  FROM check_ins AS ci
                  LEFT JOIN rooms AS r
                  ON ci.roomId = r.Id
                  LEFT JOIN user AS u
                  ON ci.userId = u.Id
                  WHERE notificationStatus = 'unread' AND multiBookId = '0' ";

        if ($userId) {
            $query .= " AND userId = '$userId'  AND (status = 'approved' OR status = 'rejected')";
        }
        else {
            $query .= "AND ( status = 'pending')";
        }
    
        $query .= " ORDER BY queueDateTime DESC"; // Adjust the limit as needed
    
        $result = $conn->query($query);
    
        $newRows = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $newRows[] = $row;
            }
        }


        //for multi booking
        // Query to get new rows from the check_ins table where notificationStatus is 'unread' and status is 'pending'
        $query = "SELECT ci.Id AS checkInId, 
                  ci.roomId AS checkInRoomId, 
                  ci.checkInDate AS checkInCheckInDate, 
                  ci.checkOutDate AS checkInCheckOutDate, 
                  ci.userId AS checkInUserId, 
                  ci.queueDateTime AS checkInQueueDateTime, 
                  ci.status AS checkInStatus, 
                  ci.checkInQuantity AS checkInQuantity, 
                  ci.paymentMethodId AS checkInPaymentMethodId, 
                  ci.totalAmount AS checkInTotalAmount, 
                  ci.customerfullName AS checkInCustomerFullName, 
                  ci.customerCompleteAddress AS checkInCustomerCompleteAddress, 
                  ci.customerContactInfo AS checkInCustomerContactInfo,
                  ci.multiBookId AS multiBookId,
                  ci.isPartial AS isPartial,
                  ci.partialPayment AS partialPayment,
                  mb.totalAmount As multibookTotalAmount,
                  mb.partialPayment As multiBookPartialPayment,
                  GROUP_CONCAT(DISTINCT r.name ORDER BY r.name SEPARATOR ', ') AS roomName,
                  GROUP_CONCAT(DISTINCT CONCAT(r.name, ' ', ci.checkInQuantity, ' Total Quantit') ORDER BY r.name SEPARATOR ', ') AS roomNameQuantity,
                  SUM(ci.checkInQuantity) AS totalQuantity,
                  u.userName As username
                  FROM check_ins AS ci
                  LEFT JOIN rooms AS r
                  ON ci.roomId = r.Id
                  LEFT JOIN multibook AS mb
                  ON ci.multiBookId = mb.Id
                  LEFT JOIN user AS u
                  ON ci.userId = u.Id
                  WHERE notificationStatus = 'unread' AND multiBookId != '0' ";

        if ($userId) {
            $query .= " AND userId = '$userId'  AND (status = 'approved' OR status = 'rejected')";
        }
        else {
            $query .= "AND ( status = 'pending')";
        }
    
        $query .= "GROUP BY ci.multiBookId ORDER BY queueDateTime DESC"; // Adjust the limit as needed
    
        $result = $conn->query($query);
    
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
        'latestModifiedDate' => $currentDate
    ];

    $conditions = [
        'id' => $ReservationCheckInId
    ];

    if (updateRecord($table, $data, $conditions, $conn)) {
       $response['approved'] = true;
    } else {

        $conditions = [
            'multiBookId' => $ReservationCheckInId
        ];

        if (updateRecord($table, $data, $conditions, $conn)){
            $response['approved'] = true;
        }
        else {
            $response['approved'] = false;
        }
        
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
        'message' => $rejectionReason,
        'latestModifiedDate' => $currentDate
    ];

    $conditions = [
        'id' => $ReservationCheckInId
    ];

    if (updateRecord($table, $data, $conditions, $conn)) {
       $response['rejected'] = true;
    } else {
        $conditions = [
            'multiBookId' => $ReservationCheckInId
        ];

        if (updateRecord($table, $data, $conditions, $conn)){
            $response['rejected'] = true;
        }
        else {
            $response['approved'] = false;
        }
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

        $conditions = [
            'multiBookId' => $ReservationCheckInId
        ];

        if (updateRecord($table, $data, $conditions, $conn)){
            $response['read'] = true;
        }
        else {
            $response['read'] = false;
        }
       
    }


   echo json_encode($response);
}

?>