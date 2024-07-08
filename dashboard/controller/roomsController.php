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
            $response['error'] = 'The quantity you selected is greater than the actual.';
        }
        else {
            if(@getPaymentMethod($paymentMethod)) {

                $pmId = @getPaymentMethod($paymentMethod);
                $reservationInsertSQL = "INSERT INTO `check_ins`(`roomId`, `checkInDate`, `checkOutDate`, `paidAmount`, `userId`, `queueDateTime`, `status`, `checkInQuantity`, `paymentMethodId`, `totalAmount`, `customerfullName`, `customerCompleteAddress`, `customerContactInfo`) 
                VALUES ('$roomId','$checkInDate','$checkOutDate','$paidAmount','$userId','$queueDateTime','Pending','$quantity','$pmId','$totalAmount','$customerfullName','$customerCompleteAddress','$customerContactInfo')";
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
                DATE(checkInDate) <= ? AND DATE(checkOutDate) >= ? -- Overlapping with start date
                OR DATE(checkInDate) <= ? AND DATE(checkOutDate) >= ? -- Overlapping with end date
                OR DATE(checkInDate) >= ? AND DATE(checkOutDate) <= ? -- Completely within the date range
            )
        GROUP BY roomId
    ) AS totalCheckIn 
    ON r.Id = totalCheckIn.roomId
    WHERE (r.quantity - IFNULL(totalCheckIn.totalCheckInQuantity, 0)) > 0
    GROUP BY r.Id, r.name, r.maximum, r.description, r.originalRate, r.quantity
    ORDER BY r.Id ASC
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
                    '{\"Id\":\"', am.Id, '\",\"amenityName\":\"', am.amenityName, '\"}'
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
    ck.paidAmount AS paidAmount,
    ck.queueDateTime AS queueDateTime,
    ck.status AS reservationStatus,
    ck.checkInQuantity AS reservationQuantity,
    ck.totalAmount AS reservationTotalPayable,
    ck.customerfullName AS customerfullName,
    ck.customerCompleteAddress AS customerCompleteAddress,
    ck.customerContactInfo AS customerContactInfo,
    ck.message AS reservationMessage,
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
                'totalCheckInQuantity' => isset($row['totalCheckInQuantity'])? $row['totalCheckInQuantity'] : null

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

?>