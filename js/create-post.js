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

const textRegex = /^.{800,20000}$/;
const textInput = document.getElementById("text");
const textLabel = document.getElementById("text-label");
validateInputs(textInput, textRegex, textLabel);

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
