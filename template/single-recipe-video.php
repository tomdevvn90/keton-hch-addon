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
                echo do_shortcode('[be_ajax_pagination post_type="recipe-video" posts_per_page="4" paged="1" post_not_in="'.$id_post.'"]');
            ?>
        </div>

    </div>

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