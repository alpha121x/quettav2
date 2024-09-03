<?php include(__DIR__ . "/auth.php") ?>
<?php
require "./DAL/db_config.php";
?>

<head>
  <title>Parcel Details</title>
  <?php include(__DIR__ . "/assets/include/linked-files.php") ?>
</head>

<body>
  <!-- ======= Header ======= -->
  <?php include(__DIR__ . "/assets/include/header-nav.php") ?>
  <!-- End Header -->

  <main id="main" class="main">

    <div class="pagetitle">
      <nav>
        <ol class="breadcrumb">
          <li class="breadcrumb-item"><a href="index.html">Home</a></li>
          <li class="breadcrumb-item active">Parcel Details</li>
        </ol>
      </nav>
    </div><!-- End Page Title -->

    <section class="section">
      <div class="row">
        <div class="col-lg-12">
          <div class="container mt-4">
            <div class="row align-items-end">

              <!-- Zone Selection -->
              <?php
              try {
                $stmt = $pdo->query("SELECT DISTINCT zone_code FROM public.tbl_landuse_f ORDER BY zone_code ASC");
                $zones = $stmt->fetchAll(PDO::FETCH_ASSOC);
              } catch (PDOException $e) {
                echo "Error: " . $e->getMessage();
              }
              ?>
              <div class="col-md-3 mb-3">
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
              <div class="col-md-3 mb-3">
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
              <div class="col-md-3 mb-3">
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

              <!-- Search Button -->
              <div class="col-md-3 mb-3">
                <div class="form-group">
                  <button type="button" class="btn btn-primary" id="search-btn">Search</button>
                </div>
              </div>

            </div>
          </div>

          <!-- Data Table -->
          <div class="card">
            <div class="card-body">
              <table id="datatable" class="table dataable" style="width:100%">
                <thead>
                  <tr>
                    <th>Parcel ID</th>
                    <th>Zone Code</th>
                    <th>Land Type</th>
                    <th>Land Sub-Type</th>
                    <th>Modification Type</th>
                    <th>Building Height</th>
                    <th>Building Condition</th>
                  </tr>
                </thead>
                <tbody>
                  <!-- Data will be inserted here -->
                </tbody>
              </table>
            </div>
          </div>


        </div>
      </div>
    </section>

  </main><!-- End #main -->

  <!-- Footer -->
  <?php include(__DIR__ . "/assets/include/footer.php") ?>
  <!-- Back to top -->
  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <!-- Scripts -->
  <?php include(__DIR__ . "/assets/include/script-files.php") ?>

  <script>
    $(document).ready(function() {
      // Initialize DataTable
      $('#datatable').DataTable();
    });
  </script>


  <!-- AJAX Scripts -->
  <script>
    $(document).ready(function() {
      // Initialize DataTable
      var table = $('#datatable').DataTable();

      // Fetch blocks when zone is selected
      $('#zone-select').on('change', function() {
        var zoneCode = $(this).val();
        $.ajax({
          type: 'POST',
          url: 'DAL/fetch_blocks.php',
          data: {
            zone_code: zoneCode
          },
          success: function(response) {
            $('#block-select').html(response);
          },
          error: function() {
            console.error('Error fetching blocks');
          }
        });
      });

      // Fetch data when search button is clicked
      $('#search-btn').on('click', function() {
        var zoneCode = $('#zone-select').val();
        var block = $('#block-select').val();
        var category = $('[aria-label="Select Category"]').val();

        $.ajax({
          type: 'POST',
          url: 'DAL/fetch_data.php',
          data: {
            zone_code: zoneCode,
            block: block,
            category: category
          },
          success: function(response) {
            // Clear the table and add new data
            table.clear();
            table.rows.add($(response)); // Ensure response is valid HTML for table rows
            table.draw();
          },
          error: function() {
            console.error('Error fetching data');
          }
        });
      });
    });
  </script>


</body>