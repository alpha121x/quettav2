// Code for add applicants handle form fields thorugh ajax //
$(document).ready(function () {
  // Handle district change
  $("#district").change(function () {
    var district_id = $(this).val();
    $.ajax({
      url: "add_applicants.php",
      method: "POST",
      data: {
        action: "get_tehsils",
        district_id: district_id,
      },
      success: function (response) {
        $("#tehsil").html(response);
        $("#circle").html('<option value="">Select Circle</option>'); // Reset circles and mozahs
        $("#mozah").html('<option value="">Select Mozah</option>');
        $("#applicant").html('<option value="">Select Applicant</option>');
      },
    });
  });

  // Handle tehsil change
  $("#tehsil").change(function () {
    var tehsil_id = $(this).val();
    $.ajax({
      url: "add_applicants.php",
      method: "POST",
      data: {
        action: "get_circles",
        tehsil_id: tehsil_id,
      },
      success: function (response) {
        $("#circle").html(response);
        $("#mozah").html('<option value="">Select Mozah</option>'); // Reset mozahs
        $("#applicant").html('<option value="">Select Applicant</option>');
      },
    });
  });

  // Handle circle change
  $("#circle").change(function () {
    var circle_id = $(this).val();
    $.ajax({
      url: "add_applicants.php",
      method: "POST",
      data: {
        action: "get_mozahs",
        circle_id: circle_id,
      },
      success: function (response) {
        $("#mozah").html(response);
        $("#applicant").html('<option value="">Select Applicant</option>');
      },
    });
  });

  // Handle mozah change
  $("#mozah").change(function () {
    $.ajax({
      url: "add_applicants.php",
      method: "POST",
      data: {
        action: "get_applicants",
      },
      success: function (response) {
        $("#applicant").html(response);
      },
    });
  });
});
////////////////////////////////

/// select2 js ////
$(document).ready(function() {
    // Initialize select2 on all select elements
    $('#district, #tehsil, #circle, #mozah, #applicant').select2();
});
///////////////////
