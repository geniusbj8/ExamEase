//admin js

document.addEventListener("DOMContentLoaded", function() {
  // Add event listener for managing users
  const addUserButton = document.querySelector('.add-user-btn');
  
  addUserButton.addEventListener('click', function() {
    alert("Redirecting to user creation form...");
    // You can redirect to a separate page for adding users or open a modal.
  });
  
  const editButtons = document.querySelectorAll('button:contains("Edit")');
  const deleteButtons = document.querySelectorAll('button:contains("Delete")');

  // Event listeners for Edit and Delete buttons
  editButtons.forEach(button => {
    button.addEventListener('click', function() {
      alert("Editing user...");
      // Implement edit user logic here.
    });
  });

  deleteButtons.forEach(button => {
    button.addEventListener('click', function() {
      alert("Deleting user...");
      // Implement delete user logic here.
    });
  });
});
