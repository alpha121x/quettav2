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

// Functions to populate dropdowns on DOM load
$(document).ready(function () {
  function populateZones() {
    $.ajax({
      url: "DAL/onload_script.php?type=zones",
      type: "GET",
      data: { type: "zones" },
      success: function (response) {
        let zones =
          typeof response === "string" ? JSON.parse(response) : response;

        let zoneSelect = $("#zone-select");
        zoneSelect.empty(); // Clear any existing options
        zoneSelect.append("<option selected>Select Zone</option>"); // Default option

        if (Array.isArray(zones) && zones.length > 0) {
          zones.forEach(function (zone) {
            zoneSelect.append(
              `<option value="${zone.zone_code}">${zone.zone_code}</option>`
            );
          });
        } else {
          console.error("No zones found or response is invalid.");
        }
      },
      error: function (xhr, status, error) {
        console.error("Error fetching zones: ", xhr.responseText || error);
      },
    });
  }

  function populateCategories() {
    $.ajax({
      url: "DAL/onload_script.php?type=categories",
      type: "GET",
      data: { type: "categories" },
      success: function (response) {
        let categories =
          typeof response === "string" ? JSON.parse(response) : response;

        let categorySelect = $("#category-select");
        categorySelect.empty();
        categorySelect.append("<option selected>Select Category</option>");

        if (Array.isArray(categories) && categories.length > 0) {
          categories.forEach(function (category) {
            categorySelect.append(
              `<option value="${category.modification_type}">${category.modification_type}</option>`
            );
          });
        } else {
          console.error("No categories found or response is invalid.");
        }
      },
      error: function (xhr, status, error) {
        console.error("Error fetching categories: ", xhr.responseText || error);
      },
    });
  }

  function populateLandTypes() {
    $.ajax({
      url: "DAL/onload_script.php?type=land_types",
      type: "GET",
      data: { type: "land_types" },
      success: function (response) {
        let landTypes =
          typeof response === "string" ? JSON.parse(response) : response;

        let landTypeSelect = $("#land-type-select");
        landTypeSelect.empty();
        landTypeSelect.append("<option selected>Select Land Type</option>");

        if (Array.isArray(landTypes) && landTypes.length > 0) {
          landTypes.forEach(function (landType) {
            landTypeSelect.append(
              `<option value="${landType.land_type}">${landType.land_type}</option>`
            );
          });
        } else {
          console.error("No land types found or response is invalid.");
        }
      },
      error: function (xhr, status, error) {
        console.error("Error fetching land types: ", xhr.responseText || error);
      },
    });
  }

  populateZones();
  populateCategories();
  populateLandTypes();
});

// DOM ready for chart initialization
$(document).ready(() => {
  fetch("DAL/onload_script.php?type=chart_data")
    .then((response) => response.json())
    .then((data) => {
      if (data.error) {
        console.error("Error fetching chart data:", data.error);
        return;
      }

      initReportsChart(data.modificationTypes);
      initPieChart(data.parcelPercentages, data.zoneLabels);
      initLineChart(data.landCounts, data.landTypes);
      initColumnChart(
        data.mergeCounts,
        data.sameCounts,
        data.splitCounts,
        data.zones
      );
    })
    .catch((error) => {
      console.error("Error fetching chart data:", error);
    });
});

// Function to initialize the reports chart
function initReportsChart(modificationTypes) {
  let reportsChart = new ApexCharts(document.querySelector("#reportsChart"), {
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
  });

  // Render the chart
  reportsChart.render();
}

// Function to initialize the pie chart
function initPieChart(parcelPercentages, zoneLabels) {
  let pieChart = new ApexCharts(document.querySelector("#pieChart"), {
    series: parcelPercentages, // Data for pie chart
    chart: {
      height: 350,
      type: "pie",
      toolbar: {
        show: true,
      },
    },
    labels: zoneLabels, // Labels for pie chart
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
  });

  // Render the pie chart
  pieChart.render();
}

// Function to initialize the line chart
function initLineChart(landCounts, landTypes) {
  let lineChart = new ApexCharts(document.querySelector("#lineChart"), {
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
  });

  // Render the line chart
  lineChart.render();
}

// Function to initialize the column chart
function initColumnChart(mergeCounts, sameCounts, splitCounts, zones) {
  let columnChart = new ApexCharts(document.querySelector("#columnChart"), {
    series: [
      {
        name: "Merge",
        data: mergeCounts, // Merge data
      },
      {
        name: "Same",
        data: sameCounts, // Same data
      },
      {
        name: "Split",
        data: splitCounts, // Split data
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
      categories: zones, // Zones for x-axis
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
  });

  // Render the column chart
  columnChart.render();
}

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

// fetch cards update data //
$(document).ready(function () {
  // Fetch data using AJAX
  $.ajax({
    url: "./DAL/fetch_cards_data.php",
    method: "GET",
    dataType: "json",
    success: function (data) {
      // Check for errors
      if (data.error) {
        console.error("Server Error:", data.error);
      } else {
        // Update card values
        $("#total-zones").text(data.totalZones || "N/A");
        $("#total-blocks").text(data.totalBlocks || "N/A");
        $("#total-parcels").text(data.totalParcels || "N/A");
        $("#merge-parcels").text(data.mergeParcels || "N/A");
        $("#same-parcels").text(data.sameParcels || "N/A");
        $("#split-parcels").text(data.splitParcels || "N/A");
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error("AJAX Error:", textStatus, errorThrown);
    },
  });
});
