<?php include "./DAL/fetch_chart_data.php" ?>
<main id="main" class="main">

  <div class="pagetitle">
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
        <li class="breadcrumb-item active">Dashboard</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

  <style>
    /* Filter button styling */
    .filter-button {
      position: fixed;
      top: 50%;
      right: 25px;
      transform: translateY(-50%) rotate(90deg);
      transform-origin: right center;
      padding: 10px 20px;
      background-color: #28A745;
      color: white;
      border: none;
      border-radius: 12px;
      cursor: pointer;
      z-index: 1000;
    }


    /* Drawer styling */
    .filter-drawer {
      position: fixed;
      top: 0;
      right: -100%;
      width: 300px;
      height: 100%;
      background-color: white;
      box-shadow: -2px 0 5px rgba(0, 0, 0, 0.5);
      transition: right 0.3s ease;
      z-index: 999;
    }

    /* Content inside drawer */
    .drawer-content {
      padding: 20px;
    }

    /* Close button styling */
    .close-button {
      background: none;
      border: none;
      color: #333;
      font-size: 23px;
      cursor: pointer;
      position: absolute;
      top: 10px;
      right: 10px;
    }

    /* Apply button styling */
    /* Center the button */
    .apply-button {
      display: block;
      margin: 20px auto;
      /* Centers the button horizontally */
      background-color: #28a745;
      color: white;
      padding: 10px 20px;
      border: none;
      cursor: pointer;
      border-radius: 5px;
    }


    /* Show the drawer when active */
    .drawer-active {
      right: 0;
    }
  </style>

  <section class="section dashboard">
    <div class="row">

      <div class="col-lg-12">
        <div class="row">

          <?php include "top_cards.php" ?>

        </div>
      </div><!-- End Cards -->

      <!-- Filter Button -->
      <div class="col-lg-3">
        <button id="openDrawerBtn" class="filter-button">
          <i class="bi bi-arrow-down-square"></i> Filter
        </button>

        <!-- Filter Drawer -->
        <div id="filterDrawer" class="filter-drawer">
          <div class="drawer-content">
            <button id="closeDrawerBtn" class="close-button">&times;</button>
            <h3>Filter Options</h3>

            <!-- Zone Selection -->
            <?php
            try {
              $stmt = $pdo->query("SELECT DISTINCT zone_code FROM public.tbl_landuse_f ORDER BY zone_code ASC");
              $zones = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
              echo "Error: " . $e->getMessage();
            }
            ?>
            <div class="mb-3">
              <div class="form-group">
                <select class="form-select" id="zone-select" aria-label="Select Zone">
                  <option selected>Select Zone</option>
                  <?php if (!empty($zones)): ?>
                    <?php foreach ($zones as $zone): ?>
                      <option value="<?= htmlspecialchars($zone['zone_code']); ?>">
                        <?= htmlspecialchars($zone['zone_code']); ?>
                      </option>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <option disabled>No Zones Available</option>
                  <?php endif; ?>
                </select>
              </div>
            </div>

            <!-- Block Selection -->
            <div class="mb-3">
              <div class="form-group">
                <select class="form-select" id="block-select" aria-label="Select Block">
                  <option selected>Select Block</option>
                  <!-- Blocks will be populated based on the selected zone -->
                </select>
              </div>
            </div>

            <!-- Category Selection -->
            <?php
            try {
              $stmt = $pdo->query("SELECT DISTINCT modification_type FROM public.tbl_landuse_f ORDER BY modification_type ASC");
              $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
              echo "Error: " . $e->getMessage();
            }
            ?>
            <div class="mb-3">
              <div class="form-group">
                <select class="form-select" id="category-select" aria-label="Select Category">
                  <option selected>Select Category</option>
                  <?php if (!empty($categories)): ?>
                    <?php foreach ($categories as $category): ?>
                      <option value="<?= htmlspecialchars($category['modification_type']); ?>">
                        <?= htmlspecialchars($category['modification_type']); ?>
                      </option>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <option disabled>No Categories Available</option>
                  <?php endif; ?>
                </select>
              </div>
            </div>

            <!-- Land Type Selection -->
            <?php
            try {
              $stmt = $pdo->query("SELECT DISTINCT land_type FROM public.tbl_landuse_f ORDER BY land_type ASC");
              $landTypes = $stmt->fetchAll(PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
              echo "Error: " . $e->getMessage();
            }
            ?>
            <div class="mb-3">
              <div class="form-group">
                <select id="landTypeSelect" class="form-select" aria-label="Select Land Type">
                  <option selected>Select Land Type</option>
                  <?php if (!empty($landTypes)): ?>
                    <?php foreach ($landTypes as $landType): ?>
                      <option value="<?= htmlspecialchars($landType['land_type']); ?>">
                        <?= htmlspecialchars($landType['land_type']); ?>
                      </option>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <option disabled>No Land Types Available</option>
                  <?php endif; ?>
                </select>
              </div>
            </div>

            <!-- Land Sub Type Selection -->
            <div class="mb-3">
              <div class="form-group">
                <select id="landSubTypeSelect" class="form-select" aria-label="Select Land Sub Type">
                  <option selected>Select Land Sub Type</option>
                  <!-- Land sub types will be populated here based on land type selection -->
                </select>
              </div>
            </div>

            <script>
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
                    url: "DAL/fetch_land_sub_types.php", // Updated file path
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
            </script>

            <!-- Apply Filters Button -->
            <button id="applyFiltersBtn" class="apply-button">Apply Filters</button>
          </div>
        </div>
      </div>

    </div>


    <div class="col-lg-12">
      <div class="row">

        <div class="col-lg-4">
          <div class="card">

            <div class="card-body">
              <h5 class="card-title">Land Modification Types Chart</span></h5>

              <!-- Line Chart -->
              <div id="reportsChart"></div>
              <!-- End Line Chart -->

            </div>

          </div>
        </div>

        <div class="col-lg-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Map</h5>

              <div id="map" class="contact-map">
                <iframe
                  src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d52599.105009580575!2d66.9551262983524!3d30.17984019470825!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ed2e4a21261a2ef%3A0x6f7d937d15cb23a7!2sQuetta%2C%20Balochistan!5e0!3m2!1sen!2s!4v1693245110924"
                  width="100%"
                  height="360"
                  frameborder="0"
                  style="border:0"
                  allowfullscreen>
                </iframe>

              </div>


            </div>
          </div>
        </div>


        <div class="col-lg-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Zone Parcels Chart</h5>

              <!-- Pie Chart -->
              <div id="pieChart"></div>
              <!-- End Pie Chart -->

            </div>
          </div>
        </div>


        <div class="col-lg-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Land Types Chart</h5>


              <!-- Line Chart -->
              <div id="lineChart"></div>
              <!-- End Line Chart -->

            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Zone Modification Types Chart</h5>

              <!-- Column Chart -->
              <div id="columnChart"></div>
              <!-- End Column Chart -->

            </div>
          </div>
        </div>


        <div class="col-lg-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Donut Chart</h5>

              <!-- Donut Chart -->
              <div id="donutChart"></div>

              <script>
                document.addEventListener("DOMContentLoaded", () => {
                  new ApexCharts(document.querySelector("#donutChart"), {
                    series: [44, 55, 13, 43, 22],
                    chart: {
                      height: 350,
                      type: 'donut',
                      toolbar: {
                        show: true
                      }
                    },
                    labels: ['Team A', 'Team B', 'Team C', 'Team D', 'Team E'],
                  }).render();
                });
              </script>
              <!-- End Donut Chart -->

            </div>
          </div>
        </div>
      </div>

    </div>



    </div>
  </section>
</main><!-- End #main -->

<script>
  // Get elements
  const openDrawerBtn = document.getElementById('openDrawerBtn');
  const filterDrawer = document.getElementById('filterDrawer');
  const closeDrawerBtn = document.getElementById('closeDrawerBtn');
  const applyFiltersBtn = document.getElementById('applyFiltersBtn');

  // Open drawer
  openDrawerBtn.addEventListener('click', () => {
    filterDrawer.classList.add('drawer-active');
    openDrawerBtn.style.display = 'none'; // Hide the open button
  });

  // Close drawer
  closeDrawerBtn.addEventListener('click', () => {
    filterDrawer.classList.remove('drawer-active');
    setTimeout(() => {
      openDrawerBtn.style.display = 'block'; // Show the open button after drawer closes
    }, 300); // Adjust this timeout to match the CSS transition duration
  });

  // Apply filters
  applyFiltersBtn.addEventListener('click', () => {
    // Get selected values
    const zoneCode = document.getElementById('zone-select').value;
    const block = document.getElementById('block-select').value;
    const category = document.querySelector('[aria-label="Select Category"]').value;
    const landType = document.querySelector('[aria-label="Select Land Type"]').value;
    const landSubType = document.querySelector('[aria-label="Select Land Sub Type"]').value;

    // Send AJAX request with the selected filter values
    $.ajax({
      url: './DAL/fetch_cards_data.php',
      method: 'POST',
      dataType: 'json',
      data: {
        zone_code: zoneCode,
        sheet_no: block,
        modification_type: category,
        land_type: landType,
        land_sub_type: landSubType
      },
      success: function(data) {
        // Check for errors
        if (data.error) {
          console.error(data.error);
        } else {
          // Update card values
          $('#total-zones').text(data.totalZones);
          $('#total-blocks').text(data.totalBlocks);
          $('#total-parcels').text(data.totalParcels);
          $('#merge-parcels').text(data.mergeParcels);
          $('#same-parcels').text(data.sameParcels);
          $('#split-parcels').text(data.splitParcels);
        }

        // console.log(zoneCode,block,category,landType,landSubType);

        // Close the drawer after applying filters
        filterDrawer.classList.remove('drawer-active');
        setTimeout(() => {
          openDrawerBtn.style.display = 'block'; // Show the open button after drawer closes
        }, 300);
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.error('AJAX Error: ' + textStatus);
      }
    });
  });
</script>



// charts scripts //
<script>
  document.addEventListener("DOMContentLoaded", () => {
    // Chart for Modification Types
    new ApexCharts(document.querySelector("#reportsChart"), {
      series: [{
        name: 'Modification Types',
        data: modificationTypes.map(item => parseInt(item.count))
      }],
      chart: {
        height: 350,
        type: 'bar',
        toolbar: {
          show: false
        }
      },
      colors: ['#28A745'],
      fill: {
        type: "solid"
      },
      dataLabels: {
        enabled: false
      },
      stroke: {
        curve: 'smooth',
        width: 2
      },
      xaxis: {
        categories: modificationTypes.map(item => item.modification_type),
        title: {
          text: 'Modification Types'
        }
      },
      yaxis: {
        title: {
          text: 'Counts'
        }
      },
      tooltip: {
        x: {
          format: 'dd/MM/yy HH:mm'
        }
      }
    }).render();
  });

  document.addEventListener("DOMContentLoaded", () => {
    // Initialize the chart with the PHP-generated data
    new ApexCharts(document.querySelector("#pieChart"), {
      series: parcelPercentages, // PHP data
      chart: {
        height: 350,
        type: 'pie',
        toolbar: {
          show: true
        }
      },
      labels: zoneLabels, // PHP data
      responsive: [{
        breakpoint: 480,
        options: {
          chart: {
            width: 200
          },
          legend: {
            position: 'bottom'
          }
        }
      }]
    }).render();
  });



  document.addEventListener("DOMContentLoaded", () => {
    // Initialize chart with fetched data
    new ApexCharts(document.querySelector("#lineChart"), {
      series: [{
        name: "Land Count",
        data: landCounts
      }],
      chart: {
        height: 350,
        type: 'line',
        zoom: {
          enabled: false
        }
      },
      dataLabels: {
        enabled: false
      },
      stroke: {
        curve: 'smooth' // Smooth curve for the line
      },
      grid: {
        row: {
          colors: ['#f3f3f3', 'transparent'], // Alternating row colors
          opacity: 0.5
        },
      },
      xaxis: {
        categories: landTypes,
        title: {
          text: 'Land Types'
        }
      },
      yaxis: {
        title: {
          text: 'Land Count'
        },
        tickAmount: 5, // Number of ticks to show
        labels: {
          formatter: function(value) {
            return value.toFixed(0); // Display integer values
          }
        }
      }
    }).render();
  });


  document.addEventListener("DOMContentLoaded", () => {
    new ApexCharts(document.querySelector("#columnChart"), {
      series: [{
        name: 'Merge',
        data: mergeCounts // PHP data
      }, {
        name: 'Same',
        data: sameCounts // PHP data
      }, {
        name: 'Split',
        data: splitCounts // PHP data
      }],
      chart: {
        type: 'bar',
        height: 350
      },
      plotOptions: {
        bar: {
          horizontal: false,
          columnWidth: '55%',
          endingShape: 'rounded'
        },
      },
      dataLabels: {
        enabled: false
      },
      stroke: {
        show: true,
        width: 2,
        colors: ['transparent']
      },
      xaxis: {
        categories: zones, // PHP data
      },
      yaxis: {
        title: {
          text: 'Count of Modified Parcels'
        }
      },
      fill: {
        opacity: 1
      },
      tooltip: {
        y: {
          formatter: function(val) {
            return val + " parcels";
          }
        }
      }
    }).render();
  });
</script>