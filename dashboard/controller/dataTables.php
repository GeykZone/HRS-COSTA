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

$date = new DateTime();
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
        array('db' => 'createdDate', 'dt' => 2, 'field' => 'createdDate'),
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
    $where = " (createdDate = '$currentDate' OR latestModifiedDate = '$currentDate') AND status = 'approved'";

    // Output data as json format 
    echo json_encode( SSP::simple( $_GET, $dbDetails, $table, $primaryKey, $columns, $joinQuery, $where) );
    // print json_encode($where);
}

?>