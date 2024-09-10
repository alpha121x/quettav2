<main id="main" class="main">

  <div class="pagetitle">
    <nav>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="index.php">Home</a></li>
        <li class="breadcrumb-item active">Dashboard</li>
      </ol>
    </nav>
  </div><!-- End Page Title -->

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
            <div class="mb-3">
              <div class="form-group">
                <select class="form-select" id="zone-select" aria-label="Select Zone">
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
            <div class="mb-3">
              <div class="form-group">
                <select class="form-select" id="category-select" aria-label="Select Category">
               
                </select>
              </div>
            </div>

            <!-- Land Type Selection -->
            <div class="mb-3">
              <div class="form-group">
                <select id="landTypeSelect" class="form-select" aria-label="Select Land Type">
                  <option selected>Select Land Type</option>
               
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

<script src="assets/js/dashboard.js"></script>
