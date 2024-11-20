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

if (isset($inputData['submitRating']) && $inputData['submitRating'] === true) {
    // Capture input data
    $roomId = $inputData['roomId'] ?? null;
    $userId = $inputData['userId'] ?? null;
    $rating = $inputData['rating'] ?? null;

    // Validate required fields
    if ($roomId && $userId && $rating !== null) {
        // Insert rating into room_ratings table
        $insertRatingSql = "INSERT INTO `room_ratings` (`room_id`, `user_id`, `rating`) VALUES (?, ?, ?)";
        if ($stmt = $conn->prepare($insertRatingSql)) {
            $stmt->bind_param("iid", $roomId, $userId, $rating);
            if ($stmt->execute()) {
                // Fetch all ratings for the specified room
                $fetchRatingsSql = "SELECT AVG(`rating`) AS overall_rating FROM `room_ratings` WHERE `room_id` = ?";
                if ($fetchStmt = $conn->prepare($fetchRatingsSql)) {
                    $fetchStmt->bind_param("i", $roomId);
                    $fetchStmt->execute();
                    $result = $fetchStmt->get_result();
                    if ($result && $row = $result->fetch_assoc()) {
                        $overallRating = round($row['overall_rating'], 2);

                        // Update the room's overall rating
                        $updateRoomSql = "UPDATE `rooms` SET `overall_rating` = ? WHERE `Id` = ?";
                        if ($updateStmt = $conn->prepare($updateRoomSql)) {
                            $updateStmt->bind_param("di", $overallRating, $roomId);
                            if ($updateStmt->execute()) {
                                $response = [
                                    'status' => 'success',
                                    'message' => 'Rating added successfully and overall rating updated.',
                                    'roomId' => $roomId,
                                    'overall_rating' => $overallRating,
                                ];
                            } else {
                                $response = [
                                    'status' => 'error',
                                    'message' => 'Failed to update room overall rating: ' . $conn->error,
                                ];
                            }
                        } else {
                            $response = [
                                'status' => 'error',
                                'message' => 'Failed to prepare room update statement: ' . $conn->error,
                            ];
                        }
                    } else {
                        $response = [
                            'status' => 'error',
                            'message' => 'Failed to fetch overall ratings: ' . $conn->error,
                        ];
                    }
                } else {
                    $response = [
                        'status' => 'error',
                        'message' => 'Failed to prepare ratings fetch statement: ' . $conn->error,
                    ];
                }
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Failed to insert rating: ' . $stmt->error,
                ];
            }
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Failed to prepare insert statement: ' . $conn->error,
            ];
        }
    } else {
        $response = [
            'status' => 'error',
            'message' => 'Missing required fields or invalid data.',
        ];
    }

    // Output response as JSON
    echo json_encode($response);
}



?>