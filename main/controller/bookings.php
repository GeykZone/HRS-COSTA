<?php
include ('roomsController.php');

if(isset($inputData['openBooking'])){
    $checkinId = $inputData['checkinId'];

    $selectCheckInsSql = "
        SELECT
        Id, isPartial, partialPayment
        FROM check_ins
        WHERE Id = ?
    ";
    
    if ($stmt = $conn->prepare($selectCheckInsSql)) {
        $stmt->bind_param('i', $checkinId);
        
        if ($stmt->execute()) {
            // Bind result variables
            $stmt->bind_result($id, $isPartial, $partialPayment);
            
            // Fetch the result
            if ($stmt->fetch()) {
                $response['Id'] = $id;
                $response['isPartial'] = $isPartial;
                $response['partialPayment'] = $partialPayment;
            } else {
                // If no results found
                $response['error'] = 'No matching records found.';
            }
        } else {
            $response['error'] = 'Failed to execute the query.';
        }
        
        $stmt->close();
    } else {
        $response['error'] = 'Failed to prepare the SQL statement.';
    }
    
    echo json_encode($response);
    
}

?>