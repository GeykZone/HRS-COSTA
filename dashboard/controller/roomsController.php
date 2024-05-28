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
        ) AS otherRates
    FROM 
        rooms AS r
    LEFT JOIN 
        room_image AS ri 
        ON r.Id = ri.roomId
    LEFT JOIN 
        other_rate AS o_rate 
        ON r.Id = o_rate.roomId
    LEFT JOIN 
        check_ins c 
        ON r.Id = c.roomId
        AND c.status NOT IN ('pending', 'check-in')
        AND (
            DATE(c.checkInDate) <= ? AND DATE(c.checkOutDate) >= ? -- Overlapping with start date
            OR DATE(c.checkInDate) <= ? AND DATE(c.checkOutDate) >= ? -- Overlapping with end date
            OR DATE(c.checkInDate) >= ? AND DATE(c.checkOutDate) <= ? -- Completely within the date range
        )
    WHERE c.roomId IS NULL
    GROUP BY roomId ASC ";

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
        ) AS amenities
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
    WHERE r.Id = ?
    ";

    if ($stmt = $conn->prepare($selectRoomsSql)) {
        $stmt->bind_param('i', $roomId);
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
                'paymentMethodPaymentNumber' => isset($row['paymentMethodPaymentNumber']) ? $row['paymentMethodPaymentNumber'] : null

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