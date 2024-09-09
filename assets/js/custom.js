// Initialize DataTable with server-side processing
var table = $("#table").DataTable({
  processing: true,
  serverSide: true,
  responsive: true,
  ajax: {
    url: "DAL/fetch_data.php", // Server-side data source
    type: "POST",
    data: function (d) {
      // Pass additional parameters (filters) to the server
      d.zone_code = $("#zone-select").val();
      d.block = $("#block-select").val();
      d.category = $('[aria-label="Select Category"]').val();
    },
  },
  language: {
    search: "",
    searchPlaceholder: "Search by parcel_id",
    emptyTable: "Data Not Available",
  },
  columns: [
    {
      data: null, // No data for row number, handled in createdRow
      render: function (data, type, row, meta) {
        return meta.row + 1; // Return row number (1-based index)
      },
    },
    { data: "parcel_id" },
    { data: "zone_code" },
    { data: "land_type" },
    { data: "land_sub_type" },
    { data: "modification_type" },
    { data: "building_height" },
    { data: "building_condition" },
    {
      data: null, // Action column for "View" button
      orderable: false, // Disable sorting for this column
      render: function (data, type, row) {
        return (
          '<button type="button" class="btn btn-sm btn-success view-btn" data-id="' +
          row.parcel_id +
          '">View</button>'
        );
      },
    },
  ],
  // Highlight row if the server returns a highlight flag
  rowCallback: function (row, data, index) {
    if (data.highlight) {
      $(row).addClass("highlighted-row"); // Apply the highlight class
    } else {
      $(row).removeClass("highlighted-row"); // Remove any previous highlights
    }
  },
});

// Event listener for the search input to reset pagination on new search
$("#table_filter input")
  .unbind()
  .bind("keyup", function (e) {
    if (e.keyCode == 13 || $(this).val().length > 0) {
      // Search on Enter key or non-empty input
      table.search(this.value).draw();
    }
  });

// Optional: Clear search input after search
$("#table_filter input").on("input", function () {
  if ($(this).val() === "") {
    table.search("").draw(); // Reset table state
  }
});

// Fetch blocks when the selected zone changes
$("#zone-select").on("change", function () {
  var zoneCode = $(this).val();

  // Clear block selection and reset to default "Select Block"
  $("#block-select").html("<option selected>Select Block</option>");

  if (zoneCode) {
    // Fetch blocks based on selected zone
    $.ajax({
      type: "POST",
      url: "DAL/fetch_blocks.php", // Use a specific file to fetch blocks
      data: { zone_code: zoneCode },
      success: function (response) {
        // Append the fetched blocks while keeping "Select Block" as the default
        $("#block-select").append(response);
      },
      error: function () {
        console.error("Error fetching blocks");
      },
    });
  }
});

// Apply filters and reload DataTable with filtered data
$("#search-btn").on("click", function () {
  table.ajax.reload(); // Reload table data based on current filters
});

// Event listener for the "View" button
// Event listener for the "View" button
$("#table tbody").on("click", ".view-btn", function () {
  var parcelId = $(this).data("id");

  // console.log(parcelId);

  // Fetch details for the selected parcel
  $.ajax({
    url: "DAL/fetch_parcel_details.php", // Endpoint to fetch parcel details
    type: "POST",
    data: { parcel_id: parcelId },
    success: function (response) {
      var data = response;
      // console.log(response);

      // Set other fields as needed
      $("#modalContent").html(`
        <table class="table table-bordered">
          <tbody>
            <tr>
              <td class="text-success fw-bold">Zone Code</td>
              <td>${data.zone_code}</td>
              <td class="text-success fw-bold">Block Code</td>
              <td>${data.sheet_no}</td>
            </tr>
            <tr>
              <td class="text-success fw-bold">Parcel ID</td>
              <td>${data.parcel_id}</td>
              <td class="text-success fw-bold">Modification Type</td>
              <td>${data.modification_type}</td>
            </tr>
            <tr>
              <td class="text-success fw-bold">Land Type</td>
              <td>${data.land_type}</td>
              <td class="text-success fw-bold">Land Sub Type</td>
              <td>${data.land_sub_type}</td>
            </tr>
            <tr>
              <td class="text-success fw-bold">Building Condition</td>
              <td>${data.building_condition}</td>
              <td class="text-success fw-bold">Building Height</td>
              <td>${data.building_height}</td>
            </tr>
            <tr>
              <td class="text-success fw-bold">Building Type</td>
              <td>${data.building_type}</td>
              <td class="text-success fw-bold">Username</td>
              <td>${data.username}</td>
            </tr>
            <tr>
              <td class="text-success fw-bold">Survey Date</td>
              <td>${data.survey_date_time}</td>
              <td class="text-success fw-bold">DB Date</td>
              <td>${data.db_date_time}</td>
            </tr>
            <tr>
              <td class="text-success fw-bold">Merge ID</td>
              <td>${data.merge_id}</td>
              <td class="text-success fw-bold">Land ID</td>
              <td>${data.land_id}</td>
            </tr>
            <tr>
              <td class="text-success fw-bold">Symbol</td>
              <td>${data.symbol}</td>
              <td class="text-success fw-bold">Remarks</td>
              <td>${data.remarks}</td>
            </tr>
            <tr>
              <td class="text-success fw-bold">QA By</td>
              <td>${data.qa_by_uu}</td>
              <td class="text-success fw-bold">QA Remarks</td>
              <td>${data.qa_remarks}</td>
            </tr>
            <tr>
              <td class="text-success fw-bold">App Version</td>
              <td>${data.app_version}</td>
              <td class="text-success fw-bold">Survey Date QA</td>
              <td>${data.survey_date_time_qa}</td>
            </tr>
            <tr>
              <td class="text-success fw-bold">QA Latitude</td>
              <td>${data.qa_lat}</td>
              <td class="text-success fw-bold">QA Longitude</td>
              <td>${data.qa_lng}</td>
            </tr>
            <tr>
            <td class="text-success fw-bold">Picture 1</td>
            <td>
              <img src="${data.picture1}" class="building_img" id="picture1" alt="Picture 1">
            </td>
            <td class="text-success fw-bold">Picture 2</td>
            <td>
              <img src="${data.picture2}" class="building_img" id="picture2" alt="Picture 2">
            </td>
          </tr>
          </tbody>
        </table>
      `);

      // Open the modal and load content based on the parcelId
      $("#viewModal").modal("show");
    },
    error: function () {
      // Handle errors
      $("#modalContent").html("<p>Error fetching details.</p>");
    },
  });
});


$(document).ready(function() {
  // Fetch data using AJAX
  $.ajax({
      url: './DAL/fetch_cards_data.php',
      method: 'GET',
      dataType: 'json',
      success: function(data) {
          // Check for errors
          if (data.error) {
              console.error('Server Error:', data.error);
          } else {
              // Update card values
              $('#total-zones').text(data.totalZones || 'N/A');
              $('#total-blocks').text(data.totalBlocks || 'N/A');
              $('#total-parcels').text(data.totalParcels || 'N/A');
              $('#merge-parcels').text(data.mergeParcels || 'N/A');
              $('#same-parcels').text(data.sameParcels || 'N/A');
              $('#split-parcels').text(data.splitParcels || 'N/A');
          }
      },
      error: function(jqXHR, textStatus, errorThrown) {
          console.error('AJAX Error:', textStatus, errorThrown);
      }
  });
});