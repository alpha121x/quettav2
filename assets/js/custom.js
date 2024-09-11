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
      url: "DAL/fetch_dropdowns_data.php",  // Use a specific file to fetch blocks
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

// Fetch land sub types when the selected land type changes
$("#landTypeSelect").on("change", function() {
  var landType = $(this).val();

  // Clear land sub type selection and reset to default "Select Land Sub Type"
  $("#landSubTypeSelect").html(
    "<option selected>Select Land Sub Type</option>"
  );

  if (landType) {
    // Fetch land sub types based on selected land type
    $.ajax({
      type: "POST",
      url: "DAL/fetch_dropdowns_data.php", // Updated file path
      data: {
        land_type: landType,
      },
      success: function(response) {
        // Append the fetched land sub types while keeping "Select Land Sub Type" as the default
        $("#landSubTypeSelect").append(response);
      },
      error: function() {
        console.error("Error fetching land sub types");
      },
    });
  }
});

// Apply filters and reload DataTable with filtered data
$("#search-btn").on("click", function () {
  table.ajax.reload(); // Reload table data based on current filters
});


// Event listener for the "View" button
$("#table tbody").on("click", ".view-btn", function () {
  var parcelId = $(this).data("id");

  // console.log(parcelId);

  // Fetch details for the selected parcel
  $.ajax({
    url: "DAL/onload_script.php?type=parcel", // Endpoint to fetch parcel details
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

// fuctions to populate dropdowns on dom load
$(document).ready(function () {
  // Function to populate Zones
  function populateZones() {
    $.ajax({
      url: "DAL/onload_script.php?type=zones",
      type: "GET",
      data: { type: "zones" },
      success: function (response) {
        // Check if response is already an object or a string (JSON string)
        let zones =
          typeof response === "string" ? JSON.parse(response) : response;

        let zoneSelect = $("#zone-select");
        zoneSelect.empty(); // Clear any existing options
        zoneSelect.append("<option selected>Select Zone</option>"); // Default option

        // Check if zones were retrieved successfully
        if (Array.isArray(zones) && zones.length > 0) {
          zones.forEach(function (zone) {
            // Since only 'zone_code' is being fetched, we will use it both for value and display.
            zoneSelect.append(
              `<option value="${zone.zone_code}">${zone.zone_code}</option>`
            );
          });
        } else {
          console.error("No zones found or response is invalid.");
        }
      },
      error: function (xhr, status, error) {
        console.error("Error fetching zones: ", error);
      },
    });
  }

  function populateCategories() {
    $.ajax({
      url: "DAL/onload_script.php?type=categories", // Make sure the path is correct
      type: "GET",
      data: { type: "categories" },
      success: function (response) {
        // Check if response is already an object or a string (JSON string)
        let categories =
          typeof response === "string" ? JSON.parse(response) : response;

        let categorySelect = $("#category-select");
        categorySelect.empty(); // Clear any existing options
        categorySelect.append("<option selected>Select Category</option>"); // Default option

        // Check if categories were retrieved successfully
        if (Array.isArray(categories) && categories.length > 0) {
          categories.forEach(function (category) {
            // Since only 'modification_type' is being fetched, we will use it both for value and display.
            categorySelect.append(
              `<option value="${category.modification_type}">${category.modification_type}</option>`
            );
          });
        } else {
          console.error("No categories found or response is invalid.");
        }
      },
      error: function (xhr, status, error) {
        console.error("Error fetching categories: ", error);
      },
    });
  }

  function populateLandTypes() {
    $.ajax({
      url: "DAL/onload_script.php?type=land_types", // Make sure the path is correct
      type: "GET",
      data: { type: "land_types" },
      success: function (response) {
        // Check if response is already an object or a string (JSON string)
        let landTypes =
          typeof response === "string" ? JSON.parse(response) : response;

        let landTypeSelect = $("#landTypeSelect");
        landTypeSelect.empty(); // Clear any existing options
        landTypeSelect.append("<option selected>Select Land Type</option>"); // Default option

        // Check if land types were retrieved successfully
        if (Array.isArray(landTypes) && landTypes.length > 0) {
          landTypes.forEach(function (landType) {
            // Since only 'land_type' is being fetched, we will use it both for value and display.
            landTypeSelect.append(
              `<option value="${landType.land_type}">${landType.land_type}</option>`
            );
          });
        } else {
          console.error("No land types found or response is invalid.");
        }
      },
      error: function (xhr, status, error) {
        console.error("Error fetching land types: ", error);
      },
    });
  }

  // Populate both dropdowns on page load
  populateZones();
  populateCategories();
  populateLandTypes();
});
// end... //
