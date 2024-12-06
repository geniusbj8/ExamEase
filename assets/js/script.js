// script.js

// contact form script

$(document).ready(function() {
  // When the form is submitted
  $('#contactForm').submit(function(event) {
      event.preventDefault(); // Prevent the default form submission

      // Serialize the form data
      var formData = $(this).serialize();
      console.log("Form Data: ", formData);  // Log the serialized data for debugging

      // Send the data using AJAX
      $.ajax({
          url: 'submit_contact.php', // The server-side script to process the data
          type: 'POST', // The HTTP method to send the data
          data: formData, // The form data to send
          success: function(response) {
              console.log("Response: ", response);  // Log the response from the server

              // Check if response contains 'success' to trigger SweetAlert
              if (response === 'success') {
                  Swal.fire({
                      title: 'Success!',
                      text: 'Your message has been sent successfully!',
                      icon: 'success',
                      confirmButtonText: 'Okay'
                  });
              } else {
                  Swal.fire({
                      title: 'Error!',
                      text: 'There was an error submitting your form. Please try again.',
                      icon: 'error',
                      confirmButtonText: 'Okay'
                  });
              }

              // Optionally reset the form after submission
              $('#contactForm')[0].reset();
          },
          error: function(xhr, status, error) {
              console.log("Error: ", error); // Log the error if there's an issue with the AJAX request
              Swal.fire({
                  title: 'Error!',
                  text: 'There was an error submitting your form. Please try again.',
                  icon: 'error',
                  confirmButtonText: 'Okay'
              });
          }
      });
  });
});



