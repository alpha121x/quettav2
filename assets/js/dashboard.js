// Store references to the chart instances
let reportsChart, lineChart, columnChart;

$(document).ready(() => {
  // Perform AJAX request to fetch data for charts
  $.ajax({
    url: "DAL/onload_script.php?type=chart_data",
    type: "GET",
    dataType: "json",
    success: (data) => {
      // Ensure data is not empty or error
      if (data.error) {
        console.error("Error fetching chart data:", data.error);
        return;
      }

      // Initialize charts with the fetched data
      initReportsChart(data.modificationTypes);
      initPieChart(data.parcelPercentages, data.zoneLabels);
      initLineChart(data.landCounts, data.landTypes);
      initColumnChart(
        data.mergeCounts,
        data.sameCounts,
        data.splitCounts,
        data.zones
      );
      initDonutChart(data.blockPercentages, data.blockLabels);
    },
    error: (xhr, status, error) => {
      console.error("Error fetching chart data:", error);
    },
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

// Function to initialize the donut chart
function initDonutChart(blockPercentages, blockLabels) {
  let donutChart = new ApexCharts(document.querySelector("#donutChart"), {
    series: blockPercentages, // Data for the donut chart
    chart: {
      height: 350,
      type: "donut",
      toolbar: {
        show: true,
      },
    },
    labels: blockLabels, // Labels for the donut chart
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

  // Render the donut chart
  donutChart.render();
}

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

  // Create the URL with query parameters
  let url = `DAL/fetch_chart_data.php?zone_code=${zoneCode}&block=${block}&category=${category}&land_type=${landType}&land_sub_type=${landSubType}`;

  // Perform the AJAX request to fetch data with parameters
  fetch(url)
    .then((response) => response.json())
    .then((data) => {
      // console.log(data);
      // Check if there's any error in the response
      if (data.error) {
        console.error("Error fetching data:", data.error);
        return;
      }

      // Clear existing charts
      if (reportsChart) reportsChart.destroy();
      if (lineChart) lineChart.destroy();
      if (columnChart) columnChart.destroy();

      // Initialize charts with the fetched data
      reportsChart = initReportsChart(data.modificationTypes);
      lineChart = initLineChart(data.landCounts, data.landTypes);
      columnChart = initColumnChart(
        data.mergeCounts,
        data.sameCounts,
        data.splitCounts,
        data.zones
      );
    })
    .catch((error) => {
      console.error("Error fetching chart data:", error);
    });

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
      // console.error(data);
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

        $(".counter").counterUp();
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
    url: "DAL/fetch_cards_data.php",
    method: "POST",
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

        $(".counter").counterUp();
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error("AJAX Error:", textStatus, errorThrown);
    },
  });
});

// Function to initialize the reports chart
function initReportsChart(modificationTypes) {
  return new ApexCharts(document.querySelector("#reportsChart"), {
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
  }).render();
}

// Function to initialize the pie chart
function initPieChart(parcelPercentages, zoneLabels) {
  return new ApexCharts(document.querySelector("#pieChart"), {
    series: parcelPercentages,
    chart: {
      height: 350,
      type: "pie",
      toolbar: {
        show: true,
      },
    },
    labels: zoneLabels,
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
}

// Function to initialize the line chart
function initLineChart(landCounts, landTypes) {
  return new ApexCharts(document.querySelector("#lineChart"), {
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
      curve: "smooth",
    },
    grid: {
      row: {
        colors: ["#f3f3f3", "transparent"],
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
      tickAmount: 5,
      labels: {
        formatter: function (value) {
          return value.toFixed(0);
        },
      },
    },
  }).render();
}

// Function to initialize the column chart
function initColumnChart(mergeCounts, sameCounts, splitCounts, zones) {
  return new ApexCharts(document.querySelector("#columnChart"), {
    series: [
      {
        name: "Merge",
        data: mergeCounts,
      },
      {
        name: "Same",
        data: sameCounts,
      },
      {
        name: "Split",
        data: splitCounts,
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
      categories: zones,
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
}
