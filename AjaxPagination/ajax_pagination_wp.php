<?php
function ajax_pagination_svl( $atts ){
    $atts = shortcode_atts(
        array(
            'posts_per_page' => 5,
            'paged' => 1,
            'post_type' => 'post',
            'post_not_in'=>'',
        ), $atts,'ajax_pagination'
    );
    $posts_per_page = intval($atts['posts_per_page']);
    $paged = intval($atts['paged']);
    $post_type = sanitize_text_field($atts['post_type']);
    $post_not_in = $atts['post_not_in'];
    $allpost  = '<div id="result_ajaxp">';
    $allpost .= query_ajax_pagination( $post_type, $posts_per_page , $paged, $post_not_in );
    $allpost .= '</div>';

    return $allpost;
}
add_shortcode('ajax_pagination', 'ajax_pagination_svl');

function query_ajax_pagination( $post_type = 'post', $posts_per_page = 5, $paged = 1, $post_not_in = ''){
    
    if(!empty($post_not_in)) {
        $args_svl = array(
            'post_type' => $post_type,
            'posts_per_page' => $posts_per_page,
            'paged' => $paged,
            'post__not_in' =>[intval($post_not_in)],
            'post_status' => 'publish'
        );
    }else{
        $args_svl = array(
            'post_type' => $post_type,
            'posts_per_page' => $posts_per_page,
            'paged' => $paged,
            'post_status' => 'publish'
        );
    }
    
    $q_svl = new WP_Query( $args_svl );
    $total_records = $q_svl->found_posts;
    $total_pages = ceil($total_records/$posts_per_page);

    if($q_svl->have_posts()) {
        ob_start();
        ?>
            <div class="ajax_pagination" posts_per_page="<?php echo $posts_per_page?>" post_type ="<?php echo $post_type?>">
                <div class="list-recipes-videos">
                <?php 
                    while($q_svl->have_posts()):$q_svl->the_post();
                        $id_post_recipes = get_the_ID();
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
                    endwhile;
                ?>
                </div>

                <?php echo paginate_function( $posts_per_page, $paged, $total_records, $total_pages);?>
                <div class="loading_ajaxp">
                    <div id="circularG">
                        <div id="circularG_1" class="circularG"></div>
                        <div id="circularG_2" class="circularG"></div>
                        <div id="circularG_3" class="circularG"></div>
                        <div id="circularG_4" class="circularG"></div>
                        <div id="circularG_5" class="circularG"></div>
                        <div id="circularG_6" class="circularG"></div>
                        <div id="circularG_7" class="circularG"></div>
                        <div id="circularG_8" class="circularG"></div>
                    </div>
                </div>

            </div>
        <?php
        $content = ob_get_clean();
    }
    wp_reset_query();

    return $content;
}

function paginate_function($item_per_page, $current_page, $total_records, $total_pages)
{
    $pagination = '';
    if($total_pages > 0 && $total_pages != 1 && $current_page <= $total_pages){ 
        $pagination .= '<ul class="pagination">';

        $right_links = $current_page + 3;
        $previous = $current_page - 3; //previous link
        $next = $current_page + 1; //next link
        $first_link = true; //boolean var to decide our first link

        if($current_page > 1){
            $previous_link = ($previous<=0)? 1 : $previous;
            $pagination .= '<li class="first"><a href="#" data-page="1" title="First">&laquo;</a></li>'; //first link
            $pagination .= '<li><a href="#" data-page="'.$previous_link.'" title="Previous">&lt;</a></li>'; //previous link
            for($i = ($current_page-2); $i < $current_page; $i++){ //Create left-hand side links
                if($i > 0){
                    $pagination .= '<li><a href="#" data-page="'.$i.'" title="Page'.$i.'">'.$i.'</a></li>';
                }
            }
            $first_link = false; //set first link to false
        }

        if($first_link){ //if current active page is first link
            $pagination .= '<li class="first active">'.$current_page.'</li>';
        }elseif($current_page == $total_pages){ //if it's the last active link
            $pagination .= '<li class="last active">'.$current_page.'</li>';
        }else{ //regular current link
            $pagination .= '<li class="active">'.$current_page.'</li>';
        }

        for($i = $current_page+1; $i < $right_links ; $i++){ //create right-hand side links
            if($i<=$total_pages){
                $pagination .= '<li><a href="#" data-page="'.$i.'" title="Page '.$i.'">'.$i.'</a></li>';
            }
        }
        if($current_page < $total_pages){
            $next_link = ($i > $total_pages)? $total_pages : $i;
            $pagination .= '<li><a href="#" data-page="'.$next_link.'" title="Next">&gt;</a></li>'; //next link
            $pagination .= '<li class="last"><a href="#" data-page="'.$total_pages.'" title="Last">&raquo;</a></li>'; //last link
        }

        $pagination .= '</ul>';
    }
    return $pagination; //return pagination links
}

add_action( 'wp_ajax_LoadPostPagination', 'LoadPostPagination_init' );
add_action( 'wp_ajax_nopriv_LoadPostPagination', 'LoadPostPagination_init' );
function LoadPostPagination_init() {
    $posts_per_page = intval($_POST['posts_per_page']);
    $paged = intval($_POST['data_page']);
    $post_type = sanitize_text_field($_POST['post_type']);
    $post_not_in = intval($_POST['post_not_in']);
    $allpost = query_ajax_pagination( $post_type, $posts_per_page , $paged, $post_not_in );
    echo $allpost;
    exit;
}


