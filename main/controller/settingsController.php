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


//query all payment methods
if (isset($inputData['queryAllPaymentMethods']) && $inputData['queryAllPaymentMethods'] === true) {
    // Query to fetch all payment methods
    $query = "SELECT `Id`, `paymentMethodName`, `qrLink`, `paymentNumber` FROM `payment_methods`";

    $result = $conn->query($query); // Assuming $conn is your database connection

    if ($result && $result->num_rows > 0) {
        $paymentMethods = [];
        while ($row = $result->fetch_assoc()) {
            $paymentMethods[] = $row; // Add each row to the payment methods array
        }

        // Respond with the payment methods in JSON format
        $response = [
            'status' => 'success',
            'paymentMethods' => $paymentMethods,
        ];
    } else {
        // Handle case where no payment methods are found
        $response = [
            'status' => 'error',
            'message' => 'No payment methods found.',
        ];
    }

    // Return the response as JSON
    echo json_encode($response);
}


if (isset($inputData['paymentMethodDml']) && $inputData['paymentMethodDml'] === true) {
    $paymentMethodName = isset($inputData['paymentMethodNameField']) ? $inputData['paymentMethodNameField'] : null;
    $paymentMethodNumber = isset($inputData['paymentMethodNumberField']) ? $inputData['paymentMethodNumberField'] : null;
    $paymentMethodImageLink = isset($inputData['paymentMethodImageLink']) ? $inputData['paymentMethodImageLink'] : null;
    $toUpdatePaymentMethodId = isset($inputData['toUpdatePaymentMethodId']) ? $inputData['toUpdatePaymentMethodId'] : null;
    $isDelete = isset($inputData['deleteThisPaymentMethod']) && $inputData['deleteThisPaymentMethod'] === true;

    if ($toUpdatePaymentMethodId) {
        if ($isDelete) {
            // DELETE Operation
            $deleteSql = "DELETE FROM `payment_methods` WHERE `Id` = ?";
            if ($stmt = $conn->prepare($deleteSql)) {
                $stmt->bind_param("i", $toUpdatePaymentMethodId);
                if ($stmt->execute()) {
                    $response = [
                        'status' => 'success',
                        'message' => 'Payment method deleted successfully.'
                    ];
                } else {
                    $response = [
                        'status' => 'error',
                        'message' => 'Failed to delete payment method: ' . $conn->error
                    ];
                }
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Failed to prepare DELETE statement: ' . $conn->error
                ];
            }
        } else {
            // UPDATE Operation
            $updateFields = [];
            $updateParams = [];
            $paramTypes = "";

            if ($paymentMethodName !== null) {
                $updateFields[] = "`paymentMethodName` = ?";
                $updateParams[] = $paymentMethodName;
                $paramTypes .= "s";
            }
            if ($paymentMethodNumber !== null) {
                $updateFields[] = "`paymentNumber` = ?";
                $updateParams[] = $paymentMethodNumber;
                $paramTypes .= "s";
            }
            if ($paymentMethodImageLink !== null) {
                $updateFields[] = "`qrLink` = ?";
                $updateParams[] = $paymentMethodImageLink;
                $paramTypes .= "s";
            }

            if (!empty($updateFields)) {
                $updateSql = "UPDATE `payment_methods` SET " . implode(", ", $updateFields) . " WHERE `Id` = ?";
                $updateParams[] = $toUpdatePaymentMethodId;
                $paramTypes .= "i";

                if ($stmt = $conn->prepare($updateSql)) {
                    $stmt->bind_param($paramTypes, ...$updateParams);
                    if ($stmt->execute()) {
                        $response = [
                            'status' => 'success',
                            'message' => 'Payment method updated successfully.'
                        ];
                    } else {
                        $response = [
                            'status' => 'error',
                            'message' => 'Failed to update payment method: ' . $stmt->error
                        ];
                    }
                } else {
                    $response = [
                        'status' => 'error',
                        'message' => 'Failed to prepare UPDATE statement: ' . $conn->error
                    ];
                }
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'No fields to update.'
                ];
            }
        }
    } else {
        // INSERT Operation
        $insertSql = "INSERT INTO `payment_methods` (`paymentMethodName`, `paymentNumber`, `qrLink`) VALUES (?, ?, ?)";
        if ($stmt = $conn->prepare($insertSql)) {
            $stmt->bind_param("sss", $paymentMethodName, $paymentMethodNumber, $paymentMethodImageLink);
            if ($stmt->execute()) {
                $response = [
                    'status' => 'success',
                    'message' => 'Payment method added successfully.',
                    'insertedId' => $stmt->insert_id
                ];
            } else {
                $response = [
                    'status' => 'error',
                    'message' => 'Failed to insert payment method: ' . $stmt->error
                ];
            }
        } else {
            $response = [
                'status' => 'error',
                'message' => 'Failed to prepare INSERT statement: ' . $conn->error
            ];
        }
    }

    // Return the response as JSON
    echo json_encode($response);
}



?>