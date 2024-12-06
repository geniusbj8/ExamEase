// login js

document.addEventListener("DOMContentLoaded", function() {
  const loginSection = document.querySelector('.login-section');
  const registerSection = document.querySelector('.register-section');
  const signUpLink = document.querySelector('.sign-up-link'); // Sign Up link in the login form
  const loginLink = document.querySelector('.login-link'); // Login link in the register form

  // Initially, only show the login section
  registerSection.style.display = 'none';

  // When the Sign Up link is clicked, hide the login section and show the register section
  signUpLink.addEventListener('click', function(event) {
    event.preventDefault();
    loginSection.style.display = 'none';  // Hide the login section
    registerSection.style.display = 'flex'; // Show the register section
  });

  // When the Login link in the register section is clicked, hide the register section and show the login section
  loginLink.addEventListener('click', function(event) {
    event.preventDefault();
    registerSection.style.display = 'none'; // Hide the register section
    loginSection.style.display = 'flex'; // Show the login section
  });
});