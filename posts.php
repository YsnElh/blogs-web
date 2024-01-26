<?php
    require_once "./includes/config_session.inc.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts</title>
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="fontawesome/css/all.min.css">
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link href="css/main.css" rel="stylesheet">
    <link href="css/cards_posts.css" rel="stylesheet">
</head>

<body>
    <?php
        include('components/header.php');
    ?>
    <div>
        <main class="tm-main">
            <h1 id="error"></h1>
            <div class="mb-3">
                <div>
                    <label for="sort-by">Sort By:</label>
                    <select class="form-control" name="sort-by" id="sort-by">
                        <option value="1">Last Created</option>
                        <option value="2">First Created</option>
                        <option value="3">Title</option>
                    </select>
                </div>
                <div>
                    <label for="categorySelect">Categories names:</label>
                    <select class="form-control" name="categorySelect" id="categorySelect">
                    </select>
                </div>
                <div>
                    <label for="author-name">Authors names:</label>
                    <select class="form-control" name="author-name" id="author-name">
                    </select>
                </div>
                <div id="print-json"></div>

            </div>
            <div id="all-posts"></div>
            <button class="btn btn-outline-info" id="load-more-button">Load More</button>
            <div id="loading" style="display: none; text-align: center;">
                <img src="./img/loading.gif" width="200px" alt="">
            </div>
            <div id="not-found"></div>
        </main>
    </div>

    <script src="js/jquery.min.js"></script>
    <script src="js/main.js"></script>
    <script>
    async function fetchPosts() {
        try {
            showLoadingMessage();
            const response = await fetch("./includes/posts/api/all_posts.inc.php", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
            });
            if (!response.ok) {
                throw new Error("Network response: " + response.status);
            }
            hideLoadingMessage();
            const data = await response.json();
            return data;
        } catch (error) {
            hideLoadingMessage();
            console.error("Error fetching data:", error);
            return error;
        }
    }

    let allPostsDiv = document.getElementById("all-posts");
    let errorMsg = document.getElementById("error");
    let loadMoreButton = document.getElementById("load-more-button");
    let loadingMsg = document.getElementById("loading");
    let msgNotFound = document.getElementById("not-found");
    let nbrPostPerSec = 15;
    let displayedPosts = 0;
    let startIndex = 0;
    //-------------
    let filterForm = document.getElementById("filter-form");
    let sortBySelect = document.getElementById("sort-by");
    let categorySelect = document.getElementById("categorySelect");
    categorySelect.innerHTML = '<option value="all">All</option>';
    let authorsnameSelect = document.getElementById("author-name");
    authorsnameSelect.innerHTML = '<option value="all">All</option>';
    const uniqueCategories = new Set();
    const uniqueAuthors = new Set();

    async function fillSelects() {
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const categ = urlParams.get("categ");

        let posts = await fetchPosts();
        posts.forEach((post) => {
            let categories = post.category_names
                .split(",")
                .map((category) => category.trim());
            categories.forEach((category) => uniqueCategories.add(category));
        });
        const categoriesArray = [...uniqueCategories];
        categoriesArray.forEach((category) => {
            const option = document.createElement("option");
            option.value = category;
            option.textContent = category;
            categorySelect.appendChild(option);
            if (categ === category) {
                option.selected = true;
            }
        });
        posts.forEach((post) => {
            uniqueAuthors.add(post.author_name);
        });
        const authorsArray = [...uniqueAuthors];
        authorsArray.forEach((authorname) => {
            const option = document.createElement("option");
            option.value = authorname;
            option.textContent = authorname;
            authorsnameSelect.appendChild(option);
        });
    }
    fillSelects();
    // Event listeners for form elements
    sortBySelect.addEventListener("change", () => handleEventChange(loadMorePosts));
    categorySelect.addEventListener("change", () =>
        handleEventChange(loadMorePosts)
    );
    authorsnameSelect.addEventListener("change", () =>
        handleEventChange(loadMorePosts)
    );

    // Wrapper function to handle the event change
    function handleEventChange(asyncFunction) {
        // Use a wrapper function to call the async function and handle any errors
        async function wrapper() {
            try {
                await asyncFunction();
            } catch (error) {
                console.error("Error in async function:", error);
            }
        }

        // Call the wrapper function
        wrapper();
    }

    function filterPosts(posts) {
        // Sort By Handle
        allPostsDiv.innerHTML = "";
        let sortBy = sortBySelect.value;

        // Category Handle
        let category = "";
        const queryString = window.location.search;
        const urlParams = new URLSearchParams(queryString);
        const categ = urlParams.get("categ");
        if (categ && category === "") {
            category = categ;
        } else {
            category = categorySelect.value;
        }

        // Authors Handle
        let authorName = authorsnameSelect.value;

        if (sortBy == "1" && category === "all" && authorName === "all") {
            return posts;
        } else {
            let filteredPosts = posts.filter((post) => {
                const categories = post.category_names
                    .split(",")
                    .map((category) => category.trim().toLowerCase());
                const lowercaseAuthor = post.author_name.toLowerCase();

                const categoryMatch =
                    category === "all" || categories.includes(category.toLowerCase());
                const authorMatch =
                    authorName === "all" ||
                    lowercaseAuthor.includes(authorName.toLowerCase());

                return categoryMatch && authorMatch;
            });

            if (sortBy == "2") {
                filteredPosts.reverse();
            } else if (sortBy == "3") {
                filteredPosts.sort((a, b) => a.title.localeCompare(b.title));
            }
            return filteredPosts;
        }
    }

    function showLoadingMessage() {
        if (loadingMsg) {
            loadingMsg.style.display = "block";
        }
    }

    function hideLoadingMessage() {
        if (loadingMsg) {
            loadingMsg.style.display = "none";
        }
    }

    async function loadMorePosts() {
        let data = await fetchPosts();
        let cpt = 0;
        let posts = filterPosts(data);
        if (posts.length > 0) {
            msgNotFound.innerHTML = "";
            posts.map((p) => {
                cpt += 1;
                if (cpt <= nbrPostPerSec) {
                    let postDiv = document.createElement("div");
                    postDiv.classList.add("card");
                    postDiv.innerHTML = `<img src="./post_imgs/${p.post_img}" width="400px" alt="Image of the post: ${p.title}">
            <div class="card__content">
            <a href="/singlepost?id=${p.id}">
            <p class="card__title">${p.title}</p>
            </a>
            <p class="card__description">${p.description}</p>
            <p class="card__description">Categories: ${p.category_names}</p>
            </div>
            </div>`;
                    allPostsDiv.appendChild(postDiv);
                    displayedPosts += 1;
                }
            });

            if (displayedPosts === posts.length) {
                if (loadMoreButton) {
                    loadMoreButton.style.display = "none";
                }
            } else {
                if (loadMoreButton) {
                    loadMoreButton.style.display = "block";
                }
            }
            if (nbrPostPerSec < data.length) {
                nbrPostPerSec += 15;
            }
            displayedPosts = 0;
        } else {
            msgNotFound.innerHTML =
                '<h2>There is no posts match your saerch!</h2><img src="./img/not-found2.gif" alt="">';
        }
    }

    document.addEventListener("DOMContentLoaded", () => {
        loadMorePosts();

        if (loadMoreButton) {
            loadMoreButton.addEventListener("click", () =>
                handleEventChange(loadMorePosts)
            );
        }
    });

    categorySelect.addEventListener("change", () => {
        history.replaceState({}, document.title, window.location.pathname);
    });
    </script>

</body>

</html>