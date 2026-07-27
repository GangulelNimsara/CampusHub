var phoneInstance = null;

document.addEventListener("DOMContentLoaded", () => {
  phoneInstance = initializeMobileInput();

  const loginForm = document.getElementById("loginForm");
  if (loginForm) {
    loginForm.addEventListener("submit", function (e) {
      e.preventDefault();
      loginProcess();
    });
  }

  const registerForm = document.getElementById("registerForm");
  if (registerForm) {
    registerForm.addEventListener("submit", function (e) {
      e.preventDefault();
      registerProcess();
    });
  }
});

function initializeMobileInput() {
  const mobileInput = document.getElementById("mobile");
  if (!mobileInput) return null;

  return window.intlTelInput(mobileInput, {
    separateDialCode: true,
    initialCountry: "lk",
    nationalMode: false,
    autoHideDialCode: false,
    formatOnDisplay: true,
    utilsScript:
      "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
  });
}

function registerProcess() {
  var username = document.getElementById("username").value.trim();
  var firstName = document.getElementById("firstName").value.trim();
  var lastName = document.getElementById("lastName").value.trim();
  var password = document.getElementById("password").value.trim();
  var confirmPassword = document.getElementById("confirmPassword").value.trim();
  var email = document.getElementById("email").value.trim();

  var mobile = phoneInstance
    ? phoneInstance.getNumber()
    : document.getElementById("mobile").value.trim();

  var form = new FormData();
  form.append("username", username);
  form.append("firstName", firstName);
  form.append("lastName", lastName);
  form.append("password", password);
  form.append("confirmPassword", confirmPassword);
  form.append("email", email);
  form.append("mobile", mobile);

  var request = new XMLHttpRequest();
  request.onreadystatechange = function () {
    if (request.readyState == 4 && request.status == 200) {
      alert(request.responseText);
    }
  };

  request.open("POST", "registerProcess.php", true);
  request.send(form);
}

function loginProcess() {
  var username = document.getElementById("username").value.trim();
  var password = document.getElementById("password").value.trim();

  var form = new FormData();
  form.append("username", username);
  form.append("password", password);

  var request = new XMLHttpRequest();
  request.onreadystatechange = function () {
    if (request.readyState == 4 && request.status == 200) {
      var response = request.responseText.trim();

      if (response === "user_not_found") {
        window.location.href = "register.php";
      } else if (response === "success") {
        window.location.href = "index.php";
      } else {
        
      }
    }
  };

  request.open("POST", "loginProcess.php", true);
  request.send(form);
}