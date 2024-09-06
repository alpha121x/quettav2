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
