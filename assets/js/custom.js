  // Initialize DataTable with server-side processing
  var table = $('#table').DataTable({
    "processing": true,
    "serverSide": true,
    "ajax": {
      "url": "DAL/fetch_data.php", // Updated data source
      "type": "POST",
      "data": function(d) {
        // Pass additional parameters to the server
        d.zone_code = $('#zone-select').val();
        d.block = $('#block-select').val();
        d.category = $('[aria-label="Select Category"]').val();
      }
    },
    "columns": [
      {
        "data": null, // No data for row number, handled in createdRow
        "render": function(data, type, row, meta) {
          return meta.row + 1; // Return row number (1-based index)
        }
      },
      { "data": "parcel_id" },
      { "data": "zone_code" },
      { "data": "land_type" },
      { "data": "land_sub_type" },
      { "data": "modification_type" },
      { "data": "building_height" },
      { "data": "building_condition" }
    ]
  });
  
/** */
  $('#zone-select').on('change', function() {
    var zoneCode = $(this).val();

    // Clear block selection and reset to default "Select Block"
    $('#block-select').html('<option selected>Select Block</option>');

    if (zoneCode) {
      // Fetch blocks based on selected zone
      $.ajax({
        type: 'POST',
        url: 'DAL/fetch_data.php',
        data: {
          zone_code: zoneCode
        },
        success: function(response) {
          // Append the fetched blocks while keeping "Select Block" as the default
          $('#block-select').append(response);
        },
        error: function() {
          console.error('Error fetching blocks');
        }
      });
    }
  });

  // Apply filters and reload DataTable with filtered data
  $('#search-btn').on('click', function() {
    table.ajax.reload(); // Reload table data based on current filters
  });

