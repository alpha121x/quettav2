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
          <!-- Data Table -->
          <div class="card">
            <div class="card-body">
              <table id="abc" class="table table-striped" style="width:100%">
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
                <tbody id="tbody">
                  <!-- Dynamic rows will be injected here by DataTables -->
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
      $('#abc').DataTable({
        "processing": true,
        "serverSide": true,
        "pageLength": 10, // Default rows per page
        "ajax": {
          "url": "DAL/fetch_dummydata.php", // Make sure this points to the correct server-side script
          "type": "POST",
          "dataSrc": "data" // Ensure that your server-side script returns a 'data' key with an array
        },
        "columns": [
          { "data": "parcel_id" },
          { "data": "zone_code" },
          { "data": "land_type" },
          { "data": "land_sub_type" },
          { "data": "modification_type" },
          { "data": "building_height" },
          { "data": "building_condition" }
        ]
      });
    });
  </script>
</body>
