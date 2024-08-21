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
           console.log(json.data)
           return json.data;
        }      
        
      },
      order: [[0,'asc']],
      
      responsive: true,
      fixedHeader: true,
      dom: 'Bfrtip',
      buttons: [
        {
          extend: 'excel',
          text: 'Export Excel',
          className: 'btn rounded-3',
          exportOptions: {
              columns: 'th:not(:last-child)'
          }
        },
        {
          extend: 'print',
          text: 'Print Table',
          className: 'btn rounded-3',
    
          title: 'HRS-COSTA',
    
          messageTop: `Booking Details`,
              //className: 'fa fa-print'
            //className: 'fa fa-print',
            
    
            exportOptions: {
            modifier: {
                page: 'current'
            },
              //columns: [0, 1] //r.broj kolone koja se stampa u PDF
              columns: [0,1,2,3,4,5,6,7],
              // optional space between columns
              columnGap: 1
            },
    
            customize: function ( doc ) {
              $(doc.document.body).find('h1').css('font-size', '15pt');
              $(doc.document.body).find('h1').css('text-align', 'center'); 
              $(doc.document.body).find('table').addClass("table-bordered")
              $(doc.document.body).find('table').css('font-size', '15pt');
              $(doc.document.body).find('table').css('width', '100%');
              $(doc.document.body).css('text-align', 'center')
            }
        },
        {
          text: 'Date Filter',
          className: 'btn rounded-3',
          action: function (e, dt, button, config) {
            $("#filter_table").modal("toggle");
          }
        }
      ],
      "lengthMenu": [[10, 50, 100, 500, 1000], [10, 50, 100, 500, 1000]],
  
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
  
  if(document.getElementById('dashboardBookingDetailsTable')){
    dashboardBookingDetailsTable()
  }
