// Initialize DataTable with server-side processing
var table = $("#table").DataTable({
  processing: true,
  serverSide: true,
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
              <div class="row mb-3">
          <div class="col-md-6">
            <label for="zoneCode" class="form-label text-success fw-bold">Zone Code</label>
            <input type="text" class="form-control" id="zoneCode" value="${data.zone_code}" readonly>
          </div>
          <div class="col-md-6">
            <label for="landType" class="form-label text-success fw-bold">Block Code</label>
            <input type="text" class="form-control" id="landType" value="${data.sheet_no}" readonly>
          </div>
        </div>
      <div class="row mb-3">
          <div class="col-md-6">
            <label for="picture1" class="form-label text-success fw-bold">Picture 1</label>
            <img src="${data.picture1}" class="building_img" id="picture1" alt="Picture 1">
          </div>
           <div class="col-md-6">
            <label for="picture1" class="form-label text-success fw-bold">Picture 2</label>
            <img src="${data.picture2}" class="building_img"  id="picture1" alt="Picture 1">
          </div>
        </div>
            <div class="row mb-3">
          <div class="col-md-6">
            <label for="buildingHeight" class="form-label text-success fw-bold">Land Type</label>
            <input type="text" class="form-control" id="buildingHeight" value="${data.land_type}" readonly>
          </div>
          <div class="col-md-6">
            <label for="buildingCondition" class="form-label text-success fw-bold">Land Sub Type</label>
            <input type="text" class="form-control" id="buildingCondition" value="${data.land_sub_type}" readonly>
          </div>
        </div>
        <div class="row mb-3">
          <div class="col-md-6">
            <label for="buildingType" class="form-label text-success fw-bold">Modification Type</label>
            <input type="text" class="form-control" id="buildingType" value="${data.modification_type}" readonly>
          </div>
           <div class="col-md-6">
            <label for="buildingType" class="form-label text-success fw-bold">Building Type</label>
            <input type="text" class="form-control" id="buildingType" value="${data.building_type}" readonly>
          </div>
        </div>
         <div class="row mb-3">
          <div class="col-md-6">
            <label for="buildingType" class="form-label text-success fw-bold">Building Condtition</label>
            <input type="text" class="form-control" id="buildingType" value="${data.building_condition}" readonly>
          </div>
           <div class="col-md-6">
            <label for="buildingType" class="form-label text-success fw-bold">Building Height</label>
            <input type="text" class="form-control" id="buildingType" value="${data.building_height}" readonly>
          </div>
        </div>
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
