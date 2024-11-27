if(document.getElementById('dashboardBookingDetailsTable')){
  function dashboardBookingDetailsTable(){
      var ajax_url = "controller/dataTables.php";
    
      if ( ! $.fn.DataTable.isDataTable( '#dashboardBookingDetailsTable' ) ) { // check if data table is already exist
    
      table = $('#dashboardBookingDetailsTable').DataTable({
    
        // "processing": true,
        "deferRender": true,
        "serverSide": true,
        "ajax": {
            url: ajax_url,
            data: {
              dashboardBookingDetailsTable: true
            },
            "dataSrc": function ( json ) {
              //Make your callback here.
            // console.log(json.data)
            return json.data;
          }      
          
        },
        order: [[0,'asc']],
        
        responsive: true,
        fixedHeader: true,
        dom: 'rtip',
        pageLength : 5,
        "lengthMenu": [[5, 10, 20, 50, 100], [5, 10, 20, 50, 100]],
    
        //disable the sorting of colomn
          // "columnDefs": [ {
          // "targets": 6,
          // "orderable": false
          // } ],
    
          "language": {
            "info": "Showing _START_ to _END_ of _TOTAL_ entries",
            "infoFiltered":""
          },
    
        "columns": [
          null,
          null,
          null,
          null,
          null,
          null,
          null,
          null,
        ],
      });
      
      }
  };
  dashboardBookingDetailsTable()
}

if(document.getElementById('bookingDetailsTable')){

  function bookingDetailsTable(){
    var ajax_url = "controller/dataTables.php";

    let bookingTableData = {
      bookingDetailsTable: true,
      customerId: customerId,
      UserRole:UserRole,
    }

    if(Object.keys(filterableValues).length > 0){

      for (let [key, value] of Object.entries(filterableValues)) {
        bookingTableData[ `${key}`] = value;
      }

      console.log('table =>', bookingTableData)
     
    }

  
  
    if ( ! $.fn.DataTable.isDataTable( '#bookingDetailsTable' ) ) { // check if data table is already exist
  
    table = $('#bookingDetailsTable').DataTable({
  
      // "processing": true,
      "deferRender": true,
      "serverSide": true,
      searching: false, // Disable default server-side search
      "ajax": {
          url: ajax_url,
          data: bookingTableData,
          "dataSrc": function ( json ) {
          //   //Make your callback here.
          // console.log(json)
          return json.data;
        }      
        
      },
      order: [[9,'desc']],
      
      responsive: true,
      fixedHeader: true,
      dom: 'Bfrtip',
      pageLength : 10,
      buttons: [
        {
            extend: 'excel',
            text: 'Export Excel',
            className: 'export-excel-btn',
            exportOptions: {
                // Specify columns to be included (0 to 9 in this case)
                columns: function (idx, data, node) {
                    // Include columns 0 to 9
                    return idx >= 0 && idx <= 8;
                }
              }
          }
      ],
      "lengthMenu": [[5, 10, 20, 50, 100], [5, 10, 20, 50, 100]],
  
      //disable the sorting of colomn
        "columnDefs": [ {
        "targets": 0,
        "orderable": false
        } ],
  
        "language": {
          "info": "Showing _START_ to _END_ of _TOTAL_ entries",
          "infoFiltered":""
        },
  
      "columns": [
        {
          "targets": 0,
          "render": function ( data, type, row, meta ) {
            return `<span class="clickable costa-btn-a" id="${data}" onClick="moreDetails(this.id)" >More Details</span>`
          },
          
        },
        null,
        null,
        null,
        null,
        null,
        null,
        null,
        null,
        null,
      ],
    });
    
    }
  };
  bookingDetailsTable();

  // Select the span element
  var spanElement = document.getElementById('bookingsOpenFilterModalBtn');

  // Select the div element
  var targetDiv = document.querySelector('.dt-buttons');

  // Check if both elements exist
  if (spanElement && targetDiv) {
      // Move the span element inside the target div
      targetDiv.appendChild(spanElement);
  } else {
      console.error('Element(s) not found');
  }

}

if(document.getElementById('paymentDetailsTable')){

  function paymentDetailsTable(){
    var ajax_url = "controller/dataTables.php";

    let bookingTableData = {
      paymentDetailsTable: true,
      customerId: customerId,
      UserRole:UserRole,
    }

    if(Object.keys(filterableValues).length > 0){

      for (let [key, value] of Object.entries(filterableValues)) {
        bookingTableData[ `${key}`] = value;
      }

      console.log('table =>', bookingTableData)
     
    }
  
    if ( ! $.fn.DataTable.isDataTable( '#paymentDetailsTable' ) ) { // check if data table is already exist
      let tableDataVar;
  
    table = $('#paymentDetailsTable').DataTable({
  
      // "processing": true,
      "deferRender": true,
      "serverSide": true,
      "ajax": {
          url: ajax_url,
          data: bookingTableData,
          "dataSrc": function ( json ) {
          //   //Make your callback here.
          tableDataVar = json.data;
          return json.data;
        }      
        
      },
      order: [[7,'desc']],
      
      responsive: true,
      fixedHeader: true,
      searching: false, // Disable default server-side search
      dom: 'Bfrtip',
      pageLength : 100,
      buttons: [
        {
            extend: 'excel',
            text: 'Export Excel',
            className: 'export-excel-btn',
            exportOptions: {
                // Specify columns to be included (0 to 8 in this case)
                columns: function (idx, data, node) {
                    // Include columns 0 to 8
                    return idx >= 0 && idx <= 7;
                }
              }
          }
      ],
      "lengthMenu": [[5, 10, 20, 50, 100], [5, 10, 20, 50, 100]],
  
      //disable the sorting of colomn
        // "columnDefs": [ {
        // "targets": 9,
        // "orderable": false
        // } ],
  
        "language": {
          "info": "Showing _START_ to _END_ of _TOTAL_ entries",
          "infoFiltered":""
        },
  
      "columns": [
        null,
        null,
        null,
        null,
        null,
        null,
        null,
        null
      ],
    });    
    }
  };
  paymentDetailsTable();

  // Select the span element
  var spanElement = document.getElementById('paymentsOpenFilterModalBtn');

  // Select the div element
  var targetDiv = document.querySelector('.dt-buttons');

  // Check if both elements exist
  if (spanElement && targetDiv) {
      // Move the span element inside the target div
      targetDiv.appendChild(spanElement);
  } else {
      console.error('Element(s) not found');
  }

}

if(document.getElementById('revenueTable')){

  function paymentDetailsTable(){
    var ajax_url = "controller/dataTables.php";

    let bookingTableData = {
      showRevenueReport: true,
      reportYear: defaultYearSet,
      timeframe: defaultRevenueType
    }
  
    if ( ! $.fn.DataTable.isDataTable( '#revenueTable' ) ) { // check if data table is already exist
      let tableDataVar;
  
    table = $('#revenueTable').DataTable({
  
      // "processing": true,
      "deferRender": true,
      "serverSide": true,
      "ajax": {
          url: ajax_url,
          data: bookingTableData,
          "dataSrc": function ( json ) {
          //   //Make your callback here.
          tableDataVar = json.data;
          console.log(tableDataVar)
          return json.data;
        }      
        
      },
      order: [[0,'desc']],
      
      responsive: true,
      fixedHeader: true,
      searching: false, // Disable default server-side search
      dom: 'Bfrtip',
      pageLength : 100,
      buttons: [
        {
            extend: 'excel',
            text: 'Export Excel',
            title: defaultRevenueType + ' Revenue '+defaultYearSet,
            className: 'export-excel-btn',
            exportOptions: {
                // Specify columns to be included (0 to 8 in this case)
                columns: function (idx, data, node) {
                    // Include columns 0 to 8
                    return idx >= 0 && idx <= 2;
                }
              }
          }
      ],
      "lengthMenu": [[5, 10, 20, 50, 100], [5, 10, 20, 50, 100]],
  
      //disable the sorting of colomn
        // "columnDefs": [ {
        // "targets": 9,
        // "orderable": false
        // } ],
  
        "language": {
          "info": "Showing _START_ to _END_ of _TOTAL_ entries",
          "infoFiltered":""
        },
  
      "columns": [
        null,
        null,
        null
      ],
    });    
    }
  };
  paymentDetailsTable();

  // Select the span element
  const revenueContainer = document.getElementById('revenueContainer');

  // Select the div element
  var targetDiv = revenueContainer.querySelector('.dt-buttons');

  // Check if both elements exist
  if (dailyRevenu && weeklyRevenue && monthlyRevenue && targetDiv) {
      // Move the span element inside the target div
      targetDiv.appendChild(dailyRevenu);
      targetDiv.appendChild(weeklyRevenue);
      targetDiv.appendChild(monthlyRevenue);
  } else {
      console.error('Element(s) not found');
  }

}
