<?php 
    get_header(); 
    $id_post = get_the_ID();
    //setPostViews($id_post);
?>

<div class="wrapper-single-recipe">
    <div class="single-recipe-detail">
        <div class="container">
            <div class="single-recipe-content">
                <div class="single-recipe">
                    <div class="feature-post">
                        <img class="thumb" src="<?php echo get_the_post_thumbnail_url($id_post,'full')?>"/>
                        <div class="date-cat">
                            <div class="date">
                                <?php 
                                    $date = get_the_date('F j, Y',$id_post);
                                    echo $date;
                                ?>
                            </div>
                            <?php 
                                $terms = get_the_terms( $id_post, 'recipe-cat' );
                                if ( !empty( $terms ) ){ 
                            ?>
                                <div class="cats"><?php echo join(', ', wp_list_pluck($terms, 'name')); ?></div>
                            <?php 
                                } 
                            ?>
                        </div>
                        <h2 class="title">
                            <?php 
                                echo get_the_title($id_post);
                            ?>
                        </h2>
                        <?php 
                            $des_recipe = get_field('description',$id_post);
                            if(!empty($des_recipe)) {
                                ?>
                                    <div class="des-recipe">
                                        <?php 
                                            echo $des_recipe;
                                        ?>
                                    </div>
                                <?php
                            }
                        ?>
                    </div> 
                    <?php 
                        $ingredient = get_field('ingredient',$id_post);
                        $process = get_field('process_recipe',$id_post);
                        $notice = get_field('note_recipe',$id_post);
                        if(!empty($ingredient) || !empty($process)) {
                            ?>
                                <div class="ingredient-process-recipe">
                                    <div class="ingredient">
                                        <h2 class="name-feauter">
                                            <?php echo __('SESTAVINE','hch-addons')?>
                                        </h2>
                                        <div class="content">
                                            <?php 
                                                echo $ingredient;
                                            ?>
                                        </div>
                                    </div>
                                    <div class="process">
                                        <h2 class="name-feauter">
                                            <?php echo __('POSTOPEK','hch-addons')?>
                                        </h2>
                                        <div class="content">
                                            <?php 
                                                echo $process;
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            <?php
                        }
                        if(!empty($notice)) {
                            ?>
                                <div class="note-recipe">
                                    <h2 class="name-feauter">
                                        <?php echo __('MOJE OPOMBE','hch-addons')?>
                                    </h2>
                                    <div class="content">
                                        <?php 
                                            echo $notice;
                                        ?>
                                    </div>
                                </div>
                            <?php
                        }

                    ?>

                </div>
                <div class="side-bar-recipe">
                    <?php 
                        $posts_interesting = get_field('side_bar_recipe','options')['list_interesting_post_sidebar'];
                        $gallery_sidebar = get_field('side_bar_recipe','options')['list_media_recipe_side_bar'];
                        if(!empty($posts_interesting)) {
                            ?>
                                <div class="posts-interesting">
                                    <h2 class="header">
                                        <?php echo __('ZANIMIVE OBJAVE','hch-addons')?>
                                    </h2>
                                    <div class="list-post">
                                    <?php 
                                        foreach ($posts_interesting as $p_in) {
                                            ?>
                                            <div class="item-post">
                                                <a href="<?php echo get_permalink($p_in)?>">
                                                    <img class="thumb-p" src="<?php echo get_the_post_thumbnail_url($p_in,'thumbnail') ?>"/>
                                                    <label class="name">
                                                        <?php 
                                                            echo get_the_title($p_in);
                                                        ?>
                                                    </label>
                                                </a>
                                            </div>
                                            <?php
                                        }
                                    ?>
                                    </div>
                                </div>
                            <?php
                        }
                        if(!empty($gallery_sidebar)) {
                            // echo '<pre>';
                            // print_r($gallery_sidebar);
                            // echo '</pre>';
                            ?>
                                <div class="gallery-sidebar">
                                    <?php 
                                        foreach ($gallery_sidebar as $img_sb) {
                                            if(!empty($img_sb['link'])) {
                                                ?>
                                                <a href="<?php echo $img_sb['link']['url']?>" target="<?php echo $img_sb['link']['target']?>">
                                                    <img src="<?php echo $img_sb['image']?>"/>
                                                </a>
                                                <?php
                                            }else{
                                                ?>
                                                <img src="<?php echo $img_sb['image']?>"/>
                                                <?php
                                            }
                                        }
                                    ?>
                                </div>
                            <?php
                        }
                    ?>    
                </div>       
            </div>
        </div>
    </div>
    <?php 
        $product_related = get_field('related_products',$id_post);
        $recipe_video_related = get_field('related_recipe_video',$id_post);
        $post_related = get_field('related_post_recipe',$id_post);
        if(!empty($product_related)) {
            $args = array(
                'post_type' => 'product',
                'posts_per_page' => -1,
                'post_status'    => 'publish',
                'post__in' =>$product_related
            );
            $q_svl = new WP_Query( $args );

            ?>
            <div class="wrapper-related-product">
                <div class="container">
                    <h2 class="title">
                        <?php 
                            echo __('Za recept potrebujete:','hch-addons');
                        ?>
                    </h2>
                <?php 
                    if($q_svl->have_posts()) {
                        ?>
                         <div class="list-products products column-5 mobile-column-2">
                            <?php 
                                while($q_svl->have_posts()):$q_svl->the_post();
                                    wc_get_template_part( 'content', 'product' );
                                endwhile;
                            ?>
                        </div>
                        <?
                        wp_reset_query();
                    }
                ?>
                </div>
            </div> 
            <?php
        }

        if(!empty($recipe_video_related)|| !empty($post_related)) {
            ?>
            <div class="wrapper-related-bottom">
                <div class="container">
                    <div class="recipe-video-post-detail">            
                        <?php 
                            if(!empty($recipe_video_related)) {
                                ?>
                                <div class="product-related">
                                    <div class="title">
                                        <svg width="53" height="53" viewBox="0 0 53 53" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect width="53" height="53" rx="5" fill="#EA2B0F"/>
                                        <path d="M33.5805 17.6094H22.75V27.6623H33.5805V17.6094ZM31.7336 25.8154H24.5969V19.4563H31.7336V25.8154Z" fill="white"/>
                                        <path d="M37.0419 30.1953H22.7539V32.0423H37.0419V30.1953Z" fill="white"/>
                                        <path d="M37.0419 34.2266H22.7539V36.0735H37.0419V34.2266Z" fill="white"/>
                                        <path d="M39.2019 14H20.6696C19.1274 14 17.8715 15.2559 17.8715 16.7981V37.0278C17.8715 38.1378 16.9683 39.0391 15.8601 39.0391C14.752 39.0391 13.8488 38.1359 13.8488 37.0278V21.7849C13.8488 20.5382 14.8628 19.5261 16.1076 19.5261V17.6791C13.8414 17.6791 12 19.5205 12 21.7849V37.0278C12 39.1555 13.7306 40.886 15.8583 40.886H39.2C40.7422 40.886 41.9982 39.6301 41.9982 38.0879V16.7981C41.9982 15.2559 40.7422 14 39.2 14H39.2019ZM40.153 38.0898C40.153 38.6143 39.7264 39.0409 39.2019 39.0409H19.1514C19.5115 38.4555 19.7184 37.7647 19.7184 37.0296V16.7981C19.7184 16.2736 20.145 15.8469 20.6696 15.8469H39.2019C39.7264 15.8469 40.153 16.2736 40.153 16.7981V38.0898Z" fill="white"/>
                                        </svg>
                                        <h4><?php echo __('Kuhinja izzivov','hch-addons')?></h4>
                                    </div>
                                    <div class="list-related">
                                    <?php 
                                        foreach ($recipe_video_related as $id_rv) {
                                            $id_post_recipes = $id_rv;
                                            $thumbnail = get_the_post_thumbnail_url($id_post_recipes,'full');
                                            $link = get_permalink($id_post_recipes);
                                            $title = get_the_title($id_post_recipes);
                                            $date = get_the_date('F j, Y',$id_post_recipes);
                                            ?>
                                            <div class="item-recipe-video">
                                                <a href="<?php echo $link?>">
                                                    <div class="thumbnail">
                                                        <img src="<?php echo $thumbnail?>"/>
                                                        <div class="icon-play">
                                                            <svg xmlns="http://www.w3.org/2000/svg" width="74" height="74" viewBox="0 0 74 74" fill="none">
                                                                <circle cx="37" cy="37" r="36" stroke="white" stroke-width="2"/>
                                                                <path d="M48.6383 34.1291C50.3396 35.3238 50.3396 37.8448 48.6383 39.0394L33.5235 49.6528C31.5356 51.0487 28.7995 49.6267 28.7995 47.1976L28.7995 25.971C28.7995 23.5419 31.5356 22.1199 33.5235 23.5158L48.6383 34.1291Z" fill="white"/>
                                                            </svg>
                                                        </div>
                                                        <div class="ovelay"></div>
                                                    </div>
                                                    <div class="box-feature">
                                                        <div class="view-post">
                                                            <?php 
                                                                echo getPostViews($id_post_recipes);
                                                            ?>
                                                        </div>
                                                        <div class="title">
                                                            <?php 
                                                                echo $title;
                                                            ?>
                                                        </div>
                                                        <div class="date">
                                                            <?php 
                                                                echo $date;
                                                            ?>
                                                        </div>
                                                    </div>
                                                </a>
                                            </div>
                                            <?php
                                        
                                            }
                                    ?>
                                    </div>
                                </div>
                                <?php
                            }
                            if(!empty($post_related)) {
                                ?>
                                <div class="product-related">
                                    <div class="title">
                                        <svg width="53" height="53" viewBox="0 0 53 53" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <rect width="53" height="53" rx="5" fill="#EA2B0F"/>
                                        <path d="M33.5805 17.6094H22.75V27.6623H33.5805V17.6094ZM31.7336 25.8154H24.5969V19.4563H31.7336V25.8154Z" fill="white"/>
                                        <path d="M37.0419 30.1953H22.7539V32.0423H37.0419V30.1953Z" fill="white"/>
                                        <path d="M37.0419 34.2266H22.7539V36.0735H37.0419V34.2266Z" fill="white"/>
                                        <path d="M39.2019 14H20.6696C19.1274 14 17.8715 15.2559 17.8715 16.7981V37.0278C17.8715 38.1378 16.9683 39.0391 15.8601 39.0391C14.752 39.0391 13.8488 38.1359 13.8488 37.0278V21.7849C13.8488 20.5382 14.8628 19.5261 16.1076 19.5261V17.6791C13.8414 17.6791 12 19.5205 12 21.7849V37.0278C12 39.1555 13.7306 40.886 15.8583 40.886H39.2C40.7422 40.886 41.9982 39.6301 41.9982 38.0879V16.7981C41.9982 15.2559 40.7422 14 39.2 14H39.2019ZM40.153 38.0898C40.153 38.6143 39.7264 39.0409 39.2019 39.0409H19.1514C19.5115 38.4555 19.7184 37.7647 19.7184 37.0296V16.7981C19.7184 16.2736 20.145 15.8469 20.6696 15.8469H39.2019C39.7264 15.8469 40.153 16.2736 40.153 16.7981V38.0898Z" fill="white"/>
                                        </svg>
                                        <h4><?php echo __('Blog','hch-addons')?></h4>
                                    </div>
                                    <div class="list-related">
                                    <?php 
                                        foreach ($post_related as $id_pc) {
                                            $rp_id = $id_pc;
                                            $terms = get_the_terms( $rp_id, 'recipe-cat' );
                                            ?>
                                            <a href="<?php echo get_permalink($rp_id); ?>" class="related-item">
                                                <div class="thumb"><?php echo get_the_post_thumbnail( $rp_id, 'medium' ); ?></div>
                                                <?php 
                                                if ( !empty( $terms ) ){ ?>
                                                    <div class="cats"><?php echo join(', ', wp_list_pluck($terms, 'name')); ?></div>
                                                <?php } ?>
                                                <h3><?php echo get_the_title(); ?></h3>
                                                <div class="crt-date-comment">
                                                    <span class="date">
                                                        <?php echo get_the_date('F j Y',$rp_id); ?>
                                                    </span>
                                                    <span class="count-comment">
                                                        <?php 
                                                            echo __(' , '.get_comment_count($rp_id)['approved'].' komentarji','hch-addons'); 
                                                        ?>
                                                    </span>
                                                </div>
                                            </a>
                                            <?php
                                        
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

<?php get_footer(); ?>