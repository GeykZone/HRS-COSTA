<?php 

if (file_exists('../../connection/connect.php')) {
    include('../../connection/connect.php');
};

// Database connection info 
$dbDetails = array( 
    'host' => $hostname, 
    'user' => $username , 
    'pass' => $password, 
    'db'   => $database
); 

function formatCurrency($amount) {
    return '₱' . number_format($amount, 2);
}

$timezone = new DateTimeZone('Asia/Manila');
$date = new DateTime('now', $timezone);
$currentDate = $date->format('Y-m-d');

if(isset($_GET['dashboardBookingDetailsTable'])){
    // DB table to use 
    $table = 'check_ins'; 

    // Table's primary key 
    $primaryKey = 'Id';
    // $query_btn = $_GET['query_btn'];
    
    // Array of database columns which should be read and sent back to DataTables.
    // The `db` parameter represents the column name in the database.
    // The `dt` parameter represents the DataTables column identifier.
    $columns = array(
        array('db' => '(r.name)', 'dt' => 0, 'field' => 'roomName'),
        array('db' => 'totalAmount', 'dt' => 1, 'field' => 'totalAmount', 'formatter' => function($d, $row) {
            return formatCurrency($d);
        }),
        array('db' => 'latestModifiedDate', 'dt' => 2, 'field' => 'latestModifiedDate'),
        array('db' => 'checkInDate', 'dt' => 3, 'field' => 'checkInDate'),
        array('db' => 'checkOutDate', 'dt' => 4, 'field' => 'checkOutDate'),
        array('db' => 'checkInQuantity', 'dt' => 5, 'field' => 'checkInQuantity'),
        array('db' => 'customerfullName', 'dt' => 6, 'field' => 'customerfullName'),
        array('db' => 'status', 'dt' => 7, 'field' => 'status', 'formatter' => function($d, $row) {
            return ucwords($d);
        })
    );
    
    // Include SQL query processing class 
    require 'ssp.class.php'; 

    $joinQuery = ", r.name AS roomName, ci.roomId AS roomId, ci.checkInDate AS checkInDate, ci.checkOutDate AS checkOutDate, ci.userId AS userId, ci.queueDateTime AS queueDateTime, ci.status AS status, ci.checkInQuantity AS checkInQuantity, 
    ci.paymentMethodId AS paymentMethodId, ci.totalAmount AS totalAmount, ci.customerfullName AS customerfullName, ci.customerCompleteAddress AS customerCompleteAddress, 
    ci.customerContactInfo AS customerContactInfo, ci.notificationStatus AS notificationStatus, ci.message AS message, ci.multiBookId AS multiBookId, ci.createdDate AS createdDate
    FROM `{$table}` AS ci 
    LEFT JOIN rooms AS r ON ci.roomId = r.Id ";
    $where = " latestModifiedDate = '$currentDate' AND status = 'approved'";

    // Output data as json format 
    echo json_encode( SSP::simple( $_GET, $dbDetails, $table, $primaryKey, $columns, $joinQuery, $where) );
    // print json_encode($where);
}

if(isset($_GET['bookingDetailsTable'])){

    $UserRole = $_GET['UserRole'];
    $customerId = $_GET['customerId'];

    $filterCheck_InStartDate = isset($_GET['filterCheck_InStartDate']) ? $_GET['filterCheck_InStartDate'] : null;
    $filterCheck_InEndDate = isset($_GET['filterCheck_InEndDate']) ? $_GET['filterCheck_InEndDate'] : null;
    $filterCheck_OutStartDate = isset($_GET['filterCheck_OutStartDate']) ? $_GET['filterCheck_OutStartDate'] : null;
    $filterCheck_OutEndDate = isset($_GET['filterCheck_OutEndDate']) ? $_GET['filterCheck_OutEndDate'] : null;
    $filterRoomName = isset($_GET['filterRoomName']) ? $_GET['filterRoomName'] : null;
    $filterCustomer = isset($_GET['filterCustomer']) ? $_GET['filterCustomer'] : null;
    $filterTotalPrice = isset($_GET['filterTotalPrice']) ? $_GET['filterTotalPrice'] : null;
    $totalPriceOperator = isset($_GET['totalPriceOperator']) ? $_GET['totalPriceOperator'] : null;
    $bookedQuantityOperator = isset($_GET['bookedQuantityOperator']) ? $_GET['bookedQuantityOperator'] : null;
    $filterBookedQuantity = isset($_GET['filterBookedQuantity']) ? $_GET['filterBookedQuantity'] : null;
    $filterPaymentIsPartital = isset($_GET['filterPaymentIsPartital']) ? $_GET['filterPaymentIsPartital'] : null;
    $filterBookingStatus = isset($_GET['filterBookingStatus']) ? $_GET['filterBookingStatus'] : null;
    $filterProcessStartDate = isset($_GET['filterProcessStartDate']) ? $_GET['filterProcessStartDate'] : null;
    $filterProcessEndDate = isset($_GET['filterProcessEndDate']) ? $_GET['filterProcessEndDate'] : null;
    $filterLastProcessStartDate = isset($_GET['filterLastProcessStartDate']) ? $_GET['filterLastProcessStartDate'] : null;
    $filterLastProcessEndDate = isset($_GET['filterLastProcessEndDate']) ? $_GET['filterLastProcessEndDate'] : null;
    
    function isPartialOrNOt($d) {
        if($d ==  1){
            return 'True';
        }
        return 'False';
    }

    // DB table to use 
    $table = 'check_ins'; 

    // Table's primary key 
    $primaryKey = 'Id';
    // $query_btn = $_GET['query_btn'];

    // Array of database columns which should be read and sent back to DataTables.
    // The `db` parameter represents the column name in the database.
    // The `dt` parameter represents the DataTables column identifier.
    $columns = array(
        array('db' => '(r.name)', 'dt' => 0, 'field' => 'roomName'),
        array('db' => 'customerfullName', 'dt' => 1, 'field' => 'customerfullName'),
        array('db' => 'totalAmount', 'dt' => 2, 'field' => 'totalAmount', 'formatter' => function($d, $row) {return formatCurrency($d);}),
        array('db' => 'checkInDate', 'dt' => 3, 'field' => 'checkInDate'),
        array('db' => 'checkOutDate', 'dt' => 4, 'field' => 'checkOutDate'),
        array('db' => 'checkInQuantity', 'dt' => 5, 'field' => 'checkInQuantity'),
        array('db' => 'isPartial', 'dt' => 6, 'field' => 'isPartial', 'formatter' => function($d, $row) {return isPartialOrNOt($d);}),
        array('db' => 'status', 'dt' => 7, 'field' => 'status', 'formatter' => function($d, $row) {return ucwords($d);}),
        array('db' => 'createdDate', 'dt' => 8, 'field' => 'createdDate'),
        array('db' => 'latestModifiedDate', 'dt' => 9, 'field' => 'latestModifiedDate'),
        array('db' => 'ci.Id', 'dt' => 10, 'field' => 'Id'),
    );

     // Include SQL query processing class 
     require 'ssp.class.php'; 

     $joinQuery = ", ci.Id, r.name AS roomName, ci.roomId AS roomId, ci.checkInDate AS checkInDate, ci.checkOutDate AS checkOutDate, ci.userId AS userId, ci.queueDateTime AS queueDateTime, ci.status AS status, ci.checkInQuantity AS checkInQuantity, 
     ci.paymentMethodId AS paymentMethodId, ci.totalAmount AS totalAmount, ci.customerfullName AS customerfullName, ci.customerCompleteAddress AS customerCompleteAddress, 
     ci.customerContactInfo AS customerContactInfo, ci.notificationStatus AS notificationStatus, ci.message AS message, ci.multiBookId AS multiBookId, ci.createdDate AS createdDate,
     ci.isPartial AS isPartial, ci.partialPayment AS partialPayment, ci.latestModifiedDate AS latestModifiedDate
     FROM `{$table}` AS ci 
     LEFT JOIN rooms AS r ON ci.roomId = r.Id ";
     $where = "(status = 'approved' OR status = 'rejected' OR status = 'cancelled' OR status = 'pending')";

    // Check if the UserRole is not equal to 'admin'
    if ($UserRole != 'admin') {
        // Add the condition to filter by customerId
        $where .= " AND userId = '{$customerId}'";
    }

    // Add conditions based on the filter parameters
    if ($filterCheck_InStartDate) {
        $where .= " AND ci.checkInDate >= '{$filterCheck_InStartDate}'";
    }

    if ($filterCheck_InEndDate) {
        $where .= " AND ci.checkInDate <= '{$filterCheck_InEndDate}'";
    }

    if ($filterCheck_OutStartDate) {
        $where .= " AND ci.checkOutDate >= '{$filterCheck_OutStartDate}'";
    }

    if ($filterCheck_OutEndDate) {
        $where .= " AND ci.checkOutDate <= '{$filterCheck_OutEndDate}'";
    }

    if ($filterRoomName) {
        $where .= " AND r.name LIKE '%{$filterRoomName}%'";
    }

    if ($filterCustomer) {
        $where .= " AND ci.customerfullName LIKE '%{$filterCustomer}%'";
    }

    if ($filterTotalPrice != null) {
        $operator = $totalPriceOperator ? $totalPriceOperator : '=';
        if ($operator == '==') {
            $operator = '=';
        }
        // Convert filterTotalPrice to a float
        $filterTotalPriceFloat = (float) $filterTotalPrice;

        $where .= " AND ROUND(ci.totalAmount, 2) {$operator} {$filterTotalPriceFloat}";
    }

    if ($filterBookedQuantity) {
        $operator = $bookedQuantityOperator ? $bookedQuantityOperator : '=';
        if ($operator == '==') {
            $operator = '=';
        }
        $where .= " AND ci.checkInQuantity {$operator} {$filterBookedQuantity}";
    }

    if ($filterPaymentIsPartital) {
        $where .= " AND ci.isPartial = '{$filterPaymentIsPartital}'";
    }

    if ($filterBookingStatus) {
        $where .= " AND ci.status = '{$filterBookingStatus}'";
    }

    if ($filterProcessStartDate) {
        $where .= " AND ci.createdDate >= '{$filterProcessStartDate}'";
    }

    if ($filterProcessEndDate) {
        $where .= " AND ci.createdDate <= '{$filterProcessEndDate}'";
    }

    if ($filterLastProcessStartDate) {
        $where .= " AND ci.latestModifiedDate >= '{$filterLastProcessStartDate}'";
    }

    if ($filterLastProcessEndDate) {
        $where .= " AND ci.latestModifiedDate <= '{$filterLastProcessEndDate}'";
    }
 
     // Output data as json format 
     echo json_encode( SSP::simple( $_GET, $dbDetails, $table, $primaryKey, $columns, $joinQuery, $where) );

    //  echo $where;
}

if(isset($_GET['paymentDetailsTable'])){

    $UserRole = $_GET['UserRole'];
    $customerId = $_GET['customerId'];

    $filterCustomer = isset($_GET['filterCustomer']) ? $_GET['filterCustomer'] : null;
    $filterPaymentMethod = isset($_GET['filterPaymentMethod']) ? $_GET['filterPaymentMethod'] : null;
    $filterTotalPrice = isset($_GET['filterTotalPrice']) ? $_GET['filterTotalPrice'] : null;
    $totalPriceOperator = isset($_GET['totalPriceOperator']) ? $_GET['totalPriceOperator'] : null;
    $bookedQuantityOperator = isset($_GET['bookedQuantityOperator']) ? $_GET['bookedQuantityOperator'] : null;
    $filterBookedQuantity = isset($_GET['filterBookedQuantity']) ? $_GET['filterBookedQuantity'] : null;
    $filterPaymentIsPartital = isset($_GET['filterPaymentIsPartital']) ? $_GET['filterPaymentIsPartital'] : null;
    $filterPaymentIsMultiBooked = isset($_GET['filterPaymentIsMultiBooked']) ? $_GET['filterPaymentIsMultiBooked'] : null;
    $filterPaymentStatus = isset($_GET['filterPaymentStatus']) ? $_GET['filterPaymentStatus'] : null;
    $filterLastProcessStartDate = isset($_GET['filterLastProcessStartDate']) ? $_GET['filterLastProcessStartDate'] : null;
    $filterLastProcessEndDate = isset($_GET['filterLastProcessEndDate']) ? $_GET['filterLastProcessEndDate'] : null;
    $filterPartialAmount = isset($_GET['filterPartialAmount']) ? $_GET['filterPartialAmount'] : null;
    $partialAmountOperator = isset($_GET['partialAmountOperator']) ? $_GET['partialAmountOperator'] : null;
    
    function isPartialOrNOt($d) {
        if($d ==  1){
            return 'True';
        }
        return 'False';
    }

    function getTotalAmount($Id) {

        global $conn;
        $response = 'empty';
        $response_id =  0;
        $response_mbId = 0;
    
        // Query to get specific records based on multiBookId
        $query = "
            SELECT Id, multiBookId FROM check_ins WHERE Id = ?
        ";
    
        // Prepare and execute the query
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param('i', $Id); // Bind multiBookId as an integer
    
            if ($stmt->execute()) {
                // Bind result variables
                $stmt->bind_result($Id, $multiBookId);
                if ($stmt->fetch()) {
                    $response_mbId = $multiBookId;
                    $response_id = $Id;
                } 
            }
            
            $stmt->close();
        }
    
    
        if($response_mbId != 0){
            $multiBookQuery = "
                SELECT 
                mb.totalAmount AS totalAmount
                FROM multibook AS mb
                LEFT JOIN check_ins AS ci ON  ci.multiBookId  =  mb.id
                WHERE mb.id = ?
            ";
    
            if ($stmt = $conn->prepare($multiBookQuery)) {
                $stmt->bind_param('i', $response_mbId); // Bind multiBookId as an integer
    
                if ($stmt->execute()) {
                    $stmt->bind_result($totalAmount);
                    if ($stmt->fetch()) {
                        $response =  $totalAmount;
                    }
                } 
            }
            $stmt->close();
        }
        else{
            $singleBookQuery = "
                SELECT 
                totalAmount
                FROM check_ins
                WHERE Id = ?
            ";
    
            if ($stmt = $conn->prepare($singleBookQuery)) {
                $stmt->bind_param('i', $response_id); // Bind multiBookId as an integer
    
                if ($stmt->execute()) {
                    $stmt->bind_result($totalAmount);
                    if ($stmt->fetch()) {
                        $response =  $totalAmount;
                    }
                } 
            }
            $stmt->close();
        }
    
        // Output response as JSON
        return($response);
    
    }

    function getPartialAmount($Id) {

        global $conn;
        $response = 'empty';
        $response_id =  0;
        $response_mbId = 0;
    
        // Query to get specific records based on multiBookId
        $query = "
            SELECT Id, multiBookId FROM check_ins WHERE Id = ?
        ";
    
        // Prepare and execute the query
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param('i', $Id); // Bind multiBookId as an integer
    
            if ($stmt->execute()) {
                // Bind result variables
                $stmt->bind_result($Id, $multiBookId);
                if ($stmt->fetch()) {
                    $response_mbId = $multiBookId;
                    $response_id = $Id;
                } 
            }
            
            $stmt->close();
        }
    
    
        if($response_mbId != 0){
            $multiBookQuery = "
                SELECT 
                mb.partialPayment AS partialPayment
                FROM multibook AS mb
                LEFT JOIN check_ins AS ci ON  ci.multiBookId  =  mb.id
                WHERE mb.id = ?
            ";
    
            if ($stmt = $conn->prepare($multiBookQuery)) {
                $stmt->bind_param('i', $response_mbId); // Bind multiBookId as an integer
    
                if ($stmt->execute()) {
                    $stmt->bind_result($partialPayment);
                    if ($stmt->fetch()) {
                        $response =  $partialPayment;
                    }
                } 
            }
            $stmt->close();
        }
        else{
            $singleBookQuery = "
                SELECT 
                partialPayment
                FROM check_ins
                WHERE Id = ?
            ";
    
            if ($stmt = $conn->prepare($singleBookQuery)) {
                $stmt->bind_param('i', $response_id); // Bind multiBookId as an integer
    
                if ($stmt->execute()) {
                    $stmt->bind_result($partialPayment);
                    if ($stmt->fetch()) {
                        $response =  $partialPayment;
                    }
                } 
            }
            $stmt->close();
        }
    
        // Output response as JSON
        return($response);
    
    }

    function getTotalBooked($Id) {

        global $conn;
        $response = 'empty';
        $response_id =  0;
        $response_mbId = 0;
    
        // Query to get specific records based on multiBookId
        $query = "
            SELECT Id, multiBookId FROM check_ins WHERE Id = ?
        ";
    
        // Prepare and execute the query
        if ($stmt = $conn->prepare($query)) {
            $stmt->bind_param('i', $Id); // Bind multiBookId as an integer
    
            if ($stmt->execute()) {
                // Bind result variables
                $stmt->bind_result($Id, $multiBookId);
                if ($stmt->fetch()) {
                    $response_mbId = $multiBookId;
                    $response_id = $Id;
                } 
            }
            
            $stmt->close();
        }
    
    
        if($response_mbId != 0){
            $multiBookQuery = "
                SELECT 
                SUM(ci.checkInQuantity) AS checkInQuantity
                FROM multibook AS mb
                LEFT JOIN check_ins AS ci ON  ci.multiBookId  =  mb.id
                WHERE mb.id = ?
            ";
    
            if ($stmt = $conn->prepare($multiBookQuery)) {
                $stmt->bind_param('i', $response_mbId); // Bind multiBookId as an integer
    
                if ($stmt->execute()) {
                    $stmt->bind_result($checkInQuantity);
                    if ($stmt->fetch()) {
                        $response =  $checkInQuantity;
                    }
                } 
            }
            $stmt->close();
        }
        else{
            $singleBookQuery = "
                SELECT 
                checkInQuantity
                FROM check_ins
                WHERE Id = ?
            ";
    
            if ($stmt = $conn->prepare($singleBookQuery)) {
                $stmt->bind_param('i', $response_id); // Bind multiBookId as an integer
    
                if ($stmt->execute()) {
                    $stmt->bind_result($checkInQuantity);
                    if ($stmt->fetch()) {
                        $response =  $checkInQuantity;
                    }
                } 
            }
            $stmt->close();
        }
    
        // Output response as JSON
        return($response);
    
    }

    // DB table to use 
    $table = 'check_ins'; 

    // Table's primary key 
    $primaryKey = 'Id';
    // $query_btn = $_GET['query_btn'];

    // Array of database columns which should be read and sent back to DataTables.
    // The `db` parameter represents the column name in the database.
    // The `dt` parameter represents the DataTables column identifier.
    $columns = array(
        array('db' => 'customerfullName', 'dt' => 0, 'field' => 'customerfullName'),
        array('db' => 'pm.paymentMethodName', 'dt' => 1, 'field' => 'paymentMethodName'),
        array('db' => 'ci.Id', 'dt' => 2, 'field' => 'Id', 'formatter' => function($d, $row) {return formatCurrency(getTotalAmount($d));}),
        array('db' => 'ci.Id', 'dt' => 3, 'field' => 'Id', 'formatter' => function($d, $row) {return getTotalBooked($d);}),
        array('db' => 'multiBookId', 'dt' => 4, 'field' => 'multiBookId', 'formatter' => function($d, $row) { if($d != 0){return 'True';}return 'False';}),
        array('db' => 'isPartial', 'dt' => 5, 'field' => 'isPartial', 'formatter' => function($d, $row) {return isPartialOrNOt($d);}),
        array('db' => 'ci.Id', 'dt' => 6, 'field' => 'Id', 'formatter' => function($d, $row) {return formatCurrency(getPartialAmount($d));}),
        array('db' => 'status', 'dt' => 7, 'field' => 'status'),
        array('db' => 'latestModifiedDate', 'dt' => 8, 'field' => 'latestModifiedDate'),
        array('db' => 'ci.Id', 'dt' => 9, 'field' => 'Id'),
    );

     // Include SQL query processing class 
     require 'ssp.class.php'; 

     $joinQuery = ", r.name AS roomName, ci.Id, ci.roomId AS roomId, ci.checkInDate AS checkInDate, ci.checkOutDate AS checkOutDate, ci.userId AS userId, ci.queueDateTime AS queueDateTime, ci.status AS status, ci.checkInQuantity AS checkInQuantity, 
     ci.paymentMethodId AS paymentMethodId, pm.paymentMethodName AS paymentMethodName, ci.totalAmount, mb.totalAmount, ci.customerfullName AS customerfullName, ci.customerCompleteAddress AS customerCompleteAddress, 
     ci.customerContactInfo AS customerContactInfo, ci.notificationStatus AS notificationStatus, ci.message AS message, ci.multiBookId AS multiBookId, ci.createdDate AS createdDate,
     ci.isPartial AS isPartial, ci.partialPayment AS partialPayment, mb.partialPayment, ci.latestModifiedDate AS latestModifiedDate
     FROM `{$table}` AS ci 
     LEFT JOIN multibook AS mb ON mb.id = ci.multiBookId 
     LEFT JOIN rooms AS r ON ci.roomId = r.Id      
     LEFT JOIN payment_methods AS pm ON ci.paymentMethodId = pm.Id ";;
     $where = "(status = 'approved' OR status = 'rejected' OR status = 'cancelled' OR status = 'pending') ";

    // Check if the UserRole is not equal to 'admin'
    if ($UserRole != 'admin') {
        // Add the condition to filter by customerId
        $where .= " AND userId = '{$customerId}'";
    }

    // Add conditions based on the filter parameters
    if ($filterCustomer) {
        $where .= " AND ci.customerfullName LIKE '%{$filterCustomer}%'";
    }

    if($filterPaymentMethod) {
        $where .= " AND ci.paymentMethodId = '{$filterPaymentMethod}'";
    }

    if ($filterTotalPrice != null) {
        $operator = $totalPriceOperator ? $totalPriceOperator : '=';
        if ($operator == '==') {
            $operator = '=';
        }
        // Convert filterTotalPrice to a float
        $filterTotalPriceFloat = (float) $filterTotalPrice;

        $where .= "
                AND (CASE 
                        WHEN ci.multiBookId != 0 THEN ROUND(mb.totalAmount, 2) 
                        ELSE ROUND(ci.totalAmount, 2) 
                    END 
                    {$operator} {$filterTotalPriceFloat})
            ";

    }


    if ($filterPartialAmount != null) {
        $operator = $partialAmountOperator ? $partialAmountOperator : '=';
        if ($operator == '==') {
            $operator = '=';
        }
        // Convert filterPartialAmount to a float
        $filterPartialAmountFloat = (float) $filterPartialAmount;

        $where .= "
                AND (CASE 
                        WHEN ci.multiBookId != 0 THEN ROUND(mb.partialPayment, 2) 
                        ELSE ROUND(ci.partialPayment, 2) 
                    END 
                    {$operator} {$filterPartialAmountFloat})
            ";

    }

    if ($filterBookedQuantity) {
        $operator = $bookedQuantityOperator ? $bookedQuantityOperator : '=';
        if ($operator == '==') {
            $operator = '=';
        }
        $where .= "
            AND (CASE 
                    WHEN ci.multiBookId != 0 
                        THEN (SELECT SUM(ci2.checkInQuantity) 
                            FROM check_ins AS ci2 
                            WHERE ci2.multiBookId = ci.multiBookId)
                    ELSE ci.checkInQuantity
                END 
                {$operator} {$filterBookedQuantity})
        ";

    }

    if ($filterPaymentIsPartital) {
        $where .= " AND ci.isPartial = '{$filterPaymentIsPartital}'";
    }

    if ($filterPaymentIsMultiBooked) {

        if($filterPaymentIsMultiBooked == '1'){
            $where .= " AND ci.multiBookId > 0";
        }
        else if($filterPaymentIsMultiBooked == '0'){
            $where .= " AND ci.multiBookId  < 1";
        }
        
    }

    if ($filterPaymentStatus) {
        $where .= " AND ci.status = '{$filterPaymentStatus}'";
    }

    if ($filterLastProcessStartDate) {
        $where .= " AND ci.latestModifiedDate >= '{$filterLastProcessStartDate}'";
    }

    if ($filterLastProcessEndDate) {
        $where .= " AND ci.latestModifiedDate <= '{$filterLastProcessEndDate}'";
    }

    $where .= " -- Ensure that multiBookId is not repeated except for 0
    GROUP BY 
        CASE 
            WHEN ci.multiBookId != 0 THEN ci.multiBookId 
            ELSE ci.Id 
        END";
 
     // Output data as json format 
     echo json_encode( SSP::simple( $_GET, $dbDetails, $table, $primaryKey, $columns, $joinQuery, $where) );

    //  echo $where;
}

?>