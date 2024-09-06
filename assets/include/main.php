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

  <?php
  // Include the database configuration file
  require "./DAL/db_config.php";

  try {
    // Prepare the PDO queries
    $totalZonesQuery = "SELECT COUNT(DISTINCT zone_code) FROM tbl_landuse_f";
    $totalBlocksQuery = "SELECT COUNT(DISTINCT sheet_no) FROM tbl_landuse_f";
    $totalParcelsQuery = "SELECT COUNT(DISTINCT parcel_id) FROM tbl_landuse_f";

    // Execute the queries and fetch the counts using PDO
    $stmtZones = $pdo->prepare($totalZonesQuery);
    $stmtZones->execute();
    $totalZones = $stmtZones->fetchColumn();

    $stmtBlocks = $pdo->prepare($totalBlocksQuery);
    $stmtBlocks->execute();
    $totalBlocks = $stmtBlocks->fetchColumn();

    $stmtParcels = $pdo->prepare($totalParcelsQuery);
    $stmtParcels->execute();
    $totalParcels = $stmtParcels->fetchColumn();
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
  }
  ?>



  <section class="section dashboard">
    <div class="row">

      <div class="col-lg-12">
        <div class="row">
          <!-- Total Zones Card -->
          <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="card info-card zones-card">
              <div class="card-body">
                <h5 class="card-title">Total Zones</h5>
                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="background-color: #e0f7fa;">
                    <i class="bi bi-geo-alt" style="color: #28A745; font-size: 24px;"></i>
                  </div>
                  <div class="ps-3">
                    <h6><?php echo $totalZones; ?></h6>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Total Blocks Card -->
          <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="card info-card blocks-card">
              <div class="card-body">
                <h5 class="card-title">Total Blocks</h5>
                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="background-color: #e0f7fa;">
                    <i class="bi bi-grid" style="color: #28A745; font-size: 24px;"></i>
                  </div>
                  <div class="ps-3">
                    <h6><?php echo $totalBlocks; ?></h6>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Total Parcels Card -->
          <div class="col-lg-2 col-md-4 col-sm-6">
            <div class="card info-card parcels-card">
              <div class="card-body">
                <h5 class="card-title">Total Parcels</h5>
                <div class="d-flex align-items-center">
                  <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="background-color: #e0f7fa;">
                    <i class="bi bi-box-seam" style="color: #28A745; font-size: 24px;"></i>
                  </div>
                  <div class="ps-3">
                    <h6><?php echo $totalParcels; ?></h6>
                  </div>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div><!-- End Cards -->

      <!-- Filter Button -->
      <div class="col-lg-3">
        <button id="openDrawerBtn" class="filter-button"><i class="bi bi-arrow-down-square"></i> Filter</button>

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
            <div class="mb-3">
              <div class="form-group">
                <select class="form-select" id="block-select" aria-label="Select Block">
                  <option selected>Select Block</option>
                  <!-- Blocks will be populated based on the selected zone -->
                </select>
              </div>

              <?php
              try {
                $stmt = $pdo->query("SELECT DISTINCT modification_type FROM public.tbl_landuse_f ORDER BY modification_type ASC");
                $categories = $stmt->fetchAll(PDO::FETCH_ASSOC);
              } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
              }
              ?>
            </div>

            <div class="mb-3">
              <div class="form-group">
                <select class="form-select" aria-label="Select Category">
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
          </div>
          <!-- Add more filter options as needed -->
          <button id="applyFiltersBtn" class="apply-button">Apply Filters</button>
        </div>
        </form>
      </div>
    </div>

  
    <div class="col-lg-12">
      <div class="row">

        <div class="col-lg-4">
          <div class="card">

            <div class="filter">
              <a class="icon" href="#" data-bs-toggle="dropdown"><i class="bi bi-three-dots"></i></a>
              <ul class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <li class="dropdown-header text-start">
                  <h6>Filter</h6>
                </li>

                <li><a class="dropdown-item" href="#">Today</a></li>
                <li><a class="dropdown-item" href="#">This Month</a></li>
                <li><a class="dropdown-item" href="#">This Year</a></li>
              </ul>
            </div>

            <div class="card-body">
              <h5 class="card-title">Reports <span>/Today</span></h5>

              <!-- Line Chart -->
              <div id="reportsChart"></div>

              <script>
                document.addEventListener("DOMContentLoaded", () => {
                  new ApexCharts(document.querySelector("#reportsChart"), {
                    series: [{
                      name: 'Sales',
                      data: [31, 40, 28, 51, 42, 82, 56],
                    }, {
                      name: 'Revenue',
                      data: [11, 32, 45, 32, 34, 52, 41]
                    }, {
                      name: 'Customers',
                      data: [15, 11, 32, 18, 9, 24, 11]
                    }],
                    chart: {
                      height: 350,
                      type: 'area',
                      toolbar: {
                        show: false
                      },
                    },
                    markers: {
                      size: 4
                    },
                    colors: ['#4154f1', '#2eca6a', '#ff771d'],
                    fill: {
                      type: "gradient",
                      gradient: {
                        shadeIntensity: 1,
                        opacityFrom: 0.3,
                        opacityTo: 0.4,
                        stops: [0, 90, 100]
                      }
                    },
                    dataLabels: {
                      enabled: false
                    },
                    stroke: {
                      curve: 'smooth',
                      width: 2
                    },
                    xaxis: {
                      type: 'datetime',
                      categories: ["2018-09-19T00:00:00.000Z", "2018-09-19T01:30:00.000Z", "2018-09-19T02:30:00.000Z", "2018-09-19T03:30:00.000Z", "2018-09-19T04:30:00.000Z", "2018-09-19T05:30:00.000Z", "2018-09-19T06:30:00.000Z"]
                    },
                    tooltip: {
                      x: {
                        format: 'dd/MM/yy HH:mm'
                      },
                    }
                  }).render();
                });
              </script>
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
              <h5 class="card-title">Column Chart</h5>

              <!-- Column Chart -->
              <div id="columnChart"></div>

              <script>
                document.addEventListener("DOMContentLoaded", () => {
                  new ApexCharts(document.querySelector("#columnChart"), {
                    series: [{
                      name: 'Net Profit',
                      data: [44, 55, 57, 56, 61, 58, 63, 60, 66]
                    }, {
                      name: 'Revenue',
                      data: [76, 85, 101, 98, 87, 105, 91, 114, 94]
                    }, {
                      name: 'Free Cash Flow',
                      data: [35, 41, 36, 26, 45, 48, 52, 53, 41]
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
                      categories: ['Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct'],
                    },
                    yaxis: {
                      title: {
                        text: '$ (thousands)'
                      }
                    },
                    fill: {
                      opacity: 1
                    },
                    tooltip: {
                      y: {
                        formatter: function(val) {
                          return "$ " + val + " thousands"
                        }
                      }
                    }
                  }).render();
                });
              </script>
              <!-- End Column Chart -->

            </div>
          </div>
        </div>

        <div class="col-lg-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Bar Chart</h5>

              <!-- Bar Chart -->
              <div id="barChart"></div>

              <script>
                document.addEventListener("DOMContentLoaded", () => {
                  new ApexCharts(document.querySelector("#barChart"), {
                    series: [{
                      data: [400, 430, 448, 470, 540, 580, 690, 1100, 1200, 1380]
                    }],
                    chart: {
                      type: 'bar',
                      height: 350
                    },
                    plotOptions: {
                      bar: {
                        borderRadius: 4,
                        horizontal: true,
                      }
                    },
                    dataLabels: {
                      enabled: false
                    },
                    xaxis: {
                      categories: ['South Korea', 'Canada', 'United Kingdom', 'Netherlands', 'Italy', 'France', 'Japan',
                        'United States', 'China', 'Germany'
                      ],
                    }
                  }).render();
                });
              </script>
              <!-- End Bar Chart -->

            </div>
          </div>
        </div>


        <div class="col-lg-4">
          <div class="card">
            <div class="card-body">
              <h5 class="card-title">Pie Chart</h5>

              <!-- Pie Chart -->
              <div id="pieChart"></div>

              <script>
                document.addEventListener("DOMContentLoaded", () => {
                  new ApexCharts(document.querySelector("#pieChart"), {
                    series: [44, 55, 13, 43, 22],
                    chart: {
                      height: 350,
                      type: 'pie',
                      toolbar: {
                        show: true
                      }
                    },
                    labels: ['Team A', 'Team B', 'Team C', 'Team D', 'Team E']
                  }).render();
                });
              </script>
              <!-- End Pie Chart -->

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
    </script>