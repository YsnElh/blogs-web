<?php
    require_once "./includes/dbh.inc.php";
    require_once "./includes/config_session.inc.php";
    require_once "./includes/posts/createpost_view.inc.php";
    
    if (!isset($_SESSION['user_id'])) {
        header("Location: /login?error-createpost");
        die();
    }
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap" rel="stylesheet">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <link href="css/account.css" rel="stylesheet">

    <link href="css/register.css" rel="stylesheet">
    <style>
    #img-preview-infos {
        position: absolute;
        bottom: -16px;
        display: none;
        font-size: 15px;
    }

    .input-i {
        align-items: baseline;
    }
    </style>
</head>

<body>
    <?php
        include('components/header.php');
    ?>
    <div class="container-fluid">
        <main class="tm-main main-register">
            <?php check_register_errors() ?>
            <h1 class="tm-color-primary">CREATE NEW POST</h1>
            <div class="contanair">
                <form action="./includes/posts/posts.inc.php" method="post" enctype="multipart/form-data"
                    class="mb-5 ml-auto mr-0 login-form">
                    <?php registeredInputs() ?>
                    <div class="form-group row mb-4">
                        <label for="post_img" id="post-img-label" class="col-sm-3 col-form-label tm-color-primary">Post
                            Image*</label>
                        <div class="col-sm-9">
                            <div class="input-i">
                                <input class="form-control mr-0 ml-auto" name="post-img" id="post_img" type="file"
                                    accept=".jpg, .jpeg, .png" required onchange="previewImage(event)">
                                <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top" title="
                                    •The image is required.
                                    •The image must be (jpg or jpeg or png).
                                    •The Width must be double the height => width=2height.
                                    •The maximum allowed dimensions are 5000x2500 pixels.
                                    •The file size must not exceed 3 megabytes (3MB).
                                    "></i>
                            </div>
                            <?php returnImageErr() ?>
                            <div class="image-container">
                                <img src="#" id="image-preview" alt="Preview" class="img-thumbnail"
                                    style="max-width: 100%; max-height: 150px; margin-top: 10px;display: none;">
                                <i onclick="removeImage()" class="fas fa-trash-alt del-btn" id="del-btn-i"></i>
                            </div>
                            <span id="img-preview-infos" style="font-size: 15px;display:none"></span>
                        </div>
                    </div>
                    <div class="form-group row mb-4" id="categs-checkboxs">
                        <label class="col-sm-3 col-form-label tm-color-primary" for="categs">Choose
                            Categories*</label>
                        <div class="col-sm-9">
                            <div class="input-i">
                                <select class="form-control" name="categs[]" id="categs-choices" multiple></select>
                                <i class="fas fa-info-circle" data-toggle="tooltip" data-placement="top"
                                    title="Max categories allowed for a post is 4, if you choose more we will assigned the new categories you add, and the rest will be randomly picked from what you choose here"></i>
                            </div>
                            <span style="font-size: 15px;">Use (ctrl + left click mouse) to select multiple
                                categories</span><br>
                            <label for="new-categ" class="tm-color-primary" for="categs">Other
                                Categorie</label>
                            <div class="d-flex">
                                <input class="form-control" placeholder="Add new categories, only 3 allowed" type="text"
                                    name="new-categ" id="new-categ">
                                <button class="btn btn-outline-info ml-2" onclick="addNewCategory(event)">ADD</button>
                            </div>
                            <?php registred_categs() ?>


                        </div>
                    </div>
                    <button class="tm-btn tm-btn-primary tm-btn-small" id="register-btn" type="submit">Submit</button>
                </form>
            </div>
        </main>
    </div>
    <script src="js/jquery.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.10.2/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
    <script src="js/main.js"></script>
    <script>
    $(function() {
        $('[data-toggle="tooltip"]').tooltip()
    })
    </script>
    <script>
    document.addEventListener('click', function(event) {
        if (event.target.classList.contains('btn-outline-danger')) {
            const parentDiv = event.target.closest('[data-index]');
            if (parentDiv) {
                event.preventDefault();
                parentDiv.remove();
            }
        }
    });
    </script>

    </script>
    <script>
    let alertDivs = document.querySelectorAll('.alert');
    alertDivs.forEach((elm) => {
        setTimeout(function() {
            elm.style.opacity = '0';
        }, 3000);
    })

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

            reader.onload = function(e) {
                imagePreview.src = e.target.result;
                imagePreview.style.display = "block";

                let newImage = new Image();
                newImage.src = e.target.result;

                newImage.onload = function() {
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
    </script>

    <script>
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

    const categsChoices = document.getElementById("categs-choices");
    const uniqueCategories = new Set();
    async function fillDivCatefs() {
        let categs = await fetchPosts();
        let categsRegistered = <?php returnRegCategs1() ?>;

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

    document.addEventListener("DOMContentLoaded", () => {
        fillDivCatefs();
    });

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
                        break; // Exit the loop early
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
                    deleteButton.onclick = function() {
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
                alert("categorie should not be empty and should not pass 25 characters");
            }
        } else {
            newCategInput.value = "";
            alert("Only 3 Categories Allowed");
        }
    }
    </script>


</body>

</html>
<?php unsetDataSess() ?>
<!--const numberOfInputs = hiddenInputsDiv.childElementCount; if (numberOfInputs < 3) { -->