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

?>