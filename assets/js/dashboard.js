// Apply filters
applyFiltersBtn.addEventListener("click", () => {

  // Get selected values
  const zoneCode = document.getElementById("zone-select").value;
  const block = document.getElementById("block-select").value;
  const category = document.querySelector('[aria-label="Select Category"]').value;
  const landType = document.querySelector('[aria-label="Select Land Type"]').value;
  const landSubType = document.querySelector('[aria-label="Select Land Sub Type"]').value;

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


      var  params = `?zone_code=${zoneCode}&sheet_no=${block}&modification_type=${category}&land_type=${landType}&land_sub_type=${landSubType}`;
      make_chart(params);

    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error("AJAX Error: " + textStatus);
    },
  });
});


function make_chart(params){
  // Now make another request to update the chart with filtered data
  $.ajax({
    url: "DAL/fetch_chart_data.php"+params, // Make sure you have a file to handle chart data
    method: "GET",
    dataType: "json",
    // data: {
    //   zone_code: zoneCode,
    //   sheet_no: block,
    //   modification_type: category,
    //   land_type: landType,
    //   land_sub_type: landSubType,
    // },
    success: function (chartData) {
      console.log("ASIM");
      console.log(chartData);
      if (chartData.error) {
        console.error(chartData.error);
      } else {
        // Destroy the previous chart
        if (window.reportsChart) {
          window.reportsChart.destroy();
        }

        // Create a new chart with the filtered data
        window.reportsChart = new ApexCharts(
          document.querySelector("#reportsChart"),
          {
            series: [
              {
                name: "Modification Types",
                data: chartData.map((item) => parseInt(item.count)),
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
              categories: chartData.map((item) => item.modification_type),
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

        // Render the new chart
        window.reportsChart.render();
      }
    },
    error: function (jqXHR, textStatus, errorThrown) {
      console.error("AJAX Error: " + textStatus);
    },
  });
}

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
