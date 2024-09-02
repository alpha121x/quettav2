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
              <div class="col-md-3 mb-3">
                <div class="form-group">
                  <select class="form-select" aria-label="Default select example">
                    <option selected>Select Zone</option>
                    <option value="1">One</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                  </select>
                </div>
              </div>
              <div class="col-md-3 mb-3">
                <div class="form-group">
                  <select class="form-select" aria-label="Default select example">
                    <option selected>Select Tehsil</option>
                    <option value="1">One</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                  </select>
                </div>
              </div>
              <div class="col-md-3 mb-3">
                <div class="form-group">
                  <select class="form-select" aria-label="Default select example">
                    <option selected>Select Category</option>
                    <option value="1">One</option>
                    <option value="2">Two</option>
                    <option value="3">Three</option>
                  </select>
                </div>
              </div>
              <div class="col-md-3 mb-3">
                <div class="form-group">
                  <input type="text" class="form-control" id="filter1" placeholder="Search...">
                </div>
              </div>
            </div>
          </div>

          <div class="card">
            <div class="card-body">


             <?php include "./DAL/get_parcel_details.php"; ?>

              <!-- HTML Table -->
              <table id="datatable" class="table datatable">
                <thead>
                  <tr>
                    <th><b>Parcel ID</b></th>
                    <th>Zone Code</th>
                    <th>Land Type</th>
                    <th>Land Sub-Type</th>
                    <th>Building Height</th>
                    <th>Building Condition</th>
                  </tr>
                </thead>
                <tbody>
                  <?php if (!empty($parcelDetails)): ?>
                    <?php foreach ($parcelDetails as $parcel): ?>
                      <tr>
                        <td><?php echo htmlspecialchars($parcel['parcel_id']); ?></td>
                        <td><?php echo htmlspecialchars($parcel['zone_code']); ?></td>
                        <td><?php echo htmlspecialchars($parcel['land_type']); ?></td>
                        <td><?php echo htmlspecialchars($parcel['land_sub_type']); ?></td>
                        <td><?php echo htmlspecialchars($parcel['building_height']); ?></td>
                        <td><?php echo htmlspecialchars($parcel['building_condition']); ?></td>
                      </tr>
                    <?php endforeach; ?>
                  <?php else: ?>
                    <tr>
                      <td colspan="6">No parcel details found.</td>
                    </tr>
                  <?php endif; ?>
                </tbody>
              </table>



            </div>
          </div>

        </div>
      </div>
    </section>

    </div>
    </div>
    </section>

  </main><!-- End #main -->


  <?php include(__DIR__ . "/assets/include/footer.php") ?>

  <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

  <?php include(__DIR__ . "/assets/include/script-files.php") ?>

</body>