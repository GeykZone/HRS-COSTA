<?php
// Check if the $response variable is already defined
if (!isset($response)) {
    $response = array();
}
// Check if the $inputData variable is already defined
if (!isset($inputData)) {
    $inputData = json_decode(file_get_contents('php://input'), true);
}

include ('dynamicFunctions.php');

// save all the room details
if (isset($inputData['saveRoomDetails'])) {

    $imageLink = $inputData['imageLink'];
    $otherRates = $inputData['otherRates'];
    $amenities = $inputData['amenities'];
    $roomName = $inputData['roomName'];
    $roomDescription = $inputData['roomDescription'];
    $maximumCapacity = $inputData['maximumCapacity'];
    $publishedRate = $inputData['publishedRate'];
    $roomQuantity = $inputData['roomQuantity'];
    $response['roomInserted'] = false;

    $roomsInsertSQL = "INSERT INTO `rooms`( `name`, `maximum`, `description`, `originalRate`, `quantity`) VALUES ('$roomName','$maximumCapacity','$roomDescription','$publishedRate', '$roomQuantity')";
    $newlyCreatedRooms = dynamicInsert($roomsInsertSQL, 1);

    if ($newlyCreatedRooms) {
        $response['roomId'] = $newlyCreatedRooms;
        $response['roomName'] = $roomName;
        $newImages = @insertRoomImages($newlyCreatedRooms, $imageLink);

        if($newImages) {
            $response['imagesId'] = $newImages;
            $response['roomInserted'] = true;
        }

        if($otherRates) {
            $newAddedRates = @insertOtherRates($newlyCreatedRooms, $otherRates);
            $response['roomInserted'] = false;
            if($newAddedRates) {
                $response['otherRateId'] = $newAddedRates;
                $response['roomInserted'] = true;
            }
        }

        if($amenities) {
            $newAddedAmenities = @insertAmenities($newlyCreatedRooms, $amenities);
            $response['roomInserted'] = false;
            if($newAddedAmenities) {
                $response['amenitiesId'] = $newAddedAmenities;
                $response['roomInserted'] = true;
            }
        }

    }

    // $response['otherRates'] =  $otherRates;

    echo json_encode($response);
}

// send reservation request for single booking
if (isset($inputData['sendReservationRequest'])) {
    $singleBookingimageLink = $inputData['singleBookingimageLink'];
    $quantity = $inputData['quantity'];
    $paidAmount =  $inputData['paidAmount'];
    $totalAmount = $inputData['totalAmount'];
    $roomId = $inputData['roomId'];
    $userId =  $inputData['userId'];
    $queueDateTime =  $inputData['queueDateTime'];
    $paymentMethod =  $inputData['paymentMethod'];
    $checkInDate = $inputData['checkInDate'];
    $checkOutDate = $inputData['checkOutDate'];
    $customerfullName = $inputData['customerfullName'];
    $customerCompleteAddress = $inputData['customerCompleteAddress'];
    $customerContactInfo = $inputData['customerContactInfo'];
    $isPartialValue = $inputData['isPartialValue'];
    
    $selectRoomsSql = " SELECT r.Id AS Id, r.quantity AS quantity,
        IFNULL(totalCheckIn.totalCheckInQuantity, 0) AS totalCheckInQuantity
        FROM `rooms` AS r
        LEFT JOIN (
            SELECT 
                roomId, 
                SUM(checkInQuantity) AS totalCheckInQuantity 
            FROM 
                check_ins 
            WHERE 
                status IN ('Pending', 'Approved')
                AND (
                    DATE(checkInDate) <= ? AND DATE(checkOutDate) >= ? -- Overlapping with start date
                    OR DATE(checkInDate) <= ? AND DATE(checkOutDate) >= ? -- Overlapping with end date
                    OR DATE(checkInDate) >= ? AND DATE(checkOutDate) <= ? -- Completely within the date range
                )
            GROUP BY roomId
        ) AS totalCheckIn 
        ON r.Id = totalCheckIn.roomId
        WHERE Id= ? AND (r.quantity - IFNULL(totalCheckIn.totalCheckInQuantity, 0))>=? ";

    if ($stmt = $conn->prepare($selectRoomsSql)) {
        $stmt->bind_param('ssssssii', $checkOutDate, $checkInDate, $checkOutDate, $checkInDate, $checkInDate, $checkOutDate, $roomId, $quantity);
        roomQuery($stmt);

        if(isset($response['notfound']) && $response['notfound'] === true) {
            $response['error'] = 'The entered room quantity exceeds the available amount, or the quantity has changed. Please refresh the page and try again. Thank you.';
        }
        else {
            if(@getPaymentMethod($paymentMethod)) {

                $pmId = @getPaymentMethod($paymentMethod);
                $reservationInsertSQL = "INSERT INTO `check_ins`(`roomId`, `checkInDate`, `checkOutDate`, `userId`, `queueDateTime`, `status`, `checkInQuantity`, `paymentMethodId`, `totalAmount`, `customerfullName`, `customerCompleteAddress`, `customerContactInfo`,`isPartial`,`partialPayment`) 
                VALUES ('$roomId','$checkInDate','$checkOutDate','$userId','$queueDateTime','Pending','$quantity','$pmId','$totalAmount','$customerfullName','$customerCompleteAddress','$customerContactInfo','$isPartialValue','$paidAmount')";
                $reservationSent = dynamicInsert($reservationInsertSQL, 1);

                if($reservationSent) {

                    if($singleBookingimageLink) {
                        $saveEvidence = @insertPaymentEvidence($reservationSent, $singleBookingimageLink);
                    }

                    $response['reserve'] = 'success';
                   
                }
            }
        }

    } else {
        $response['error'] = 'Failed to prepare the SQL statement.';
    }

    echo json_encode($response);
}

// send reservation request for multi booking
if (isset($inputData['sendReservationRequestMultiBooking'])) {

    $roomList = $inputData['roomList'];
    $multiBookTotalAmount = $inputData['totalAmount'];
    $userId = $inputData['userId'];
    $queueDateTime =  $inputData['queueDateTime'];
    $paymentMethod =  $inputData['paymentMethod'];
    $checkInDate = $inputData['checkInDate'];
    $checkOutDate = $inputData['checkOutDate'];
    $customerfullName = $inputData['customerfullName'];
    $customerCompleteAddress = $inputData['customerCompleteAddress'];
    $customerContactInfo = $inputData['customerContactInfo'];
    $multiBookingimageLink = $inputData['multiBookingimageLink'];
    $isPartial = $inputData['isPartialValue'];
    $MultiBookpartialPayment = $inputData['partialPayment'];
    $allocatedPartials = $inputData['allocatedPartials'];
    $isBookingValid = true;

    foreach ($roomList as $room) {
        $roomId =  $room['id'] ;
        $quantity = $room['quantity'];

        $selectRoomsSql = " SELECT r.Id AS Id, r.quantity AS quantity,
        IFNULL(totalCheckIn.totalCheckInQuantity, 0) AS totalCheckInQuantity
        FROM `rooms` AS r
        LEFT JOIN (
            SELECT 
                roomId, 
                SUM(checkInQuantity) AS totalCheckInQuantity 
            FROM 
                check_ins 
            WHERE 
                status IN ('Pending', 'Approved')
                AND (
                    DATE(checkInDate) <= ? AND DATE(checkOutDate) >= ? -- Overlapping with start date
                    OR DATE(checkInDate) <= ? AND DATE(checkOutDate) >= ? -- Overlapping with end date
                    OR DATE(checkInDate) >= ? AND DATE(checkOutDate) <= ? -- Completely within the date range
                )
            GROUP BY roomId
        ) AS totalCheckIn 
        ON r.Id = totalCheckIn.roomId
        WHERE Id= ? AND (r.quantity - IFNULL(totalCheckIn.totalCheckInQuantity, 0))>=? ";

        if ($stmt = $conn->prepare($selectRoomsSql)) {
            $stmt->bind_param('ssssssii', $checkOutDate, $checkInDate, $checkOutDate, $checkInDate, $checkInDate, $checkOutDate, $roomId, $quantity);
            roomQuery($stmt);

            if(isset($response['notfound']) && $response['notfound'] === true) {
                $response['error'] = 'The room quantity has changed. Please refresh the page and try again. Thank you.';
                $isBookingValid = false;
                break;
            }

        } else {
            $response['error'] = 'Failed to prepare the SQL statement.';
        }

    }

    if($isBookingValid){
        $multiBookInsertQuery = "INSERT INTO `multibook`(`totalAmount`, `partialPayment`) VALUES ('$multiBookTotalAmount','$MultiBookpartialPayment')";
        $multiBookInsertion = dynamicInsert($multiBookInsertQuery, 1);
        
        if($multiBookInsertion) {
            $multiBookId = $multiBookInsertion;
            foreach ($roomList as $room) {
                $roomId =  $room['id'] ;
                $quantity = $room['quantity'];
                $totalAmount = $room['totalPayable'];
                $eachPartial = $allocatedPartials * $quantity;
                
                if(@getPaymentMethod($paymentMethod)) {

                    $pmId = @getPaymentMethod($paymentMethod);
                    $MultiReservationInsertSQL = "INSERT INTO `check_ins`(`roomId`, `checkInDate`, `checkOutDate`, `userId`, `queueDateTime`, `status`, `checkInQuantity`, `paymentMethodId`, `totalAmount`, `customerfullName`, `customerCompleteAddress`, `customerContactInfo`, `multiBookId`, `isPartial`, `partialPayment` ) 
                    VALUES ('$roomId','$checkInDate','$checkOutDate','$userId','$queueDateTime','Pending','$quantity','$pmId','$totalAmount','$customerfullName','$customerCompleteAddress','$customerContactInfo', '$multiBookId', '$isPartial', '$eachPartial')";
                    $multiReservation = dynamicInsert($MultiReservationInsertSQL, 1);
        
                    if($multiReservation) {
        
                        if($multiBookingimageLink) {
                            $saveEvidence = @insertPaymentEvidence($multiReservation, $multiBookingimageLink);
                        }
        
                        $response['reserve'] = 'success';
                       
                    }
                }
            }
        }

    }

    echo json_encode($response);
    
}

function getPaymentMethod($paymentMethodName) {
    global $response;
    global $conn;

    $slectPaymentMethodSql = "
    SELECT 
        pm.Id AS paymentMethodId,
        pm.paymentMethodName AS paymentMethodName,
        pm.qrLink AS paymentMethodOrLink,
        pm.paymentNumber AS paymentMethodPaymentNumber
    FROM 
        payment_methods AS pm
    WHERE
        pm.paymentMethodName = ?
    ";

    if ($stmt = $conn->prepare($slectPaymentMethodSql)) {
        // Bind the parameter
        $stmt->bind_param("s", $paymentMethodName);

        // Execute the query
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Fetch the data
        if ($row = $result->fetch_assoc()) {
            return $row['paymentMethodId'];
        }

        // Close the statement
        $stmt->close();
    } else {
        $response['error'] = 'Failed to prepare the SQL statement.';
    }

    return null;
}

// Check if inputData contains 'queryAllRooms'
if (isset($inputData['queryAllRooms'])) {

    $checkInDate = isset($inputData['checkInDate']) ? $inputData['checkInDate'] : null; 
    $checkOutDate = isset($inputData['checkOutDate']) ? $inputData['checkOutDate'] : null;

    $selectRoomsSql = "
    SELECT 
        r.Id AS roomId,
        r.overall_rating AS overall_rating,
        r.name AS roomName,
        r.maximum AS roomMaxCap,
        r.description AS roomDescription,
        r.originalRate AS roomPublishedRate,
        r.quantity AS roomQuantity,
        CONCAT(
            '[',
            GROUP_CONCAT(
                DISTINCT CONCAT(
                    '{\"Id\":\"', ri.Id, '\",\"Link\":\"', ri.Link, '\"}'
                ) ORDER BY ri.Link DESC
            ),
            ']'
        ) AS imageLinks,
        CONCAT(
            '[',
            GROUP_CONCAT(
                DISTINCT CONCAT(
                    '{\"type\":\"', o_rate.type, '\",\"amount\":', o_rate.amount, '}'
                ) ORDER BY o_rate.Id DESC
            ),
            ']'
        ) AS otherRates,
        IFNULL(totalCheckIn.totalCheckInQuantity, 0) AS totalCheckInQuantity
    FROM 
        rooms AS r
    LEFT JOIN 
        room_image AS ri 
        ON r.Id = ri.roomId
    LEFT JOIN 
        other_rate AS o_rate 
        ON r.Id = o_rate.roomId
    LEFT JOIN (
        SELECT 
            roomId, 
            SUM(checkInQuantity) AS totalCheckInQuantity 
        FROM 
            check_ins 
        WHERE 
            status IN ('Pending', 'Approved')
            AND (
                DATE(checkInDate) <= DATE_ADD(?, INTERVAL 1 DAY) AND DATE(checkOutDate) >= DATE_SUB(?, INTERVAL 1 DAY) 
                OR DATE(checkInDate) <= DATE_ADD(?, INTERVAL 1 DAY) AND DATE(checkOutDate) >= DATE_SUB(?, INTERVAL 1 DAY)
                OR DATE(checkInDate) >= DATE_SUB(?, INTERVAL 1 DAY) AND DATE(checkOutDate) <= DATE_ADD(?, INTERVAL 1 DAY)
            )
        GROUP BY roomId
    ) AS totalCheckIn 
    ON r.Id = totalCheckIn.roomId
    WHERE (r.quantity - IFNULL(totalCheckIn.totalCheckInQuantity, 0)) > 0
    GROUP BY r.Id, r.name, r.maximum, r.description, r.originalRate, r.quantity
    ORDER BY r.overall_rating DESC
";

    if ($stmt = $conn->prepare($selectRoomsSql)) {
        // Bind the parameters to the SQL query
        $stmt->bind_param('ssssss', $checkOutDate, $checkInDate, $checkOutDate, $checkInDate, $checkInDate, $checkOutDate);
        roomQuery($stmt);
    } else {
        $response['error'] = 'Failed to prepare the SQL statement.';
    }

    echo json_encode($response);
}


// query single room detail
if (isset($inputData['querySingleRoom'])) {

    $roomId = $inputData['roomId'];
    $checkInDate = (isset($inputData['checkInDate']) ? $inputData['checkInDate'] : null); // Replace with your desired check-in date
    $checkOutDate =  (isset($inputData['checkOutDate']) ? $inputData['checkOutDate'] : null); // Replace with your desired check-out date

    $selectRoomsSql = "
    SELECT 
        r.Id AS roomId,
        r.name AS roomName,
        r.maximum AS roomMaxCap,
        r.description AS roomDescription,
        r.originalRate AS roomPublishedRate,
        r.quantity AS roomQuantity,
        CONCAT(
            '[',
            GROUP_CONCAT(
                DISTINCT CONCAT(
                    '{\"Id\":\"', ri.Id, '\",\"Link\":\"', ri.Link, '\"}'
                ) ORDER BY ri.Link DESC
            ),
            ']'
        ) AS imageLinks,
        CONCAT(
            '[',
            GROUP_CONCAT(
                DISTINCT CONCAT(
                    '{\"Id\":\"', o_rate.Id, '\",\"type\":\"', o_rate.type, '\",\"amount\":', o_rate.amount, '}'
                ) ORDER BY o_rate.Id DESC
            ),
            ']'
        ) AS otherRates,
        CONCAT(
            '[',
            GROUP_CONCAT(
                DISTINCT CONCAT(
                    '{\"Id\":\"', am.Id, '\",\"amenityName\":\"', REPLACE(am.amenityName, '\"', ''), '\"}'
                ) ORDER BY am.amenityName DESC
            ),            
            ']'
        ) AS amenities,
        IFNULL(totalCheckIn.totalCheckInQuantity, 0) AS totalCheckInQuantity
    FROM 
        rooms AS r
    LEFT JOIN 
        room_image AS ri 
        ON r.Id = ri.roomId
    LEFT JOIN 
        other_rate AS o_rate 
        ON r.Id = o_rate.roomId
    LEFT JOIN 
        amenity AS am 
        ON r.Id = am.roomId
    LEFT JOIN (
        SELECT 
            roomId, 
            SUM(checkInQuantity) AS totalCheckInQuantity 
        FROM 
            check_ins 
        WHERE 
            status IN ('Pending', 'Approved')
            AND (
                DATE(checkInDate) <= ? AND DATE(checkOutDate) >= ? -- Overlapping with start date
                OR DATE(checkInDate) <= ? AND DATE(checkOutDate) >= ? -- Overlapping with end date
                OR DATE(checkInDate) >= ? AND DATE(checkOutDate) <= ? -- Completely within the date range
            )
        GROUP BY roomId
    ) AS totalCheckIn 
    ON r.Id = totalCheckIn.roomId
    WHERE r.Id = ? AND (r.quantity - IFNULL(totalCheckIn.totalCheckInQuantity, 0)) > 0
    ";

    if ($stmt = $conn->prepare($selectRoomsSql)) {
        $stmt->bind_param('ssssssi', $checkOutDate, $checkInDate, $checkOutDate, $checkInDate, $checkInDate, $checkOutDate,$roomId);
        roomQuery($stmt);
    } else {
        $response['error'] = 'Failed to prepare the SQL statement.';
    }

    echo json_encode($response);
}

if (isset($inputData['openReservationNotification'])) {
    $notificationId = $inputData['notificationId'];

    $selectCheckInsSql = "
    SELECT
    ck.Id AS checkInId,
    ck.checkInDate AS checkInDate,
    ck.checkOutDate AS checkOutDate,
    ck.queueDateTime AS queueDateTime,
    ck.status AS reservationStatus,
    ck.checkInQuantity AS reservationQuantity,
    ck.totalAmount AS reservationTotalPayable,
    ck.customerfullName AS customerfullName,
    ck.customerCompleteAddress AS customerCompleteAddress,
    ck.customerContactInfo AS customerContactInfo,
    ck.message AS reservationMessage,
    ck.isPartial AS isPartial,
    ck.partialPayment AS partialPayment,
    ck.roomId AS roomId,
    CONCAT(
        '[',
        GROUP_CONCAT(
            DISTINCT CONCAT(
                '{\"Id\":\"', pe.Id, '\",\"Link\":\"', pe.Link, '\"}'
            ) ORDER BY pe.Link DESC
        ),
        ']'
    ) AS imageLinks,
    r.name AS roomName,
    r.maximum AS roomMaxCap,
    pm.paymentMethodName AS paymentMethodName
    FROM
        check_ins AS ck
    LEFT JOIN
        rooms AS r
        ON r.Id = ck.roomId
    LEFT JOIN
        payment_methods AS pm
        ON pm.Id = ck.paymentMethodId
    LEFT JOIN
        payment_evidence AS pe
        ON pe.checkInId = ck.Id
    WHERE ck.Id = ?
    ";

    if ($stmt = $conn->prepare($selectCheckInsSql)) {
        $stmt->bind_param('i', $notificationId);
        roomQuery($stmt);
    } else {
        $response['error'] = 'Failed to prepare the SQL statement.';
    }

    echo json_encode($response);
}

if (isset($inputData['openMultiReservationNotification'])) {
    $notificationId = $inputData['notificationId'];

    $selectCheckInsSql = "
        SELECT
        ck.Id AS checkInId,
        ck.checkInDate AS checkInDate,
        ck.checkOutDate AS checkOutDate,
        ck.queueDateTime AS queueDateTime,
        ck.status AS reservationStatus,
        ck.checkInQuantity AS reservationQuantity,
        ck.totalAmount AS reservationTotalPayable,
        ck.customerfullName AS customerfullName,
        ck.customerCompleteAddress AS customerCompleteAddress,
        ck.customerContactInfo AS customerContactInfo,
        ck.message AS reservationMessage,
        ck.isPartial AS isPartial,
        ck.partialPayment AS partialPayment,
        r.name AS roomName,
        r.maximum AS roomMaxCap,
        pm.paymentMethodName AS paymentMethodName
        FROM
            check_ins AS ck
        LEFT JOIN
            rooms AS r
            ON r.Id = ck.roomId
        LEFT JOIN
            payment_methods AS pm
            ON pm.Id = ck.paymentMethodId
        WHERE ck.multiBookId = ?
    ";
    
    $newRows = [];
    
    if ($stmt = $conn->prepare($selectCheckInsSql)) {
        $stmt->bind_param('i', $notificationId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $newRows[] = $row;
            }
        } else {
            $response['error'] = 'No records found.';
        }
    } else {
        $response['error'] = 'Failed to prepare the SQL statement.';
    }

    foreach ($newRows as &$row) {
        $checkInId = $row['checkInId'];
    
        $selectImageLinksSql = "
            SELECT
                CONCAT(
                    '[',
                    GROUP_CONCAT(
                        DISTINCT CONCAT(
                            '{\"Id\":\"', pe.Id, '\",\"Link\":\"', pe.Link, '\"}'
                        ) ORDER BY pe.Link DESC
                    ),
                    ']'
                ) AS imageLinks
            FROM
                payment_evidence AS pe
            WHERE pe.checkInId = ?
        ";
    
        if ($stmt = $conn->prepare($selectImageLinksSql)) {
            $stmt->bind_param('i', $checkInId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $imageRow = $result->fetch_assoc();
                $row['imageLinks'] = $imageRow['imageLinks'];
            } else {
                $row['imageLinks'] = '[]';
            }
        } else {
            $response['error'] = 'Failed to prepare the SQL statement for imageLinks.';
        }
    }
    unset($row);
    
    // Return the results as a JSON response
    echo json_encode(['newRows' => $newRows, 'rowCount' => count($newRows)]);
    exit();    
    
}

// query payment methods
if(isset($inputData['queryPaymentMethods'])) {

    $slectPaymentMethodSql = "
    SELECT 
        pm.Id AS paymentMethodId,
        pm.paymentMethodName AS paymentMethodName,
        pm.qrLink AS paymentMethodOrLink,
        pm.paymentNumber AS paymentMethodPaymentNumber
    FROM 
        payment_methods AS pm
    ";

    if ($stmt = $conn->prepare($slectPaymentMethodSql)) {
        roomQuery($stmt);
    } else {
        $response['error'] = 'Failed to prepare the SQL statement.';
    }

    echo json_encode($response);
}

// query total number of rooms
if(isset($inputData['getTotalRoomCounts'])) {
    $sql = "SELECT SUM(quantity) AS roomQuantity FROM rooms";

    if ($stmt = $conn->prepare($sql)) {
        roomQuery($stmt);
    } else {
        $response['error'] = 'Failed to prepare the SQL statement.';
    }
    echo json_encode($response);
}

// Query total number of checkouts today
if (isset($inputData['getCheckoutValueToday'])) {

    $timezone = new DateTimeZone('Asia/Manila');
    $date = new DateTime('now', $timezone);
    $currentDate = $date->format('Y-m-d');
    $sql = "SELECT COUNT(*) AS totalCheckoutToday FROM check_ins WHERE `checkOutDate` = ? AND `status` = 'approved'";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('s', $currentDate);
        $stmt->execute();
        $stmt->bind_result($totalCheckoutToday);
        $stmt->fetch();
        $response['totalCheckoutToday'] = $totalCheckoutToday;
        $stmt->close();
    } else {
        $response['error'] = 'Failed to prepare the SQL statement.';
    }
    
    echo json_encode($response);
}

// Query total number of check-ins today
if (isset($inputData['getCheckInValueToday'])) {

    $timezone = new DateTimeZone('Asia/Manila');
    $date = new DateTime('now', $timezone);
    $currentDate = $date->format('Y-m-d');
    $sql = "SELECT COUNT(*) AS totalCheckInToday FROM check_ins WHERE `checkInDate` = ? AND `status` = 'approved'";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('s', $currentDate);
        $stmt->execute();
        $stmt->bind_result($totalCheckInToday);
        $stmt->fetch();
        $response['totalCheckInToday'] = $totalCheckInToday;
        $stmt->close();
    } else {
        $response['error'] = 'Failed to prepare the SQL statement.';
    }
    
    echo json_encode($response);
}

// Query total number of partially-paid today
if (isset($inputData['getTotdaysPaidPartial'])) {

    $timezone = new DateTimeZone('Asia/Manila');
    $date = new DateTime('now', $timezone);
    $currentDate = $date->format('Y-m-d');
    $sql = "SELECT COUNT(*) AS totdaysPaidPartial FROM check_ins WHERE (`createdDate` = ? OR `latestModifiedDate` = ?) AND `status` = 'approved' AND `isPartial` = '1'";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('ss', $currentDate, $currentDate);
        $stmt->execute();
        $stmt->bind_result($totdaysPaidPartial);
        $stmt->fetch();
        $response['totdaysPaidPartial'] = $totdaysPaidPartial;
        $stmt->close();
    } else {
        $response['error'] = 'Failed to prepare the SQL statement.';
    }
    
    echo json_encode($response);
}

// Query total number of todays rejected or cancelled
if (isset($inputData['getCancelledOrRejected'])) {

    $timezone = new DateTimeZone('Asia/Manila');
    $date = new DateTime('now', $timezone);
    $currentDate = $date->format('Y-m-d');
    $sql = "SELECT COUNT(*) AS totdaysCancelledOrRejected FROM check_ins WHERE (`createdDate` = ? OR `latestModifiedDate` = ?) AND (`status` = 'rejected')";

    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('ss', $currentDate, $currentDate);
        $stmt->execute();
        $stmt->bind_result($totdaysCancelledOrRejected);
        $stmt->fetch();
        $response['totdaysCancelledOrRejected'] = $totdaysCancelledOrRejected;
        $stmt->close();
    } else {
        $response['error'] = 'Failed to prepare the SQL statement.';
    }
    
    echo json_encode($response);
}


// Query total base on date
if (isset($inputData['getTotalBaseOndate'])) {

    $listCount = [];
    $past7Days = $inputData['past7Days'];
    $status = $inputData['status'];

    foreach ($past7Days as $day) {
        // Prepare the SQL query
        $sql = "SELECT COUNT(*) AS total FROM `check_ins` 
                WHERE (`latestModifiedDate` = ?) 
                AND `status` = ?";
        
        if ($stmt = $conn->prepare($sql)) {
            $likeDay = $day; // To match any time on that day
            $stmt->bind_param('ss', $likeDay, $status);
            $stmt->execute();
            $stmt->bind_result($total);
            $stmt->fetch();
            $stmt->close();
    
            // Add the result to listCount
            $listCount[] = $total > 0 ? $total : 0;
        } else {
            // In case of query preparation failure
            $listCount[] = 0;
        }
    }
    
    echo json_encode($listCount);
}

// query function 
function roomQuery($stmt) {
    global $response;

    $stmt->execute();
    $result = $stmt->get_result();
    
    $response['rooms'] = [];
    
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $room = [
                'roomId' => isset($row['roomId']) ? $row['roomId'] : null,
                'roomName' => isset($row['roomName']) ? $row['roomName'] : null,
                'roomMaxCap' => isset($row['roomMaxCap']) ? $row['roomMaxCap'] : null,
                'roomDescription' => isset($row['roomDescription']) ? $row['roomDescription'] : null,
                'roomPublishedRate' => isset($row['roomPublishedRate']) ? $row['roomPublishedRate'] : null,
                'roomQuantity' => isset($row['roomQuantity']) ? $row['roomQuantity'] : null,
                'imageLink' => isset($row['imageLinks']) ? $row['imageLinks'] : null,
                'otherRate' => isset($row['otherRates']) ? $row['otherRates'] : null,
                'amenities' => isset($row['amenities']) ? $row['amenities'] : null,
                'paymentMethodId' => isset($row['paymentMethodId']) ? $row['paymentMethodId'] : null,
                'paymentMethodName' => isset($row['paymentMethodName']) ? $row['paymentMethodName'] : null,
                'paymentMethodOrLink' => isset($row['paymentMethodOrLink']) ? $row['paymentMethodOrLink'] : null,
                'paymentMethodPaymentNumber' => isset($row['paymentMethodPaymentNumber']) ? $row['paymentMethodPaymentNumber'] : null,
                'checkInId' => isset($row['checkInId']) ? $row['checkInId'] : null,
                'checkInDate' => isset($row['checkInDate']) ? $row['checkInDate'] : null,
                'checkOutDate' => isset($row['checkOutDate']) ? $row['checkOutDate'] : null,
                'paidAmount' => isset($row['paidAmount']) ? $row['paidAmount'] : null,
                'queueDateTime' => isset($row['queueDateTime']) ? $row['queueDateTime'] : null,
                'reservationStatus' => isset($row['reservationStatus']) ? $row['reservationStatus'] : null,
                'reservationQuantity' => isset($row['reservationQuantity']) ? $row['reservationQuantity'] : null,
                'reservationTotalPayable' => isset($row['reservationTotalPayable']) ? $row['reservationTotalPayable'] : null,
                'customerfullName' => isset($row['customerfullName']) ? $row['customerfullName'] : null,
                'customerCompleteAddress' => isset($row['customerCompleteAddress']) ? $row['customerCompleteAddress'] : null,
                'customerContactInfo' => isset($row['customerContactInfo']) ? $row['customerContactInfo'] : null,
                'reservationMessage' => isset($row['reservationMessage']) ? $row['reservationMessage'] : null,
                'totalCheckInQuantity' => isset($row['totalCheckInQuantity'])? $row['totalCheckInQuantity'] : null,
                'overall_rating' => isset($row['overall_rating'])? $row['overall_rating'] : null

            ];
            $response['rooms'][] = $room;
        }
    } else {
        $response['notfound'] = true;
    }

    $stmt->close();
}

// insert room images
function insertRoomImages($roomId, $imageLink){
    global $conn;
    $sql = "INSERT INTO `room_image`(`Link`, `roomId`) VALUES ";
    $values = [];
    
    foreach ($imageLink as $image) {
        if (isset($image['Link']) && is_string($image['Link'])) {
            $escapedLink = mysqli_real_escape_string($conn, $image['Link']);
            $values[] = "('$escapedLink', '$roomId')";
        } else {
            throw new Exception("Invalid value found in \$imageLink array.");
        }
    }
    
    $sql .= implode(", ", $values);
    $imagesIds = dynamicInsert($sql, count($imageLink));

    if($imagesIds) {
        return $imagesIds;
    }
    return false;
}

function insertPaymentEvidence($checkInId, $imageLink) {
    global $conn;
    $sql = "INSERT INTO `payment_evidence`(`Link`, `checkInId`) VALUES";
    $values = [];

    foreach ($imageLink as $image) {
        if (isset($image['Link']) && is_string($image['Link'])) {
            $escapedLink = mysqli_real_escape_string($conn, $image['Link']);
            $values[] = "('$escapedLink', '$checkInId')";
        } else {
            throw new Exception("Invalid value found in \$imageLink array.");
        }
    }

    $sql .= implode(", ", $values);
    $imagesIds = dynamicInsert($sql, count($imageLink));

    if($imagesIds) {
        return $imagesIds;
    }
    return false;
}

// insert other rates
function insertOtherRates($roomId, $otherRates) {
    global $conn;
    $sql = "INSERT INTO `other_rate`(`type`, `roomId`, `amount`) VALUES ";
    $values = [];

    foreach ($otherRates as $rates) {
        if (isset($rates['rateType']) && isset($rates['newRateValue'])) {
            $escapedrateType = mysqli_real_escape_string($conn, $rates['rateType']);
            $escapednewRateValue = mysqli_real_escape_string($conn, $rates['newRateValue']);
            $values[] = "('$escapedrateType', '$roomId', '$escapednewRateValue')";
        } else {
            throw new Exception("Invalid value found in \$otherRates array.");
        }
    }

    $sql .= implode(", ", $values);
    $otherRateIds = dynamicInsert($sql, count($otherRates));

    if($otherRateIds) {
        return $otherRateIds;
    }
    return false;
}

// insert amenities
function insertAmenities($roomId, $amenities) {
    global $conn;
    $sql = "INSERT INTO `amenity`(`amenityName`, `roomId`) VALUES";
    $values = [];

    foreach ($amenities as $amenity) {
        if (isset($amenity['amenity'])) {
            $escapeAmenity = mysqli_real_escape_string($conn, $amenity['amenity']);
            $values[] = "('$escapeAmenity', '$roomId')";
        } else {
            throw new Exception("Invalid value found in \$amenities array.");
        }
    }

    $sql .= implode(", ", $values);
    $amenitiesIds = dynamicInsert($sql, count($amenities));

    if($amenitiesIds) {
        return $amenitiesIds;
    }
    return false;
}

// insert new roomImage
if (isset($inputData['newRoomImage']) && $inputData['newRoomImage'] === true) {
    // Capture input data
    $roomId = $inputData['roomId'] ?? null;
    $imageLink = $inputData['imageLink'] ?? null;

    // Validate required fields
    if ($roomId && $imageLink) {
        // Insert the new image into the room_image table
        $insertImageSql = "INSERT INTO `room_image` (`Link`, `roomId`) VALUES (?, ?)";
        if ($stmt = $conn->prepare($insertImageSql)) {
            $stmt->bind_param("si", $imageLink, $roomId);
            if ($stmt->execute()) {
                $response = [
                    'status' => 'success',
                    'message' => 'Image added successfully.',
                    'roomId' => $roomId,
                    'imageLink' => $imageLink,
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Failed to insert image: ' . $stmt->error,
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

// delete room image
if (isset($inputData['deleteImage']) && $inputData['deleteImage'] === true) {
    // Capture input data
    $imageId = $inputData['id'] ?? null;
    $imageLink = $inputData['link'] ?? null;

    // Validate required fields
    if ($imageId && $imageLink) {
        // Prepare the SQL query to delete the image
        $deleteImageSql = "DELETE FROM `room_image` WHERE `Id` = ? AND `Link` = ?";
        if ($stmt = $conn->prepare($deleteImageSql)) {
            $stmt->bind_param("is", $imageId, $imageLink);
            if ($stmt->execute()) {
                if ($stmt->affected_rows > 0) {
                    $response = [
                        'status' => 'success',
                        'message' => 'Image deleted successfully.',
                    ];
                } else {
                    $response = [
                        'status' => 'error',
                        'message' => 'No matching image found to delete.',
                    ];
                }
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Failed to execute delete statement: ' . $stmt->error,
                ];
            }
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Failed to prepare delete statement: ' . $conn->error,
            ];
        }
    } else {
        $response = [
            'status' => 'error',
            'message' => 'Missing required fields: id or Link.',
        ];
    }

    // Output response as JSON
    echo json_encode($response);
}

// insert other rate
if (isset($inputData['addNewRate']) && $inputData['addNewRate'] === true) {
    // Capture input data
    $rateType = $inputData['rateType'] ?? null;
    $newRateValue = $inputData['newRateValue'] ?? null;
    $roomId = $inputData['roomId'] ?? null;

    // Validate required fields
    if ($rateType && $newRateValue !== null && $roomId) {
        // Prepare the SQL query to insert the new rate
        $insertRateSql = "INSERT INTO `other_rate` (`type`, `roomId`, `amount`) VALUES (?, ?, ?)";
        if ($stmt = $conn->prepare($insertRateSql)) {
            $stmt->bind_param("sid", $rateType, $roomId, $newRateValue);
            if ($stmt->execute()) {
                $response = [
                    'status' => 'success',
                    'message' => 'New rate added successfully.',
                    'rateId' => $stmt->insert_id // Return the ID of the newly inserted rate
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Failed to add new rate: ' . $stmt->error
                ];
            }
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Failed to prepare insert statement: ' . $conn->error
            ];
        }
    } else {
        $response = [
            'status' => 'error',
            'message' => 'Missing required fields: rateType, newRateValue, or roomId.'
        ];
    }

    // Output response as JSON
    echo json_encode($response);
}

// delete other rate
if (isset($inputData['removeThisOtherRate']) && $inputData['removeThisOtherRate'] === true) {
    // Capture input data
    $rateId = $inputData['id'] ?? null; // ID of the rate to be removed
    $rateType = $inputData['type'] ?? null; // Type of the rate (optional for debugging/logging)
    $amount = $inputData['amount'] ?? null; // Amount of the rate (optional for debugging/logging)

    // Validate required fields
    if ($rateId) {
        // Prepare the SQL query to delete the specified rate
        $deleteRateSql = "DELETE FROM `other_rate` WHERE `Id` = ?";
        if ($stmt = $conn->prepare($deleteRateSql)) {
            $stmt->bind_param("i", $rateId); // Bind the rate ID as an integer
            if ($stmt->execute()) {
                // If the deletion is successful
                if ($stmt->affected_rows > 0) {
                    $response = [
                        'status' => 'success',
                        'message' => "Rate with ID {$rateId} successfully removed.",
                        'rateId' => $rateId,
                    ];
                } else {
                    $response = [
                        'status' => 'error',
                        'message' => "Rate with ID {$rateId} does not exist or was not removed.",
                    ];
                }
            } else {
                // Handle query execution failure
                $response = [
                    'status' => 'error',
                    'message' => 'Failed to delete the rate: ' . $stmt->error,
                ];
            }
        } else {
            // Handle query preparation failure
            $response = [
                'status' => 'error',
                'message' => 'Failed to prepare delete statement: ' . $conn->error,
            ];
        }
    } else {
        // Handle missing rate ID
        $response = [
            'status' => 'error',
            'message' => 'Rate ID is missing or invalid.',
        ];
    }

    // Output response as JSON
    echo json_encode($response);
}

// add new amenity
if (isset($inputData['addThisNewAmenity']) && $inputData['addThisNewAmenity'] === true) {
    // Capture input data
    $amenityName = $inputData['amenity'] ?? null; // Amenity name
    $roomId = $inputData['roomId'] ?? null;       // Room ID

    // Validate required fields
    if ($amenityName && $roomId) {
        // Prepare the SQL query to insert the new amenity
        $insertAmenitySql = "INSERT INTO `amenity` (`amenityName`, `roomId`) VALUES (?, ?)";
        if ($stmt = $conn->prepare($insertAmenitySql)) {
            $stmt->bind_param("si", $amenityName, $roomId); // Bind the amenity name and room ID
            if ($stmt->execute()) {
                // If the insertion is successful
                $response = [
                    'status' => 'success',
                    'message' => "Amenity '{$amenityName}' successfully added to room ID {$roomId}.",
                    'amenityId' => $stmt->insert_id, // Return the inserted amenity's ID
                ];
            } else {
                // Handle query execution failure
                $response = [
                    'status' => 'error',
                    'message' => 'Failed to insert amenity: ' . $stmt->error,
                ];
            }
        } else {
            // Handle query preparation failure
            $response = [
                'status' => 'error',
                'message' => 'Failed to prepare insert statement: ' . $conn->error,
            ];
        }
    } else {
        // Handle missing required fields
        $response = [
            'status' => 'error',
            'message' => 'Missing required fields: amenityName or roomId.',
        ];
    }

    // Output response as JSON
    echo json_encode($response);
}

// delete amenity
if (isset($inputData['removeThisAmenity']) && $inputData['removeThisAmenity'] === true) {
    // Capture input data
    $amenityId = $inputData['id'] ?? null; // Amenity ID to be removed
    $roomId = $inputData['roomId'] ?? null; // Room ID for verification

    // Validate required fields
    if ($amenityId && $roomId) {
        // Prepare the SQL query to delete the amenity
        $deleteAmenitySql = "DELETE FROM `amenity` WHERE `Id` = ? AND `roomId` = ?";
        if ($stmt = $conn->prepare($deleteAmenitySql)) {
            $stmt->bind_param("ii", $amenityId, $roomId); // Bind the amenity ID and room ID
            if ($stmt->execute()) {
                // Check if any rows were affected
                if ($stmt->affected_rows > 0) {
                    $response = [
                        'status' => 'success',
                        'message' => "Amenity ID {$amenityId} successfully removed from room ID {$roomId}.",
                    ];
                } else {
                    $response = [
                        'status' => 'error',
                        'message' => 'No matching amenity found or already deleted.',
                    ];
                }
            } else {
                // Handle query execution failure
                $response = [
                    'status' => 'error',
                    'message' => 'Failed to delete amenity: ' . $stmt->error,
                ];
            }
        } else {
            // Handle query preparation failure
            $response = [
                'status' => 'error',
                'message' => 'Failed to prepare delete statement: ' . $conn->error,
            ];
        }
    } else {
        // Handle missing required fields
        $response = [
            'status' => 'error',
            'message' => 'Missing required fields: amenityId or roomId.',
        ];
    }

    // Output response as JSON
    echo json_encode($response);
}

// update room info
if (isset($inputData['updateRoomInfo']) && $inputData['updateRoomInfo'] === true) {
    // Capture input data
    $roomId = $inputData['roomId'] ?? null;
    $newRoomName = $inputData['newRooName'] ?? null;
    $newRoomDescription = $inputData['newRoomDescription'] ?? null;
    $newMaximumCapacity = $inputData['newMaximumCapacity'] ?? null;
    $newRoomQuantity = $inputData['newRoomQuantity'] ?? null;
    $newPublishedRate = $inputData['newPublishedRate'] ?? null;

    // Validate required fields
    if ($roomId && $newRoomName && $newRoomDescription && $newMaximumCapacity !== null && $newRoomQuantity !== null && $newPublishedRate !== null) {
        // Prepare the SQL query to update room details
        $updateRoomSql = "
            UPDATE `rooms` 
            SET 
                `name` = ?, 
                `description` = ?, 
                `maximum` = ?, 
                `quantity` = ?, 
                `originalRate` = ?
            WHERE `Id` = ?";

        if ($stmt = $conn->prepare($updateRoomSql)) {
            // Bind parameters to the SQL query
            $stmt->bind_param("ssiidi", $newRoomName, $newRoomDescription, $newMaximumCapacity, $newRoomQuantity, $newPublishedRate, $roomId);
            
            // Execute the query
            if ($stmt->execute()) {
                $response = [
                    'status' => 'success',
                    'message' => 'Room information updated successfully.',
                    'roomId' => $roomId,
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Failed to update room information: ' . $stmt->error,
                ];
            }
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Failed to prepare update statement: ' . $conn->error,
            ];
        }
    } else {
        $response = [
            'status' => 'error',
            'message' => 'Missing required fields.',
        ];
    }

    // Output response as JSON
    echo json_encode($response);
}

?>