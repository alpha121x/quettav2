// Apply filters
applyFiltersBtn.addEventListener("click", () => {
  // Get selected values
  const zoneCode = document.getElementById("zone-select").value;
  const block = document.getElementById("block-select").value;
  const category = document.querySelector(
    '[aria-label="Select Category"]'
  ).value;
  const landType = document.querySelector(
    '[aria-label="Select Land Type"]'
  ).value;
  const landSubType = document.querySelector(
    '[aria-label="Select Land Sub Type"]'
  ).value;

  // Send AJAX request with the selected filter values
  $.ajax({
    url: "DAL/fetch_cards_data.php",
    method: "POST",
    dataType: "json",
    data: {
      zone_code: zoneCode,
      sheet_no: block,
      modification_type: category,
      land_type: landType,
      land_sub_type: landSubType,
    },
    success: function (data) {
      // Check for errors
      if (data.error) {
        console.error(data.error);
      } else {
        // Update card values
        $("#total-zones").text(data.totalZones);
        $("#total-blocks").text(data.totalBlocks);
        $("#total-parcels").text(data.totalParcels);
        $("#merge-parcels").text(data.mergeParcels);
        $("#same-parcels").text(data.sameParcels);
        $("#split-parcels").text(data.splitParcels);
      }

      // Close the drawer after applying filters
      filterDrawer.classList.remove("drawer-active");
      setTimeout(() => {
        openDrawerBtn.style.display = "block"; // Show the open button after drawer closes
      }, 300);
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error("AJAX Error: " + textStatus);
    },
  });
});

$(document).ready(function () {
  // Function to populate Zones
  function populateZones() {
    $.ajax({
      url: "DAL/get_zones.php",
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
      url: "DAL/get_categories.php", // Make sure the path is correct
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
      url: "DAL/get_land_types.php", // Make sure the path is correct
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

// Initialize chart on page load
document.addEventListener("DOMContentLoaded", () => {
  window.reportsChart = new ApexCharts(
    document.querySelector("#reportsChart"),
    {
      series: [
        {
          name: "Modification Types",
          data: modificationTypes.map((item) => parseInt(item.count)),
        },
      ],
      chart: {
        height: 350,
        type: "bar",
        toolbar: {
          show: false,
        },
      },
      colors: ["#28A745"],
      fill: {
        type: "solid",
      },
      dataLabels: {
        enabled: false,
      },
      stroke: {
        curve: "smooth",
        width: 2,
      },
      xaxis: {
        categories: modificationTypes.map((item) => item.modification_type),
        title: {
          text: "Modification Types",
        },
      },
      yaxis: {
        title: {
          text: "Counts",
        },
      },
    }
  );

  // Render the chart initially
  window.reportsChart.render();
});

document.addEventListener("DOMContentLoaded", () => {
  // Initialize the chart with the PHP-generated data
  new ApexCharts(document.querySelector("#pieChart"), {
    series: parcelPercentages, // PHP data
    chart: {
      height: 350,
      type: "pie",
      toolbar: {
        show: true,
      },
    },
    labels: zoneLabels, // PHP data
    responsive: [
      {
        breakpoint: 480,
        options: {
          chart: {
            width: 200,
          },
          legend: {
            position: "bottom",
          },
        },
      },
    ],
  }).render();
});

document.addEventListener("DOMContentLoaded", () => {
  // Initialize chart with fetched data
  new ApexCharts(document.querySelector("#lineChart"), {
    series: [
      {
        name: "Land Count",
        data: landCounts,
      },
    ],
    chart: {
      height: 350,
      type: "line",
      zoom: {
        enabled: false,
      },
    },
    dataLabels: {
      enabled: false,
    },
    stroke: {
      curve: "smooth", // Smooth curve for the line
    },
    grid: {
      row: {
        colors: ["#f3f3f3", "transparent"], // Alternating row colors
        opacity: 0.5,
      },
    },
    xaxis: {
      categories: landTypes,
      title: {
        text: "Land Types",
      },
    },
    yaxis: {
      title: {
        text: "Land Count",
      },
      tickAmount: 5, // Number of ticks to show
      labels: {
        formatter: function (value) {
          return value.toFixed(0); // Display integer values
        },
      },
    },
  }).render();
});

document.addEventListener("DOMContentLoaded", () => {
  new ApexCharts(document.querySelector("#columnChart"), {
    series: [
      {
        name: "Merge",
        data: mergeCounts, // PHP data
      },
      {
        name: "Same",
        data: sameCounts, // PHP data
      },
      {
        name: "Split",
        data: splitCounts, // PHP data
      },
    ],
    chart: {
      type: "bar",
      height: 350,
    },
    plotOptions: {
      bar: {
        horizontal: false,
        columnWidth: "55%",
        endingShape: "rounded",
      },
    },
    dataLabels: {
      enabled: false,
    },
    stroke: {
      show: true,
      width: 2,
      colors: ["transparent"],
    },
    xaxis: {
      categories: zones, // PHP data
    },
    yaxis: {
      title: {
        text: "Count of Modified Parcels",
      },
    },
    fill: {
      opacity: 1,
    },
    tooltip: {
      y: {
        formatter: function (val) {
          return val + " parcels";
        },
      },
    },
  }).render();
});

// Open drawer
openDrawerBtn.addEventListener("click", () => {
  filterDrawer.classList.add("drawer-active");
  openDrawerBtn.style.display = "none"; // Hide the open button
});

// Close drawer
closeDrawerBtn.addEventListener("click", () => {
  filterDrawer.classList.remove("drawer-active");
  setTimeout(() => {
    openDrawerBtn.style.display = "block"; // Show the open button after drawer closes
  }, 300); // Adjust this timeout to match the CSS transition duration
});
