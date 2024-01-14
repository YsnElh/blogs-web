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
        postDiv.innerHTML = `<img src="./img/${p.post_img}" width="400px" alt="Image of the post: ${p.title}">
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
