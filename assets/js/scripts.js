// Global Variables & Alert Mixin
var phoneInstance = null;

const CampusAlert = Swal.mixin({
  customClass: {
    popup: 'border border-2 border-dark rounded-4 shadow-lg bg-white p-4',
    title: 'fw-bold text-dark fs-4 mb-2',
    htmlContainer: 'text-secondary small mb-3',
    confirmButton: 'btn btn-primary rounded-pill px-4 me-2 fw-semibold',
    cancelButton: 'btn btn-outline-dark rounded-pill px-4 fw-semibold',
    denyButton: 'btn btn-danger rounded-pill px-4 me-2 fw-semibold'
  },
  buttonsStyling: false,
  background: '#ffffff'
});

// Gallery Modal State
window.galleryItems = window.galleryItems || [];
var currentGalleryIndex = 0;

// DOM Initialization
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

  // Initialize FullCalendar
  var calendarEl = document.getElementById('calendar');
  if (calendarEl && typeof FullCalendar !== "undefined") {
    var calendar = new FullCalendar.Calendar(calendarEl, {
      initialView: 'dayGridMonth',
      headerToolbar: {
        left: 'prev,next',
        center: 'title',
        right: 'today'
      },
      height: 320,
      contentHeight: 280,
      aspectRatio: 1.35,
      fixedWeekCount: false,
      eventDisplay: 'block',
      events: 'getEventsProcess.php',
      eventDidMount: function (info) {
        if (info.event.extendedProps.description) {
          info.el.setAttribute('title', info.event.title + ' (' + info.event.extendedProps.description + ')');
        } else {
          info.el.setAttribute('title', info.event.title);
        }
      }
    });
    calendar.render();
  }

  const profileImageInput = document.getElementById("profile-image-input");
  if (profileImageInput) {
    profileImageInput.addEventListener("change", function () {
      if (this.files && this.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
          document.getElementById('profile-img-preview').src = e.target.result;
        };
        reader.readAsDataURL(this.files[0]);
      }
    });
  }
});

// Mobile Input Setup
function initializeMobileInput() {
  const mobileInput = document.getElementById("mobile");
  if (!mobileInput) return null;

  return window.intlTelInput(mobileInput, {
    separateDialCode: true,
    initialCountry: "lk",
    nationalMode: false,
    autoHideDialCode: false,
    formatOnDisplay: true,
    utilsScript: "https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.8/js/utils.js",
  });
}

// Authentication Handlers
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
      var response = request.responseText.trim();

      if (response === "success") {
        CampusAlert.fire({
          icon: 'success',
          title: 'Account Created! 🎉',
          text: 'Your registration was successful. You can now log in.',
          confirmButtonText: 'Go to Login 🚀'
        }).then(() => {
          window.location.href = "login.php";
        });
      } else {
        CampusAlert.fire({
          icon: 'warning',
          title: 'Registration Notice',
          text: response,
          confirmButtonText: 'Got it'
        });
      }
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
        CampusAlert.fire({
          icon: 'info',
          title: 'Account Not Found',
          text: 'No account exists with these credentials. Redirecting to registration...',
          timer: 2000,
          showConfirmButton: false
        }).then(() => {
          window.location.href = "register.php";
        });
      } else if (response === "success") {
        window.location.href = "dashboard.php";
      } else {
        CampusAlert.fire({
          icon: 'error',
          title: 'Login Failed',
          text: response,
          confirmButtonText: 'Try Again'
        });
      }
    }
  };

  request.open("POST", "loginProcess.php", true);
  request.send(form);
}

function forgetPassword() {
  var email = document.getElementById("email").value.trim();
  var forgetBtn = document.getElementById("forgetBtn");
  var btnText = document.getElementById("btnText");
  var btnSpinner = document.getElementById("btnSpinner");

  if (!email) {
    CampusAlert.fire({
      icon: 'warning',
      title: 'Email Required',
      text: 'Please enter your registered email address.',
      confirmButtonText: 'OK'
    });
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
          CampusAlert.fire({
            icon: 'success',
            title: 'Code Sent! 📧',
            text: 'A verification code has been sent to your email.',
            confirmButtonText: 'Enter Code'
          }).then(() => {
            window.location.href = "resetPassword.php";
          });
        } else {
          CampusAlert.fire({
            icon: 'error',
            title: 'Request Failed',
            text: response,
            confirmButtonText: 'OK'
          });
        }
      } else {
        CampusAlert.fire({
          icon: 'error',
          title: 'Server Error',
          text: 'An error occurred while sending the email. Please try again.',
          confirmButtonText: 'OK'
        });
      }
    }
  };

  request.open("POST", "forgertPasswordProcess.php", true);
  request.send(form);
}

function resetPasswordProcess() {
  const inputs = document.querySelectorAll("#otpInputs input");
  var verificationCode = "";
  inputs.forEach((input) => {
    verificationCode += input.value.trim();
  });

  var password = document.getElementById("newPassword").value.trim();
  var confirmPassword = document.getElementById("confirmPassword").value.trim();

  if (verificationCode.length !== 8) {
    CampusAlert.fire({
      icon: 'warning',
      title: 'Invalid Code',
      text: 'Please enter the full 8-digit verification code.',
      confirmButtonText: 'OK'
    });
    return;
  }

  if (password !== confirmPassword) {
    CampusAlert.fire({
      icon: 'warning',
      title: 'Password Mismatch',
      text: 'New password and confirmation password do not match.',
      confirmButtonText: 'OK'
    });
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
        CampusAlert.fire({
          icon: 'success',
          title: 'Password Updated! 🔒',
          text: 'Your password has been reset successfully.',
          confirmButtonText: 'Login Now'
        }).then(() => {
          window.location.href = "login.php";
        });
      } else {
        CampusAlert.fire({
          icon: 'error',
          title: 'Error',
          text: response,
          confirmButtonText: 'OK'
        });
      }
    }
  };

  request.open("POST", "resetPasswordProcess.php", true);
  request.send(form);
}

// Event Actions
function registerForEvent(eventId) {
  CampusAlert.fire({
    title: 'Register for Event?',
    text: "Are you sure you want to register for this campus event?",
    icon: 'question',
    showCancelButton: true,
    confirmButtonText: 'Yes, Register! 🎟️'
  }).then((result) => {
    if (result.isConfirmed) {
      var form = new FormData();
      form.append("eventId", eventId);

      var request = new XMLHttpRequest();
      request.onreadystatechange = function () {
        if (request.readyState == 4 && request.status == 200) {
          var response = request.responseText.trim();
          if (response === "success") {
            CampusAlert.fire({
              icon: "success",
              title: "Success! 🎉",
              text: "Registration request submitted.",
              confirmButtonText: "Great"
            }).then(() => {
              location.reload();
            });
          } else if (response === "login_required") {
            window.location.href = "login.php";
          } else {
            CampusAlert.fire({
              icon: "error",
              title: "Error",
              text: response,
              confirmButtonText: "Try Again"
            });
          }
        }
      };
      request.open("POST", "registerEventProcess.php", true);
      request.send(form);
    }
  });
}

function registerEvent() {
  const form = document.getElementById("registrationMenuForm");
  if (!form) return;

  const formData = new FormData(form);

  fetch("eventRegisterProcess.php", {
    method: "POST",
    body: formData
  })
    .then(response => response.text())
    .then(data => {
      const result = data.trim();

      if (result === "success") {
        CampusAlert.fire({
          icon: "success",
          title: "Registration Confirmed! 🎉",
          text: "Attendance registration was successful.",
          confirmButtonText: "Go to Dashboard 🚀"
        }).then(() => {
          window.location.href = "dashboard.php";
        });
      } else if (result === "already_registered") {
        CampusAlert.fire({
          icon: "warning",
          title: "Already Registered",
          text: "This attendee is already registered for this event.",
          confirmButtonText: "Got it"
        });
      } else if (result === "login_required") {
        window.location.href = "login.php";
      } else {
        CampusAlert.fire({
          icon: "error",
          title: "Registration Failed",
          text: result,
          confirmButtonText: "Try Again"
        });
      }
    })
    .catch(error => {
      console.error("Error:", error);
    });
}

// Gallery Lightbox & Navigation
function openImagePreview(index) {
  if (!window.galleryItems || window.galleryItems.length === 0) return;
  currentGalleryIndex = index;
  updateModalContent();

  var modalElem = document.getElementById('imagePreviewModal');
  if (modalElem) {
    var previewModal = bootstrap.Modal.getInstance(modalElem) || new bootstrap.Modal(modalElem);
    previewModal.show();
  }
}

function updateModalContent() {
  const imgElement = document.getElementById('imagePreviewSrc');
  const titleElement = document.getElementById('imagePreviewTitle');
  if (imgElement && window.galleryItems[currentGalleryIndex]) {
    imgElement.src = window.galleryItems[currentGalleryIndex].src;
  }
  if (titleElement && window.galleryItems[currentGalleryIndex]) {
    titleElement.textContent = window.galleryItems[currentGalleryIndex].title || 'Image Preview';
  }
}

function prevImage() {
  if (!window.galleryItems || window.galleryItems.length === 0) return;
  currentGalleryIndex = (currentGalleryIndex - 1 + window.galleryItems.length) % window.galleryItems.length;
  updateModalContent();
}

function nextImage() {
  if (!window.galleryItems || window.galleryItems.length === 0) return;
  currentGalleryIndex = (currentGalleryIndex + 1) % window.galleryItems.length;
  updateModalContent();
}

// Submit Feedback from eventRegister.php
function submitEventFeedback() {
  const messageInput = document.getElementById("eventFeedbackMessage");
  if (!messageInput) return;

  const message = messageInput.value.trim();

  if (!message) {
    CampusAlert.fire({
      icon: "warning",
      title: "Empty Message",
      text: "Please enter your feedback before submitting.",
      confirmButtonText: "OK"
    });
    return;
  }

  const formData = new FormData();
  formData.append("message", message);

  fetch("submitFeedbackProcess.php", {
    method: "POST",
    body: formData
  })
    .then(response => response.text())
    .then(data => {
      const result = data.trim();
      if (result === "success") {
        CampusAlert.fire({
          icon: "success",
          title: "Feedback Sent! 💬",
          text: "Thank you! Your feedback has been sent to the administrators.",
          confirmButtonText: "Great"
        });
        messageInput.value = "";
      } else if (result === "login_required") {
        window.location.href = "login.php";
      } else {
        CampusAlert.fire({
          icon: "error",
          title: "Submission Failed",
          text: result,
          confirmButtonText: "Try Again"
        });
      }
    })
    .catch(error => {
      console.error("Error:", error);
    });
}

// Submit Feedback from dashboard.php Modal
function submitDashboardFeedback() {
  const messageInput = document.getElementById("dashboardFeedbackMessage");
  if (!messageInput) return;

  const message = messageInput.value.trim();

  if (!message) {
    CampusAlert.fire({
      icon: "warning",
      title: "Empty Message",
      text: "Please enter your feedback before submitting.",
      confirmButtonText: "OK"
    });
    return;
  }

  const formData = new FormData();
  formData.append("message", message);

  fetch("submitFeedbackProcess.php", {
    method: "POST",
    body: formData
  })
    .then(response => response.text())
    .then(data => {
      const result = data.trim();
      if (result === "success") {
        const modalElem = document.getElementById('dashboardFeedbackModal');
        if (modalElem) {
          const modalInstance = bootstrap.Modal.getInstance(modalElem);
          if (modalInstance) modalInstance.hide();
        }

        CampusAlert.fire({
          icon: "success",
          title: "Feedback Sent! 💬",
          text: "Thank you! Your feedback has been submitted successfully.",
          confirmButtonText: "Great"
        });
        messageInput.value = "";
      } else if (result === "login_required") {
        window.location.href = "login.php";
      } else {
        CampusAlert.fire({
          icon: "error",
          title: "Submission Failed",
          text: result,
          confirmButtonText: "Try Again"
        });
      }
    })
    .catch(error => {
      console.error("Error:", error);
    });
}

function updateProfileProcess() {
  var form = document.getElementById("profileForm");
  var formData = new FormData(form);

  var fileInput = document.getElementById("profile-image-input");
  if (fileInput && fileInput.files.length > 0) {
    formData.append("profile_image", fileInput.files[0]);
  }

  fetch("updateProfileProcess.php", {
    method: "POST",
    body: formData
  })
    .then(response => response.text())
    .then(data => {
      var result = data.trim();
      if (result === "success") {
        CampusAlert.fire({
          icon: 'success',
          title: 'Profile Updated! 👤',
          text: 'Your profile changes have been saved and a confirmation email has been sent.',
          confirmButtonText: 'OK'
        }).then(() => {
          window.location.reload();
        });
      } else if (result === "no_changes") {
        CampusAlert.fire({
          icon: 'info',
          title: 'No Changes',
          text: 'No details were modified.',
          confirmButtonText: 'OK'
        });
      } else if (result === "login_required") {
        window.location.href = "login.php";
      } else {
        CampusAlert.fire({
          icon: 'error',
          title: 'Update Failed',
          text: result,
          confirmButtonText: 'Try Again'
        });
      }
    })
    .catch(error => {
      console.error("Error:", error);
    });
}

function adminLoginProcess() {
  var form = document.getElementById("adminLoginForm");
  var formData = new FormData(form);

  fetch("loginProcess.php", {
    method: "POST",
    body: formData
  })
    .then(response => {
      if (!response.ok) {
        throw new Error("HTTP error " + response.status);
      }
      return response.text();
    })
    .then(data => {
      var result = data.trim();
      if (result === "success") {
        window.location.href = "dashboard.php";
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Login Failed',
          text: result,
          confirmButtonColor: '#000'
        });
      }
    })
    .catch(error => {
      console.error("Error:", error);
      Swal.fire({
        icon: 'error',
        title: 'Error',
        text: 'File loginProcess.php not found. Make sure it is inside the admin folder!',
        confirmButtonColor: '#000'
      });
    });
}

function addStudentProcess() {
  var form = document.getElementById("addStudentForm");
  if (!form) return;

  var formData = new FormData(form);

  fetch("add.php", {
    method: "POST",
    body: formData
  })
    .then(res => res.text())
    .then(data => {
      var res = data.trim();
      if (res === "success") {
        Swal.fire({
          icon: 'success',
          title: 'Student Added!',
          confirmButtonColor: '#000'
        }).then(() => {
          window.location.href = "index.php";
        });
      } else {
        Swal.fire({ icon: 'error', title: 'Error', text: res, confirmButtonColor: '#000' });
      }
    })
    .catch(err => console.error(err));
}

function editStudentProcess() {
  var form = document.getElementById("editStudentForm");
  if (!form) return;

  var formData = new FormData(form);

  fetch("edit.php", {
    method: "POST",
    body: formData
  })
    .then(res => res.text())
    .then(data => {
      var res = data.trim();
      if (res === "success") {
        Swal.fire({
          icon: 'success',
          title: 'Student Updated!',
          confirmButtonColor: '#000'
        }).then(() => {
          window.location.href = "index.php";
        });
      } else {
        Swal.fire({ icon: 'error', title: 'Error', text: res, confirmButtonColor: '#000' });
      }
    })
    .catch(err => console.error(err));
}

function deleteStudent(id) {
  Swal.fire({
    title: 'Are you sure?',
    text: "This student account will be deleted permanently!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#000',
    confirmButtonText: 'Yes, delete it!'
  }).then((result) => {
    if (result.isConfirmed) {
      var formData = new FormData();
      formData.append("id", id);

      fetch("delete.php", {
        method: "POST",
        body: formData
      })
        .then(res => res.text())
        .then(data => {
          if (data.trim() === "success") {
            Swal.fire({
              icon: 'success',
              title: 'Deleted!',
              text: 'Student record has been deleted.',
              confirmButtonColor: '#000'
            }).then(() => {
              location.reload();
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: data.trim(),
              confirmButtonColor: '#000'
            });
          }
        })
        .catch(err => console.error(err));
    }
  });
}

function approveRegistration(id) {
  var formData = new FormData();
  formData.append("id", id);

  fetch("approve.php", {
    method: "POST",
    body: formData
  })
    .then(res => res.text())
    .then(data => {
      if (data.trim() === "success") {
        Swal.fire({
          icon: 'success',
          title: 'Approved!',
          text: 'Registration status has been updated to approved.',
          confirmButtonColor: '#000'
        }).then(() => {
          location.reload();
        });
      } else {
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: data.trim(),
          confirmButtonColor: '#000'
        });
      }
    })
    .catch(err => console.error(err));
}

function deleteRegistration(id) {
  Swal.fire({
    title: 'Are you sure?',
    text: "This registration will be permanently removed!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#000',
    confirmButtonText: 'Yes, delete it!'
  }).then((result) => {
    if (result.isConfirmed) {
      var formData = new FormData();
      formData.append("id", id);

      fetch("delete.php", {
        method: "POST",
        body: formData
      })
        .then(res => res.text())
        .then(data => {
          if (data.trim() === "success") {
            Swal.fire({
              icon: 'success',
              title: 'Deleted!',
              text: 'Registration has been deleted.',
              confirmButtonColor: '#000'
            }).then(() => {
              location.reload();
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: data.trim(),
              confirmButtonColor: '#000'
            });
          }
        })
        .catch(err => console.error(err));
    }
  });
}

function uploadMediaProcess() {
  var form = document.getElementById("uploadMediaForm");
  if (!form) return;

  var formData = new FormData(form);

  fetch("upload.php", {
    method: "POST",
    body: formData
  })
    .then(res => res.text())
    .then(data => {
      var res = data.trim();
      if (res === "success") {
        Swal.fire({
          icon: 'success',
          title: 'Media Uploaded!',
          confirmButtonColor: '#000'
        }).then(() => {
          window.location.href = "index.php";
        });
      } else {
        Swal.fire({ icon: 'error', title: 'Error', text: res, confirmButtonColor: '#000' });
      }
    })
    .catch(err => console.error(err));
}

function deleteMedia(id) {
  Swal.fire({
    title: 'Are you sure?',
    text: "This image will be deleted permanently!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#000',
    confirmButtonText: 'Yes, delete it!'
  }).then((result) => {
    if (result.isConfirmed) {
      var formData = new FormData();
      formData.append("id", id);

      fetch("delete.php", {
        method: "POST",
        body: formData
      })
        .then(res => res.text())
        .then(data => {
          if (data.trim() === "success") {
            Swal.fire({
              icon: 'success',
              title: 'Deleted!',
              text: 'Media item has been deleted.',
              confirmButtonColor: '#000'
            }).then(() => {
              location.reload();
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: data.trim(),
              confirmButtonColor: '#000'
            });
          }
        })
        .catch(err => console.error(err));
    }
  });
}

function addEventProcess() {
  var form = document.getElementById("addEventForm");
  if (!form) return;

  var formData = new FormData(form);

  fetch("add.php", {
    method: "POST",
    body: formData
  })
    .then(res => res.text())
    .then(data => {
      var res = data.trim();
      if (res === "success") {
        Swal.fire({
          icon: 'success',
          title: 'Event Created!',
          confirmButtonColor: '#000'
        }).then(() => {
          window.location.href = "index.php";
        });
      } else {
        Swal.fire({ icon: 'error', title: 'Error', text: res, confirmButtonColor: '#000' });
      }
    })
    .catch(err => console.error(err));
}

function editEventProcess() {
  var form = document.getElementById("editEventForm");
  if (!form) return;

  var formData = new FormData(form);

  fetch("edit.php", {
    method: "POST",
    body: formData
  })
    .then(res => res.text())
    .then(data => {
      var res = data.trim();
      if (res === "success") {
        Swal.fire({
          icon: 'success',
          title: 'Event Updated!',
          confirmButtonColor: '#000'
        }).then(() => {
          window.location.href = "index.php";
        });
      } else {
        Swal.fire({ icon: 'error', title: 'Error', text: res, confirmButtonColor: '#000' });
      }
    })
    .catch(err => console.error(err));
}

function deleteEvent(id) {
  Swal.fire({
    title: 'Are you sure?',
    text: "This event and its student registrations will be deleted permanently!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#000',
    confirmButtonText: 'Yes, delete it!'
  }).then((result) => {
    if (result.isConfirmed) {
      var formData = new FormData();
      formData.append("id", id);

      fetch("delete.php", {
        method: "POST",
        body: formData
      })
        .then(res => res.text())
        .then(data => {
          var response = data.trim();
          if (response === "success") {
            Swal.fire({
              icon: 'success',
              title: 'Deleted!',
              text: 'Event has been deleted.',
              confirmButtonColor: '#000'
            }).then(() => {
              window.location.reload();
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: response,
              confirmButtonColor: '#000'
            });
          }
        })
        .catch(err => {
          console.error(err);
          Swal.fire({ icon: 'error', title: 'Request Failed', text: err.toString() });
        });
    }
  });
}

function addAnnouncementProcess() {
  var form = document.getElementById("addAnnouncementForm");
  if (!form) return;

  var formData = new FormData(form);

  fetch("add.php", {
    method: "POST",
    body: formData
  })
    .then(res => res.text())
    .then(data => {
      if (data.trim() === "success") {
        Swal.fire({
          icon: 'success',
          title: 'Announcement Published!',
          confirmButtonColor: '#000'
        }).then(() => {
          window.location.href = "index.php";
        });
      } else {
        Swal.fire({ icon: 'error', title: 'Error', text: data.trim(), confirmButtonColor: '#000' });
      }
    })
    .catch(err => console.error(err));
}

function editAnnouncementProcess() {
  var form = document.getElementById("editAnnouncementForm");
  if (!form) return;

  var formData = new FormData(form);

  fetch("edit.php", {
    method: "POST",
    body: formData
  })
    .then(res => res.text())
    .then(data => {
      if (data.trim() === "success") {
        Swal.fire({
          icon: 'success',
          title: 'Announcement Updated!',
          confirmButtonColor: '#000'
        }).then(() => {
          window.location.href = "index.php";
        });
      } else {
        Swal.fire({ icon: 'error', title: 'Error', text: data.trim(), confirmButtonColor: '#000' });
      }
    })
    .catch(err => console.error(err));
}

function deleteAnnouncement(id) {
  Swal.fire({
    title: 'Are you sure?',
    text: "This announcement will be permanently deleted!",
    icon: 'warning',
    showCancelButton: true,
    confirmButtonColor: '#d33',
    cancelButtonColor: '#000',
    confirmButtonText: 'Yes, delete it!'
  }).then((result) => {
    if (result.isConfirmed) {
      var formData = new FormData();
      formData.append("id", id);

      fetch("delete.php", {
        method: "POST",
        body: formData
      })
        .then(res => res.text())
        .then(data => {
          if (data.trim() === "success") {
            Swal.fire({
              icon: 'success',
              title: 'Deleted!',
              text: 'Announcement has been deleted.',
              confirmButtonColor: '#000'
            }).then(() => {
              window.location.reload();
            });
          } else {
            Swal.fire({
              icon: 'error',
              title: 'Error',
              text: data.trim(),
              confirmButtonColor: '#000'
            });
          }
        })
        .catch(err => console.error(err));
    }
  });
}