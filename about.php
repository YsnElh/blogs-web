<?php
require_once "./includes/config_session.inc.php";
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Xtra Blog</title>
    <link rel="stylesheet" href="fontawesome/css/all.min.css"> <!-- https://fontawesome.com/ -->
    <link href="https://fonts.googleapis.com/css2?family=Source+Sans+Pro&display=swap" rel="stylesheet">
    <!-- https://fonts.google.com/ -->
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <!--
    
TemplateMo 553 Xtra Blog

https://templatemo.com/tm-553-xtra-blog

-->
</head>

<body>
    <?php
    include('components/header.php');
    ?>
    <div class="container-fluid">
        <main class="tm-main">
            <div class="row tm-row tm-mb-45">
                <div class="col-12">
                    <img src="img/img-about.jpg" alt="Image" class="img-fluid">
                </div>
            </div>
            <div class="row tm-row tm-mb-40">
                <div class="col-12">
                    <div class="mb-4">
                        <h2 class="pt-2 tm-mb-40 tm-color-primary tm-post-title">About this xtra blog</h2>
                        <p>
                            Welcome to our blog, a virtual haven where the essence of mindful living, culinary
                            exploration, and sustainable choices converge. Here, we embark on a transformative journey,
                            delving into the intricacies of conscious living. Our commitment lies in curating content
                            that inspires positive change, fostering a deeper connection with oneself, the world, and
                            the diverse flavors it offers.
                            <br>
                            <br>
                            In the realm of mindful living, we unravel the secrets to harmonizing with the present
                            moment, fostering self-awareness, and nurturing overall well-being. Through our culinary
                            adventures, we traverse the globe, bringing you the richness of international cuisines and
                            the joy of creating diverse, delicious meals in your own kitchen.
                            <br>
                            <br>
                            Sustainability is at the core of our ethos. We explore eco-friendly practices, offering
                            insights into how small choices can make a substantial impact on our planet. Our goal is to
                            guide you towards a lifestyle that is not just fulfilling but also environmentally
                            conscious.
                            <br>
                            <br>
                            Join us as we navigate the digital age with intention, finding the delicate balance between
                            technology and daily life. We share strategies for cultivating a healthier relationship with
                            screens, fostering mindfulness in the digital space.
                            <br>
                            <br>
                            In essence, our blog is a celebration of mindful, flavorful, and sustainable living. It's an
                            invitation to explore, learn, and evolve—a journey towards a more conscious and fulfilling
                            lifestyle. Welcome to a community where the pursuit of a mindful, delicious, and sustainable
                            life is both the path and the destination.
                        </p>
                    </div>
                </div>
            </div>
            <div class="row tm-row tm-mb-120">
                <div class="col-lg-4 tm-about-col">
                    <div class="tm-bg-gray tm-about-pad">
                        <div class="text-center tm-mt-40 tm-mb-60">
                            <i class="fas fa-bezier-curve fa-4x tm-color-primary"></i>
                        </div>
                        <h2 class="mb-3 tm-color-primary tm-post-title">Background</h2>
                        <p class="mb-0 tm-line-height-short">
                            Rooted in a passion for conscious living, our blog emerged from the desire to share
                            transformative insights, fostering a community dedicated to mindfulness, culinary
                            exploration, sustainability, and beyond.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 tm-about-col">
                    <div class="tm-bg-gray tm-about-pad">
                        <div class="text-center tm-mt-40 tm-mb-60">
                            <i class="fas fa-users-cog fa-4x tm-color-primary"></i>
                        </div>
                        <h2 class="mb-3 tm-color-primary tm-post-title">Teamwork</h2>
                        <p class="mb-0 tm-line-height-short">
                            At the heart of our endeavor is a collaborative spirit. Our diverse team, united by a shared
                            commitment to inspire positive change, collaborates seamlessly to curate content that
                            resonates with the essence of mindful living and global exploration.
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 tm-about-col">
                    <div class="tm-bg-gray tm-about-pad">
                        <div class="text-center tm-mt-40 tm-mb-60">
                            <i class="fab fa-creative-commons-sampling fa-4x tm-color-primary"></i>
                        </div>
                        <h2 class="mb-3 tm-color-primary tm-post-title">Our Core Value</h2>
                        <p class="mb-0 tm-line-height-short">
                            Our core value is simple yet profound: to empower individuals on their journey toward a
                            conscious lifestyle. We believe in the transformative power of mindful choices, global
                            flavors, and sustainable practices, guiding our readers to cultivate a life that aligns with
                            their values and aspirations.
                        </p>
                    </div>
                </div>
            </div>
            <!-- <div class="row tm-row tm-mb-60">
                <div class="col-12">
                    <hr class="tm-hr-primary  tm-mb-55">
                </div>
                <div class="col-lg-6 tm-mb-60 tm-person-col">
                    <div class="media tm-person">
                        <img src="img/about-02.jpg" alt="Image" class="img-fluid mr-4">
                        <div class="media-body">
                            <h2 class="tm-color-primary tm-post-title mb-2">John Henry</h2>
                            <h3 class="tm-h3 mb-3">CEO/Founder</h3>
                            <p class="mb-0 tm-line-height-short">
                                Aliquam non vulputate lectus, vel ultricies diam. Suspendisse at ipsum
                                hendrerit, vestibulum mi id, mattis tortor.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 tm-mb-60 tm-person-col">
                    <div class="media tm-person">
                        <img src="img/about-03.jpg" alt="Image" class="img-fluid mr-4">
                        <div class="media-body">
                            <h2 class="tm-color-primary tm-post-title mb-2">Timy Cake</h2>
                            <h3 class="tm-h3 mb-3">Project Director</h3>
                            <p class="mb-0 tm-line-height-short">
                                Quisque in bibendum elit, in egestas turpis. Vestibulum ornare sollicitudin congue.
                                Aliquam lorem mi, maximus at iaculis ut.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 tm-mb-60 tm-person-col">
                    <div class="media tm-person">
                        <img src="img/about-04.jpg" alt="Image" class="img-fluid mr-4">
                        <div class="media-body">
                            <h2 class="tm-color-primary tm-post-title mb-2">Jay Zoona</h2>
                            <h3 class="tm-h3 mb-3">Supervisor</h3>
                            <p class="mb-0 tm-line-height-short">
                                Maecenas eu mi eu dui cursus consequat non eu metus. Morbi ac
                                turpis eleifend, commodo purus eget, commodo mauris.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 tm-mb-60 tm-person-col">
                    <div class="media tm-person">
                        <img src="img/about-05.jpg" alt="Image" class="img-fluid mr-4">
                        <div class="media-body">
                            <h2 class="tm-color-primary tm-post-title mb-2">Catherine Soft</h2>
                            <h3 class="tm-h3 mb-3">Team Leader</h3>
                            <p class="mb-0 tm-line-height-short">
                                Integer eu sapien hendrerit,
                                imperdiet arcu sit amet, sollicitudin ipsum.
                                Phasellus consequat suscipit ligula eget bibendum.
                            </p>
                        </div>
                    </div>
                </div>
            </div> -->
            <?php include_once('components/footer.php') ?>
        </main>
    </div>
    <script src="js/jquery.min.js"></script>
    <script src="js/main.js"></script>
</body>

</html>