<?php include(__DIR__ . "/auth.php") ?>

<head>
    <title>GIS Viewer</title>

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
                    <li class="breadcrumb-item active">GIS Viewer</li>
                </ol>
            </nav>
        </div><!-- End Page Title -->

        <section class="section">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row">

                        <div class="col-lg-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Filter</h5>

                                    <!-- Vertical Form -->
                                    <form class="row g-3">
                                        <div class="col-12">
                                            <label for="inputNanme4" class="form-label">Your Name</label>
                                            <input type="text" class="form-control" id="inputNanme4">
                                        </div>
                                        <div class="col-12">
                                            <label for="inputEmail4" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="inputEmail4">
                                        </div>
                                        <div class="col-12">
                                            <label for="inputPassword4" class="form-label">Password</label>
                                            <input type="password" class="form-control" id="inputPassword4">
                                        </div>
                                        <div class="col-12">
                                            <label for="inputAddress" class="form-label">Address</label>
                                            <input type="text" class="form-control" id="inputAddress" placeholder="1234 Main St">
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                            <button type="reset" class="btn btn-secondary">Reset</button>
                                        </div>
                                    </form><!-- Vertical Form -->

                                </div>
                            </div>
                        </div>

                        <div class="col-lg-6">
                            <div class=" card">
                                <div class="card-body">
                                    <!-- Map -->
                                    <h5 class="card-title">Map View</h5>
                                    <div id="map" class="contact-map">
                                        <iframe
                                            src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d52599.105009580575!2d66.9551262983524!3d30.17984019470825!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ed2e4a21261a2ef%3A0x6f7d937d15cb23a7!2sQuetta%2C%20Balochistan!5e0!3m2!1sen!2s!4v1693245110924"
                                            width="100%"
                                            height="450"
                                            frameborder="0"
                                            style="border:0"
                                            allowfullscreen>
                                        </iframe>

                                    </div>
                                </div>
                            </div>
                        </div><!-- Map end -->

                        <div class="col-lg-3">
                            <div class="card">
                                <div class="card-body">
                                    <h5 class="card-title">Filter</h5>

                                    <!-- Vertical Form -->
                                    <form class="row g-3">
                                        <div class="col-12">
                                            <label for="inputNanme4" class="form-label">Your Name</label>
                                            <input type="text" class="form-control" id="inputNanme4">
                                        </div>
                                        <div class="col-12">
                                            <label for="inputEmail4" class="form-label">Email</label>
                                            <input type="email" class="form-control" id="inputEmail4">
                                        </div>
                                        <div class="col-12">
                                            <label for="inputPassword4" class="form-label">Password</label>
                                            <input type="password" class="form-control" id="inputPassword4">
                                        </div>
                                        <div class="col-12">
                                            <label for="inputAddress" class="form-label">Address</label>
                                            <input type="text" class="form-control" id="inputAddress" placeholder="1234 Main St">
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn btn-primary">Submit</button>
                                            <button type="reset" class="btn btn-secondary">Reset</button>
                                        </div>
                                    </form><!-- Vertical Form -->

                                </div>
                            </div>
                        </div>

                      

                    </div>
                </div>
            </div>



        </section>

    </main><!-- End #main -->


    <?php include(__DIR__ . "/assets/include/footer.php") ?>

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i class="bi bi-arrow-up-short"></i></a>

    <?php include(__DIR__ . "/assets/include/script-files.php") ?>

</body>