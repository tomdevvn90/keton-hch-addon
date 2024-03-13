<?php 
    get_header(); 
    $id_post = get_the_ID();
    setPostViews($id_post);
?>


<div class="wrapper-single-recipes-video">
    <div class="container">
        <div class="main-video-recipes-play">
            <div class="title">
                <?php 
                    echo get_the_title($id_post);
                ?>
            </div>
            <?php 
                $link_video = get_field('link_video',$id_post);
                if(!empty($link_video)) {
                    if(str_contains($link_video,'youtube') || str_contains($link_video,'vimeo')) {
                        $embed_video = get_video_by_url($link_video);
                        ?>
                            <div class="video-play">
                                <?php 
                                    echo $embed_video;
                                ?>
                            </div>
                        <?php
                    }else{
                        ?>
                            <video class="video-bg-hcp lazy" width="auto" height="auto" autoplay="autoplay" loop="loop" muted defaultMuted playsinline  oncontextmenu="return false;"  preload="auto"  id="myVideo2">
                                <source data-src="<?php echo $link_video?>" type="video/mp4">
                            </video>
                        <?php

                    }
                }
            ?>
        </div>
        <div class="recipes-video-relation" data-post-not-in="<?php echo $id_post?>">
            <?php 
                echo do_shortcode('[be_ajax_pagination post_type="recipe-video" posts_per_page="12" paged="1" post_not_in="'.$id_post.'"]');
            ?>
        </div>
    </div>

    <div class="feature-social-network site-footer">
        <div class="container content-social-network">
            <div class="newsletter-container footer-subscribe">
                <h4 class="title">
                    <?php 
                        echo get_field('newsletter_setting_hch','options')['title'];
                    ?>
                </h4>
                <span class="sub">
                    <?php 
                        echo get_field('newsletter_setting_hch','options')['sub_title'];
                    ?>
                </span>
                <div class="form-wrapper">
                    <?php if(get_theme_mod('bacola_subscribe_form_plugin') == 'mailpoet'){ ?>
                        <?php echo do_shortcode('[mailpoet_form id="'.get_theme_mod('bacola_footer_subscribe_formid').'"]'); ?>
                    <?php } else { ?>
                        <?php echo do_shortcode('[mc4wp_form id="'.get_theme_mod('bacola_footer_subscribe_formid').'"]'); ?>
                    <?php } ?>
                </div>
            </div>
            <div class="social-container">
                <h4 class="title"><?php echo get_field('social_list_setting_hch','options')['title'];?></h4>
                <div class="list-social">
                    <?php 
                        $list_social = get_field('social_list_setting_hch','options')['list_social'];
                        if(!empty($list_social)) {
                            foreach ($list_social as $social) {
                                ?>
                                <a class="item" href="<?php echo $social['link']?>">
                                    <img src="<?php echo $social['icon']?>" />
                                </a>
                                <?php
                            }
                        }
                    ?>
                </div>
            </div>
        </div>
    </div>
    
    <?php 
        $list_chef = get_field('list_chef_recipe',$id_post);
        if(!empty($list_chef)) {
            ?>
            <div class="wrapper-list-chef-recipes">
                <div class="container">
                    <div class="list-chef-recipes">
                        <?php 
                            foreach ($list_chef as $chef) {
                                ?>
                                    <div class="chef">
                                        <img class="image" src="<?php echo $chef['image']?>"/>
                                        <h4 class="name"><?php echo $chef['name']?></h4>
                                        <div class="des">
                                            <?php 
                                                echo $chef['description'];
                                            ?>
                                        </div>
                                    </div>
                                <?php
                            }
                        ?>
                    </div>
                </div>
            </div>   
            <?php
        }
        $description = get_field('description_recipe_video',$id_post);
        $sponsors = get_field('sponsors_recipe_video',$id_post);
        if(!empty($description) || !empty($sponsors)) {
            ?>
                <div class="wrapper-content-single-recipe-video">
                    <div class="container">
                        <div class="content-bottom">
                            <?php 
                                if(!empty($description)) {
                                    ?>
                                        <div class="description">
                                            <h4 class="name">
                                                <?php echo __('O ODDAJI KUHINJA IZZIVOV','hch-addons')?>
                                            </h4>
                                            <div class="description-recipe-video">
                                                <?php 
                                                    
                                                    echo $description;
                                                ?>
                                            </div>
                                        </div>
                                    <?php
                                }

                                if(!empty($sponsors)) {
                                    ?>
                                    <div class="wrapper-sponsors">
                                        <h4 class="name">
                                            <?php echo __('SPONZORJI ODDAJE','hch-addons')?>
                                        </h4>
                                        <div class="list-sponsors">
                                            <?php 
                                                foreach ($sponsors as $sps) {
                                                    ?>
                                                        <img src="<?php echo $sps?>"/>
                                                    <?
                                                }
                                            ?>
                                        </div>
                                    </div>
                                    <?php
                                }
                            ?>
                        </div>
                    </div>
                </div>
            <?php
        }
    ?>


</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
    var lazyVideos = [].slice.call(document.querySelectorAll("video.lazy"));

    if ("IntersectionObserver" in window) {
    var lazyVideoObserver = new IntersectionObserver(function(entries, observer) {
        entries.forEach(function(video) {
        if (video.isIntersecting) {
            for (var source in video.target.children) {
            var videoSource = video.target.children[source];
            if (typeof videoSource.tagName === "string" && videoSource.tagName === "SOURCE") {
                videoSource.src = videoSource.dataset.src;
            }
            }

            video.target.load();
            video.target.classList.remove("lazy");
            lazyVideoObserver.unobserve(video.target);
        }
        });
    });

    lazyVideos.forEach(function(lazyVideo) {
        lazyVideoObserver.observe(lazyVideo);
    });
    }
    });
</script>

<?php get_footer(); ?>