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

if (isset($inputData['saveRoomDetails'])) {

    $imageLink = $inputData['imageLink'];
    $otherRates = $inputData['otherRates'];
    $roomName = $inputData['roomName'];
    $roomDescription = $inputData['roomDescription'];
    $maximumCapacity = $inputData['maximumCapacity'];
    $publishedRate = $inputData['publishedRate'];
    $response['roomInserted'] = false;

    $roomsInsertSQL = "INSERT INTO `rooms`( `name`, `maximum`, `description`, `originalRate`) VALUES ('$roomName','$maximumCapacity','$roomDescription','$publishedRate')";
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

    }

    // $response['otherRates'] =  $otherRates;

    echo json_encode($response);
}

// Check if inputData contains 'queryAllRooms'
if (isset($inputData['queryAllRooms'])) {
    $selectRoomsSql = "
    SELECT 
        r.Id AS roomId,
        r.name AS roomName,
        r.maximum AS roomMaxCap,
        r.description AS roomDescription,
        r.originalRate AS roomPublishedRate,
        GROUP_CONCAT(DISTINCT ri.Link ORDER BY ri.Link DESC SEPARATOR ',') AS imageLinks,
        GROUP_CONCAT(DISTINCT o_rate.type ORDER BY o_rate.type DESC SEPARATOR ',') AS otherRates,
        GROUP_CONCAT(DISTINCT o_rate.amount ORDER BY o_rate.amount DESC SEPARATOR ',') AS otherRateAmounts
    FROM 
        rooms AS r
    LEFT JOIN 
        room_image AS ri 
        ON r.Id = ri.roomId
    LEFT JOIN 
        other_rate AS o_rate 
        ON r.Id = o_rate.roomId
    GROUP BY roomId ASC
    ";

    if ($stmt = $conn->prepare($selectRoomsSql)) {
        dynamicQuery($stmt);
    } else {
        $response['error'] = 'Failed to prepare the SQL statement.';
    }

    echo json_encode($response);
}

// query function 
function dynamicQuery($stmt) {
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
                'imageLink' => isset($row['imageLinks']) ? $row['imageLinks'] : null,
                'otherRate' => isset($row['otherRates']) ? $row['otherRates'] : null,
                'otherRateAmount' => isset($row['otherRateAmounts']) ? $row['otherRateAmounts'] : null
            ];
            $response['rooms'][] = $room;
        }
    } else {
        $response['notfound'] = true;
    }

    $stmt->close();
}

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

?>