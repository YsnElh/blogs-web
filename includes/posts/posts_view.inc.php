<?php

declare(strict_types=1);
require_once './includes/dbh.inc.php';
require_once 'posts_modal.inc.php';
require_once 'posts_contr.inc.php';

$start = 0;
$rows_per_page = 6;

if (isset($_GET['search-value'])) {
   $searchValue = $_GET['search-value'];
} else {
   $searchValue = '';
}

if (isset($_GET['page-nr']) && ($_GET['page-nr'] === '')) {
   $_GET['page-nr'] = 1;
}

if (isset($_GET['page-nr-s']) && ($_GET['page-nr-s'] === '')) {
   $_GET['page-nr-s'] = 1;
}


if (isset($_GET['page-nr'])) {
   $page = $_GET['page-nr'] - 1;
   $start = $page * $rows_per_page;
}

if (isset($_GET['page-nr-s'])) {
   $page = $_GET['page-nr-s'] - 1;
   $start = $page * $rows_per_page;
}

$posts = get_posts($pdo, $start, $rows_per_page, $searchValue);
$nbrPostsSearched = get_nbr_posts_serched($pdo, $searchValue);
$total_posts_nbr = get_nbr_posts($pdo);


if (isset($_GET['page-nr-s'])) {
   $pages = ceil($nbrPostsSearched / $rows_per_page);
} else {
   $pages = ceil($total_posts_nbr / $rows_per_page);
}

function not_found_search()
{
   global $posts;
   global $searchValue;
   if (isset($_GET['page-nr-s']) && empty($posts)) {
      echo '<h2 style="color:red">There is no blogs based on your search: ' . $searchValue . '</h2>';
      return true;
   }
}
function not_found_posts()
{
   global $posts;
   if (count($posts) == 0 && (!isset($_GET['search-value']) || empty($_GET['search-value']))) {
      echo '<h2>There is no blogs yet</h2>';
      return true;
   }
}

function data_search()
{
   global $searchValue;
   echo $searchValue;
}

function print_posts()
{
   global $posts;

   // Check if there are posts or page numbers set
   if (!empty($posts) || isset($_GET['page-nr']) || isset($_GET['page-nr-s'])) {
      foreach ($posts as $post) {
         echo '<article class="col-12 col-md-6 tm-post">';
         echo '<hr class="tm-hr-primary">';
         echo '<a href="singlepost?id=' . $post['id'] . '" class="effect-lily tm-post-link tm-pt-60">';
         echo '<div class="tm-post-link-inner">';
         echo '<img src="./post_imgs/' . $post['post_img'] . '" alt="Image of ' . $post['title'] . '" class="img-fluid">';
         echo '</div>';

         // Check if post is created within the last 3 days
         echo ($post['created_at'] && (new DateTime($post['created_at']))->diff(new DateTime())->days <= 3) ? '<span class="position-absolute tm-new-badge">New</span>' : '';

         echo '<h2 class="tm-pt-30 tm-color-primary tm-post-title">' . $post['title'] . '</h2>';
         echo '</a>';
         echo '<p class="tm-pt-30">' . $post['description'] . '</p>';
         echo '<div class="d-flex justify-content-between tm-pt-45">';
         echo '<span class="tm-color-primary">';

         // Check if category names are set
         if (isset($post['category_names'])) {
            $post['category_names'] = explode(',', $post['category_names']);
            echo implode('. ', array_unique($post['category_names'])) . '. ';
         }

         echo '</span>';

         $created_at = $post['created_at'];
         $date = new DateTime($created_at);
         $formatted_date = $date->format("F j, Y");

         echo '<span class="tm-color-primary">' . $formatted_date . '</span>';
         echo '</div>';
         echo '<hr>';
         echo '<div class="d-flex justify-content-between">';
         echo '<span>' . $post['comments_number'] . ' comments</span>';
         echo '<span>by ' . $post['author_name'] . '</span>   ';
         echo '</div>';
         echo '</article>';
      }
   }
}


function paginate_btns()
{
   global $pages;
   global $searchValue;

   if (empty($searchValue)) {
      $get_link_nr = 'page-nr';
      echo '<div class="tm-prev-next-wrapper">';
      if (isset($_GET[$get_link_nr]) && $_GET[$get_link_nr] > 1) {
         echo '<a href="?' . $get_link_nr . '=' . $_GET[$get_link_nr] - 1 . '" class="mb-2 tm-btn tm-btn-primary tm-prev-next tm-mr-20">Previous</a>';
      } else {
         echo '<a class="mb-2 tm-btn tm-btn-primary tm-prev-next disabled tm-mr-20">Previous</a>';
      }

      if (!isset($_GET[$get_link_nr])) {
         echo '<a href="?' . $get_link_nr . '=2" class="mb-2 tm-btn tm-btn-primary tm-prev-next">Next</a>';
      } else {
         if ($_GET[$get_link_nr] >= $pages) {
            echo '<a class="mb-2 tm-btn tm-btn-primary disabled tm-prev-next">Next</a>';
         } else {
            echo '<a href="?' . $get_link_nr . '=' . $_GET[$get_link_nr] + 1 . '" class="mb-2 tm-btn tm-btn-primary tm-prev-next">Next</a>';
         }
      }
      echo '</div>';

      echo '<div class="tm-paging-wrapper">';
      echo '<span class="d-inline-block mr-3">' . $pages . ' Pages</span>';
      echo '<nav class="tm-paging-nav d-inline-block">';
      echo '<ul>';
      for ($i = 1; $i <= $pages; $i++) {
         if (isset($_GET[$get_link_nr]) && ($_GET[$get_link_nr] == $i)) {
            echo '<li class="tm-paging-item active">';
         } else if (!isset($_GET[$get_link_nr]) && ($i === 1)) {
            echo '<li class="tm-paging-item active">';
         } else {
            echo '<li class="tm-paging-item">';
         }
         echo '<a href="?' . $get_link_nr . '=' . $i . '" class="mb-2 tm-btn tm-paging-link">' . $i . '</a>';
         echo '</li>';
      }
      echo '</ul>';
      echo '</nav>';
      echo '</div>';
   } else {
      $get_link = 'page-nr-s';
      echo '<div class="tm-prev-next-wrapper">';
      if (isset($_GET[$get_link]) && $_GET[$get_link] > 1) {
         echo '<a href="?search-value=' . $searchValue . '&' . $get_link . '=' . $_GET[$get_link] - 1 . '" class="mb-2 tm-btn tm-btn-primary tm-prev-next tm-mr-20">Prev</a>';
      } else {
         echo '<a class="mb-2 tm-btn tm-btn-primary tm-prev-next disabled tm-mr-20">Prev</a>';
      }

      if (!isset($_GET[$get_link])) {
         echo '<a href="?search-value=' . $searchValue . '&' . $get_link . '=2" class="mb-2 tm-btn tm-btn-primary tm-prev-next">Next</a>';
      } else {
         if ($_GET[$get_link] >= $pages) {
            echo '<a class="mb-2 tm-btn tm-btn-primary disabled tm-prev-next">Next</a>';
         } else {
            echo '<a href="?search-value=' . $searchValue . '&' . $get_link . '=' . $_GET[$get_link] + 1 . '" class="mb-2 tm-btn tm-btn-primary tm-prev-next">Next</a>';
         }
      }
      echo '</div>';

      echo '<div class="tm-paging-wrapper">';
      echo '<span class="d-inline-block mr-3">' . $pages . ' Pages</span>';
      echo '<nav class="tm-paging-nav d-inline-block">';
      echo '<ul>';
      for ($i = 1; $i <= $pages; $i++) {
         if (isset($_GET[$get_link]) && ($_GET[$get_link] == $i)) {
            echo '<li class="tm-paging-item active">';
         } else if (!isset($_GET[$get_link]) && ($i === 1)) {
            echo '<li class="tm-paging-item active">';
         } else {
            echo '<li class="tm-paging-item">';
         }
         echo '<a href="?search-value=' . $searchValue . '&' . $get_link . '=' . $i . '" class="mb-2 tm-btn tm-paging-link">' . $i . '</a>';
         echo '</li>';
      }
      echo '</ul>';
      echo '</nav>';
      echo '</div>';
   }
}

// $pdo = null;
// $stmt = null;
// die();