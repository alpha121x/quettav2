// Fetch blocks when zone is selected
$('#zone-select').on('change', function() {
  var zoneCode = $(this).val();

  // Clear block selection and reset to default "Select Block"
  $('#block-select').html('<option selected>Select Block</option>');

  if (zoneCode) {
    // Fetch blocks based on selected zone
    $.ajax({
      type: 'POST',
      url: 'DAL/fetch_blocks.php',
      data: {
        zone_code: zoneCode
      },
      success: function(response) {
        // Append the fetched blocks while keeping "Select Block" as the default
        $('#block-select').append(response);
      },
      error: function() {
        console.error('Error fetching blocks');
      }
    });
  }
});