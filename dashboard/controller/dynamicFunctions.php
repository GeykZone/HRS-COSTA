<?php

if (file_exists('../../connection/connect.php')) {
    include('../../connection/connect.php');
};

// Dynamic insert function
function dynamicInsert($InsertSQL, $rowCount) {
    global $conn;
    global $response;
    try {
        if ($conn->query($InsertSQL) === true) {
            $firstId = $conn->insert_id;
            $insertedIds = [];
            if($rowCount > 1){
                for ($i = 0; $i < $rowCount; $i++) {
                    $insertedIds[] = $firstId + $i;
                }
                return $insertedIds;
            }
            return $firstId;
        }
        return false;
    } catch (Exception $e) {
        $response['insertUserError'] = "Error: " . $e->getMessage();
        return false;
    }
}

// Dynamic update function
function updateRecord($table, $data, $conditions, $conn) {
    // Construct the SET part of the query
    $setPart = [];
    foreach ($data as $column => $value) {
        $setPart[] = "`$column` = ?";
    }
    $setPart = implode(", ", $setPart);

    // Construct the WHERE part of the query
    $wherePart = [];
    foreach ($conditions as $column => $value) {
        $wherePart[] = "`$column` = ?";
    }
    $wherePart = implode(" AND ", $wherePart);

    // Combine into a full SQL query
    $sql = "UPDATE `$table` SET $setPart WHERE $wherePart";

    // Prepare and execute the statement
    if ($stmt = $conn->prepare($sql)) {
        // Bind the parameters
        $params = array_merge(array_values($data), array_values($conditions));
        $types = str_repeat('s', count($params)); // Assuming all parameters are strings, adjust as needed
        $stmt->bind_param($types, ...$params);

        // Execute the statement
        if ($stmt->execute()) {
            $stmt->close();
            return true;
        } else {
            $stmt->close();
            return false;
        }
    } else {
        return false;
    }
}

?>