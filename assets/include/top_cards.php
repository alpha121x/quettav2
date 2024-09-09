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
                <h6 id="total-zones">Loading...</h6>
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
                <h6 id="total-blocks">Loading...</h6>
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
                <h6 id="total-parcels">Loading...</h6>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Merge Parcels Card -->
<div class="col-lg-2 col-md-4 col-sm-6">
    <div class="card info-card merge-parcels-card">
        <div class="card-body">
            <h5 class="card-title">Merge Parcels</h5>
            <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="background-color: #e8f5e9;">
                    <i class="bi bi-union" style="color: #4CAF50; font-size: 24px;"></i>
                </div>
                <div class="ps-3">
                <h6 id="merge-parcels">Loading...</h6>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Same Parcels Card -->
<div class="col-lg-2 col-md-4 col-sm-6">
    <div class="card info-card same-parcels-card">
        <div class="card-body">
            <h5 class="card-title">Same Parcels</h5>
            <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="background-color: #fff3e0;">
                    <i class="bi bi-boxes" style="color: #FF9800; font-size: 24px;"></i>
                </div>
                <div class="ps-3">
                <h6 id="same-parcels">Loading...</h6>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Splits Parcels Card -->
<div class="col-lg-2 col-md-4 col-sm-6">
    <div class="card info-card splits-count-card">
        <div class="card-body">
            <h5 class="card-title">Split Parcels</h5>
            <div class="d-flex align-items-center">
                <div class="card-icon rounded-circle d-flex align-items-center justify-content-center" style="background-color: #e1f5fe;">
                    <i class="bi bi-dash-square" style="color: #2196F3; font-size: 24px;"></i>
                </div>
                <div class="ps-3">
                <h6 id="split-parcels">Loading...</h6>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // Fetch data using AJAX
        $.ajax({
            url: './DAL/fetch_cards_data.php',
            method: 'GET',
            dataType: 'json',
            success: function(data) {
                // Check for errors
                if (data.error) {
                    console.error('Server Error:', data.error);
                } else {
                    // Update card values
                    $('#total-zones').text(data.totalZones || 'N/A');
                    $('#total-blocks').text(data.totalBlocks || 'N/A');
                    $('#total-parcels').text(data.totalParcels || 'N/A');
                    $('#merge-parcels').text(data.mergeParcels || 'N/A');
                    $('#same-parcels').text(data.sameParcels || 'N/A');
                    $('#split-parcels').text(data.splitParcels || 'N/A');
                }
            },
            error: function(jqXHR, textStatus, errorThrown) {
                console.error('AJAX Error:', textStatus, errorThrown);
            }
        });
    });
</script>

