var phoneInstance = null;

// DOM Initialization & Event Listeners
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

  const otpContainer = document.getElementById("otpInputs");
  if (otpContainer) {
    const otpInputs = otpContainer.querySelectorAll("input");

    otpInputs.forEach((input, index) => {
      input.addEventListener("input", () => {
        const val = input.value.replace(/\D/g, "");

        if (val.length > 1) {
          fillInputsFrom(val, index);
          return;
        }

        input.value = val;
        if (val && index < otpInputs.length - 1) {
          otpInputs[index + 1].focus();
        }
      });

      input.addEventListener("keydown", (e) => {
        if (e.key === "Backspace" && !input.value && index > 0) {
          otpInputs[index - 1].focus();
        }
      });

      input.addEventListener("paste", (e) => {
        e.preventDefault();
        const pasteData = (e.clipboardData || window.clipboardData).getData("text");
        const digits = pasteData.trim().replace(/\D/g, "");

        if (digits.length > 0) {
          fillInputsFrom(digits, index);
        }
      });
    });

    function fillInputsFrom(digits, startIndex) {
      const chars = digits.split("");
      let currentIdx = startIndex;

      chars.forEach((char) => {
        if (currentIdx < otpInputs.length) {
          otpInputs[currentIdx].value = char;
          currentIdx++;
        }
      });

      const focusIndex = Math.min(currentIdx, otpInputs.length - 1);
      otpInputs[focusIndex].focus();
    }
  }
});

// Mobile Input Initialization
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

// User Registration Handler
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
      reponse = request.responseText.trim();
      if(response == "Username, email, or mobile number already exists"){
         alert (response);
      }else{
        window.location.heref ="login.php";
      }
    }
  };

  request.open("POST", "registerProcess.php", true);
  request.send(form);
}

// User Login Handler
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
        window.location.href = "dashboard.php";
      } else {
        alert(response);
      }
    }
  };

  request.open("POST", "loginProcess.php", true);
  request.send(form);
}

// Forgot Password Handler
function forgetPassword() {
  var email = document.getElementById("email").value.trim();
  var forgetBtn = document.getElementById("forgetBtn");
  var btnText = document.getElementById("btnText");
  var btnSpinner = document.getElementById("btnSpinner");

  if (!email) {
    alert("Please enter your email address.");
    return;
  }

  
  forgetBtn.disabled = true;
  btnText.textContent = "Sending...";
  btnSpinner.classList.remove("d-none");

  var form = new FormData();
  form.append("email", email);

  var request = new XMLHttpRequest();
  request.onreadystatechange = function () {
    if (request.readyState == 4) {
     
      btnText.textContent = "Send Reset Code";
      btnSpinner.classList.add("d-none");

      if (request.status == 200) {
        var response = request.responseText.trim();

        if (response === "success") {
          window.location.href = "resetPassword.php";
        } else {
          alert(response);
        }
      } else {
        alert("An error occurred while sending the email. Please try again.");
      }
    }
  };

  request.open("POST", "forgertPasswordProcess.php", true);
  request.send(form);
}

// Reset Password Handler
function resetPasswordProcess() {
  const inputs = document.querySelectorAll("#otpInputs input");
  var verificationCode = "";
  inputs.forEach((input) => {
    verificationCode += input.value.trim();
  });

  var password = document.getElementById("newPassword").value.trim();
  var confirmPassword = document.getElementById("confirmPassword").value.trim();

  if (verificationCode.length !== 8) {
    alert("Please enter the complete 8-digit verification code.");
    return;
  }

  if (password !== confirmPassword) {
    alert("Passwords do not match.");
    return;
  }

  var form = new FormData();
  form.append("verificationCode", verificationCode);
  form.append("password", password);

  var request = new XMLHttpRequest();

  request.onreadystatechange = function () {
    if (request.readyState == 4 && request.status == 200) {
      var response = request.responseText.trim();
      if (response === "success") {
        alert("Password updated successfully!");
        window.location.href = "login.php";
      } else {
        alert(response);
      }
    }
  };

  request.open("POST", "resetPasswordProcess.php", true);
  request.send(form);
}