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

    if ($filterTotalPrice) {
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

?>