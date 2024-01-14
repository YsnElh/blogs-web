const submitBtn = document.getElementById("register-btn");

function disableSbmtBtn() {
  submitBtn.disabled = true;
  submitBtn.style.backgroundColor = "#004b4b";
}

function enableSbmtBtn() {
  submitBtn.disabled = false;
  submitBtn.style.backgroundColor = "#0CC";
}

let nameValidated = false;
let emailValidated = false;
let profilePicValidated = false;
let passValidated = false;
let cnfmPassValidated = false;

function validateInputs(input, regex, label) {
  input.addEventListener("input", () => {
    let isValid = false;
    if (regex.test(input.value)) {
      input.style.border = "";
      label.style.color = "";
      input.style.outline = "";
      isValid = true;
    } else if (input.value.length === 0) {
      input.style.border = "";
      label.style.color = "";
      input.style.outline = "";
      isValid = true;
    } else {
      input.style.border = "1px solid #ff7373";
      label.style.color = "#ff7373";
      input.style.outline = "solid #ff7373";
    }

    if (input.id === "name") {
      nameValidated = isValid;
    } else if (input.id === "email") {
      emailValidated = isValid;
    } else if (input.id === "password") {
      passValidated = isValid;
    }
    updateSubmitButton();
  });
}

function updateSubmitButton() {
  if (imageInput.files.length === 0) {
    if (nameValidated && emailValidated && passValidated && cnfmPassValidated) {
      enableSbmtBtn();
    } else {
      disableSbmtBtn();
    }
  } else {
    if (
      nameValidated &&
      emailValidated &&
      passValidated &&
      cnfmPassValidated &&
      profilePicValidated
    ) {
      enableSbmtBtn();
    } else {
      disableSbmtBtn();
    }
  }
}
// Name Validate
const nameregex = /^[a-zA-Z\u00C0-\u00FF\s]{1,30}$/;
const nameInput = document.getElementById("name");
const nameLabel = document.getElementById("name-label");
validateInputs(nameInput, nameregex, nameLabel);

// Email Validate
const emailRegex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
const emailInput = document.getElementById("email");
const emailLabel = document.getElementById("email-label");
validateInputs(emailInput, emailRegex, emailLabel);

// username Validate
// let usernameRegex = /^(?=.*[a-z].*[a-z])[a-z0-9._]{5,}$/;
// let usernameInput = document.getElementById("username");
// let usernameLabel = document.getElementById("username-label");
// validateInputs(usernameInput, usernameRegex, usernameLabel);

// Image validate
let imageInput = document.getElementById("profile-pic");
let imageLabel = document.getElementById("profile-pic-label");
let errorMessage = document.getElementById("error-profile-pic-message");

function previewImagePlusValid(event) {
  var input = event.target;
  var preview = document.getElementById("image-preview");
  var delBtn = document.getElementById("del-btn-i");

  if (input.files && input.files[0]) {
    var reader = new FileReader();

    reader.onload = function () {
      preview.src = reader.result;
      preview.style.display = "block";
    };

    reader.readAsDataURL(input.files[0]);
  } else {
    //updateSubmitButton();
    // preview.src = '';
    // preview.style.display = 'none';
    // delBtn.style.display = 'none';
    // errorMessage.innerHTML = "";
    removeImage();
  }
  //---------------------------
  let newImage = new Image();
  let fileImage = imageInput.files[0];
  const maxSize = 3000;

  newImage.onload = function () {
    let width = newImage.width;
    let height = newImage.height;
    let fileSize = fileImage.size / (1024 * 1024);

    if (width !== height) {
      profilePicValidated = false;
      updateSubmitButton();
      errorMessage.innerText = "The width and height must be equal.";
      errorMessage.style.color = "#ff7373";
    } else if (width > maxSize || height > maxSize) {
      profilePicValidated = false;
      updateSubmitButton();
      errorMessage.innerText = "Dimensions should not exceed 3000x3000 pixels.";
      errorMessage.style.color = "#ff7373";
    } else if (fileSize > 2) {
      profilePicValidated = false;
      updateSubmitButton();
      errorMessage.innerText = "File size should be less than 2MB.";
      errorMessage.style.color = "#ff7373";
    } else {
      profilePicValidated = true;
      updateSubmitButton();
      errorMessage.innerText = "";
    }
  };
  newImage.src = URL.createObjectURL(fileImage);
}

function removeImage() {
  updateSubmitButton();
  var preview = document.getElementById("image-preview");
  var inputFile = document.getElementById("profile-pic");

  // Clear the input file and hide the preview
  inputFile.value = "";
  preview.src = "#";
  preview.style.display = "none";
  errorMessage.innerText = "";
}

// Password Validate
const passregex = /^(?=.*[A-Za-z])(?=.*\d).{8,}$/;
const passInput = document.getElementById("password");
const passLabel = document.getElementById("password-label");
validateInputs(passInput, passregex, passLabel);
enabDesabCnfmPass();

function enabDesabCnfmPass() {
  passInput.addEventListener("input", () => {
    if (passValidated && passregex.test(passInput.value)) {
      cnfmPassInput.disabled = false;
    } else if (passInput.value.length === 0) {
      cnfmPassInput.disabled = true;
    } else {
      cnfmPassInput.disabled = true;
    }
  });
}

// Confirm Password Validate
const cnfmPassInput = document.getElementById("cnfm-password");
const cnfmPassLabel = document.getElementById("cnfm-password-label");
cnfmPassInput.disabled = true;
validCnfmPass();

function validCnfmPass() {
  cnfmPassInput.addEventListener("input", () => {
    if (passInput.value === cnfmPassInput.value) {
      cnfmPassInput.style.border = "";
      cnfmPassLabel.style.color = "";
      cnfmPassInput.style.outline = "";
      cnfmPassValidated = true;
    } else if (cnfmPassInput.value.length === 0) {
      cnfmPassInput.style.border = "";
      cnfmPassLabel.style.color = "";
      cnfmPassInput.style.outline = "";
      cnfmPassValidated = true;
    } else {
      cnfmPassInput.style.border = "1px solid #ff7373";
      cnfmPassLabel.style.color = "#ff7373";
      cnfmPassInput.style.outline = "solid #ff7373";
    }
    updateSubmitButton();
  });
}
