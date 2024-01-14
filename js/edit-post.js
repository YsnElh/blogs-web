document.addEventListener("click", function (event) {
  if (event.target.classList.contains("btn-outline-danger")) {
    const parentDiv = event.target.closest("[data-index]");
    if (parentDiv) {
      event.preventDefault();
      parentDiv.remove();
    }
  }
});
//-------------------------------------------------
let alertDivs = document.querySelectorAll(".alert");
alertDivs.forEach((elm) => {
  setTimeout(function () {
    elm.style.opacity = "0";
  }, 3000);
});

function validateInputs(input, regex, label) {
  input.addEventListener("input", () => {
    if (regex.test(input.value)) {
      input.style.border = "";
      label.style.color = "";
      input.style.outline = "";
    } else if (input.value.length === 0) {
      input.style.border = "";
      label.style.color = "";
      input.style.outline = "";
    } else {
      input.style.border = "1px solid #ff7373";
      label.style.color = "#ff7373";
      input.style.outline = "solid #ff7373";
    }
  });
}
//description
const descriptionInput = document.getElementById("description");
const descriptionLabel = document.getElementById("description-label");
validateInputs(descriptionInput, /^.{20,250}$/, descriptionLabel);

//TITLE
const titleregex = /^.{1,50}$/;
const titleInput = document.getElementById("title");
const titleLabel = document.getElementById("title-label");
validateInputs(titleInput, titleregex, titleLabel);

function previewImage(event) {
  const input = event.target;
  const imagePreview = document.getElementById("image-preview");
  const imgPrevInfos = document.getElementById("img-preview-infos");

  const file = input.files[0];

  // Reset previous error message
  imgPrevInfos.innerHTML = "";

  if (file) {
    const reader = new FileReader();

    reader.onload = function (e) {
      imagePreview.src = e.target.result;
      imagePreview.style.display = "block";

      let newImage = new Image();
      newImage.src = e.target.result;

      newImage.onload = function () {
        let width = newImage.width;
        let height = newImage.height;
        let fileSize = file.size / (1024 * 1024);

        imgPrevInfos.style.display = "block";
        const resolutionSpan = document.createElement("span");
        const sizeSpan = document.createElement("span");

        resolutionSpan.textContent = `Resolution: ${width}x${height} `;
        sizeSpan.textContent = `- Size: ${fileSize.toFixed(2)} MB`;
        imgPrevInfos.appendChild(resolutionSpan);
        imgPrevInfos.appendChild(sizeSpan);
      };
    };

    reader.readAsDataURL(file);
  } else {
    imagePreview.src = "#";
    imagePreview.style.display = "none";
    imgPrevInfos.style.display = "none";
  }
}

function removeImage() {
  const imagePreview = document.getElementById("image-preview");
  const input = document.getElementById("post_img");
  const imgPrevInfos = document.getElementById("img-preview-infos");

  imagePreview.src = "#";
  imagePreview.style.display = "none";
  imgPrevInfos.innerHTML = "";
  imgPrevInfos.style.display = "none";
  input.value = "";
}
//------------------------------------------
async function fetchPosts() {
  try {
    const response = await fetch("./includes/posts/api/categs_ids.inc.php", {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
      },
    });
    if (!response.ok) {
      throw new Error("Network response: " + response.status);
    }
    const data = await response.json();
    return data;
  } catch (error) {
    console.error("Error fetching data:", error);
    return error;
  }
}

async function fillDivCategs(categsRegistered) {
  let categs = await fetchPosts();
  categs.forEach((c) => {
    const option = document.createElement("option");
    option.value = c.id;
    option.text = c.name;
    if (categsRegistered.includes(c.id.toString())) {
      option.selected = true;
    }
    categsChoices.add(option);
  });
}

const categsChoices = document.getElementById("categs-choices");
const uniqueCategories = new Set();

async function addNewCategory(event) {
  event.preventDefault();
  let categs = await fetchPosts();
  const newCategsList = document.getElementById("new-categs-list");
  const numberOfInputs = newCategsList.childElementCount;
  const newCategInput = document.getElementById("new-categ");

  if (numberOfInputs < 3) {
    const newCategValue = newCategInput.value;

    if (newCategValue.length > 1 && newCategValue.length <= 25) {
      let categoryExists = false;

      for (const c of categs) {
        if (c.name.toLowerCase() === newCategValue.toLowerCase()) {
          categoryExists = true;
          break;
        }
      }

      if (!categoryExists) {
        const categoryDiv = document.createElement("div");
        categoryDiv.classList.add("d-flex", "mb-2");

        const textInput = document.createElement("input");
        textInput.type = "text";
        textInput.name = "new_categ_value[]";
        textInput.value = newCategValue.toLowerCase();
        textInput.classList.add("form-control");
        textInput.readOnly = true;
        textInput.required = true;

        const deleteButton = document.createElement("button");
        deleteButton.type = "button";
        deleteButton.classList.add("btn", "btn-outline-danger", "ml-2");
        deleteButton.innerHTML = '<i class="fas fa-trash-alt"></i>';
        deleteButton.onclick = function () {
          categoryDiv.remove();
        };

        categoryDiv.appendChild(textInput);
        categoryDiv.appendChild(deleteButton);
        newCategsList.appendChild(categoryDiv);

        newCategInput.value = "";
      } else {
        newCategInput.value = "";
        alert(newCategValue + " already exists in the select area!");
      }
    } else {
      newCategInput.value = "";
      alert(
        "categorie should not be empty and should be bigger than 1 and less than 25 characters"
      );
    }
  } else {
    newCategInput.value = "";
    alert("Only 3 Categories Allowed");
  }
}
