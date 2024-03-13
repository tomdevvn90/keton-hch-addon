<?php 
 function be_get_taxonomies($taxonomy) {
    $options = array();
    $args = array(
           'taxonomy'     => $taxonomy,
           'orderby'      => 'name',
           'hide_empty'   => false
    );
    $all_categories = get_categories( $args );
    if(!empty($all_categories) && !is_wp_error($all_categories)) {
        foreach ($all_categories as $cat) {
            $options[$cat->term_id] = $cat->name;
        }
    }
    return $options;
}



function be_force_template_recipe_cat( $template ) {
    // echo '<pre>';
    // print_r(get_queried_object());
    // echo '</pre>';
    if(!empty(get_queried_object())) {
        if(get_queried_object()->name == 'recipe') {
            $template = B_HELPERS_DIR .'/template/archive-recipe.php';
        }
        if(get_queried_object()->name == 'recipe-video') {
            $template = B_HELPERS_DIR .'/template/archive-recipe-video.php';
        }
    }

    if( is_tax('recipe-cat')) {
        $template = B_HELPERS_DIR .'/template/archive-recipe.php';
    }

    if(is_singular('recipe-video')) {
        $template = B_HELPERS_DIR .'/template/single-recipe-video.php';
    }

    if(is_singular('recipe')) {
        $template = B_HELPERS_DIR .'/template/single-recipe.php';
    }

    return $template;
}



function get_video_by_url($url, $params = null)
{
    if (!is_string($url)) return false;

    $regexVM = '~
      # Match Vimeo link and embed code
      (?:<iframe [^>]*src=")?         # If iframe match up to first quote of src
      (?:                             # Group vimeo url
        https?:\/\/             # Either http or https
        (?:[\w]+\.)*            # Optional subdomains
        vimeo\.com              # Match vimeo.com
        (?:[\/\w]*\/videos?)?   # Optional video sub directory this handles groups links also
        \/                      # Slash before Id
        ([0-9]+)                # $1: VIDEO_ID is numeric
        [^\s]*                  # Not a space
      )                               # End group
      "?                              # Match end quote if part of src
      (?:[^>]*></iframe>)?            # Match the end of the iframe
      (?:<p>.*</p>)?                  # Match any title information stuff
      ~ix';
    $regExpYt = '~
    ^.*((youtu.be\/)|(v\/)|(\/u\/\w\/)|(embed\/)|(watch\?))\??v?=?([^#\&\?]*).*
    ~ix';
  
    preg_match($regexVM, $url, $matches);
    if (isset($matches[1]) && is_array($params) && isset($params['img']) && $params['img']) {
      return '<a href="' . $url . '"' . (isset($params['class']) ? ' class="popup-vimeo ' . $params['class'] . '"' : '') . '><img src="" data-vmid="' . $matches[1] . '" alt=""></a>';
    } else if (isset($matches[1])) {
      return '<iframe class="video video-vm" src="https://player.vimeo.com/video/' . $matches[1] . '?autoplay=1&loop=1&title=0&byline=0&portrait=0&muted=1" width="auto" height="auto" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>';
    }
    preg_match($regExpYt, $url, $matches);
    if (isset($matches[7]) && is_array($params) && isset($params['img']) && $params['img']) {
          return '<a href="' . $url . '"' . (isset($params['class']) ? ' class="popup-youtube ' . $params['class'] . '"' : '') . '><img src="http://img.youtube.com/vi/' . $matches[7] . '/hqdefault.jpg" alt=""></a>';
    } else if (isset($matches[7])) {
      return '<iframe class="video video-ytb" width="auto" height="auto" src="https://www.youtube.com/embed/' . $matches[7] . '?autoplay=1&mute=1" allowfullscreen></iframe>';
    }
    return false;

}



function setPostViews($postID) {

    $user_ip = $_SERVER['REMOTE_ADDR']; //retrieve the current IP address of the visitor
    $key = $user_ip . 'x' . $postID; //combine post ID & IP to form unique key
    $value = array($user_ip, $postID); // store post ID & IP as separate values (see note)
    $visited = get_transient($key); //get transient and store in variable


    //check to see if the Post ID/IP ($key) address is currently stored as a transient
    if ( false === ( $visited ) ) {

        //store the unique key, Post ID & IP address for 12 hours if it does not exist
        set_transient( $key, $value, 60*60*12 );

        // now run post views function
        $count_key = 'views';
        $count = get_post_meta($postID, $count_key, true);
        if($count==''){
            $count = 0;
            delete_post_meta($postID, $count_key);
            add_post_meta($postID, $count_key, '0');
        }else{
            $count++;
            update_post_meta($postID, $count_key, $count);
        }


    }

}

function getPostViews($postID){
    $count_key = 'views';
    $count = get_post_meta($postID, $count_key, true);
    if($count==''){
        delete_post_meta($postID, $count_key);
        add_post_meta($postID, $count_key, '0');
        return __('0 oddaja','hch-addons');
    }
    return $count.__('. oddaja','hch-addons');
}


function be_force_step_filter_price_widget() {
    return 1;
}

function be_force_min_amout_price_widget() {
    return 0;
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
            $pagination .= '<li><a href="#" data-page="'.$previous_link.'" title="Previous">←</a></li>'; //previous link
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
            $pagination .= '<li><a href="#" data-page="'.$next_link.'" title="Next">→</a></li>'; //next link
            $pagination .= '<li class="last"><a href="#" data-page="'.$total_pages.'" title="Last">&raquo;</a></li>'; //last link
        }

        $pagination .= '</ul>';
    }
    return $pagination; //return pagination links
}

function be_query_ajax_pagination( $post_type = 'post', $posts_per_page = 5, $paged = 1, $post_not_in = ''){
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

function cmp($a, $b) {
    if ($a == $b) {
        return 0;
    }
    return ($a > $b) ? -1 : 1;
}

function be_add_attribute_slug_callback( $term_html, $count, $term) {
    // echo '<pre>';
    // print_r($term);
    // echo '</pre>';
    $term_html = '<span class="count" data-taxonomy="'.$term->taxonomy.'" data-filter-attribute="'.$term->slug.'">(' . absint( $count ) . ')</span>';
    return  $term_html;
}


function get_highest($arr,$slug) {
    $max = $arr[0]; // set the highest object to the first one in the array
    foreach($arr as $obj) { // loop through every object in the array
        $num = $obj[$slug]; // get the number from the current object
        if($num > $max[$slug]) { // If the number of the current object is greater than the maxs number:
            $max = $obj; // set the max to the current object
        }
    }
    return $max; // Loop is complete, so we have found our max and can return the max object
}

function be_query_ajax_product_shop( $post_type = 'post', $posts_per_page = 12, $paged = 1, $categories = '', $range_price = '', $tax = '', $slug_attr = '', $status_product= '', $ingredient=''){
    
    if(!empty($categories)) {
        $args_svl = array(
            'post_type' => $post_type,
            'posts_per_page' => $posts_per_page,
            'paged' => $paged,
            'post_status' => 'publish',
            'tax_query' => array(
                array (
                    'taxonomy' => 'product_cat',
                    'field' => 'id',
                    'terms' => $categories,
                )
            ),
        );
        
        $q_svl = new WP_Query( $args_svl );
        $total_records = $q_svl->found_posts;
        $total_pages = ceil($total_records/$posts_per_page);
        if($q_svl->have_posts()) {
            ob_start();
            ?>
                <div class="ajax_pagination wrapper-product-resulter-shop" posts_per_page="<?php echo $posts_per_page?>" post_type ="<?php echo $post_type?>" id_cat = "<?php echo $categories?>">
                    <div class="list-products products column-4 mobile-column-2">
                        <?php 
                            while($q_svl->have_posts()):$q_svl->the_post();
								wc_get_template_part( 'content', 'product' );
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

    if(!empty($ingredient)) {
        $args_svl = array(
            'post_type' => $post_type,
            'posts_per_page' => $posts_per_page,
            'paged' => $paged,
            'post_status' => 'publish',
            'tax_query' => array(
                array (
                    'taxonomy' => 'product-ingredient',
                    'field' => 'id',
                    'terms' => $ingredient,
                )
            ),
        );
        
        $q_svl = new WP_Query( $args_svl );
        $total_records = $q_svl->found_posts;
        $total_pages = ceil($total_records/$posts_per_page);
        if($q_svl->have_posts()) {
            ob_start();
            ?>
                <div class="ajax_pagination wrapper-product-resulter-shop" posts_per_page="<?php echo $posts_per_page?>" post_type ="<?php echo $post_type?>" id_cat = "<?php echo $categories?>" id_ingre = "<?php echo $ingredient?>">
                    <div class="list-products products column-4 mobile-column-2">
                        <?php 
                            while($q_svl->have_posts()):$q_svl->the_post();
								wc_get_template_part( 'content', 'product' );
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

    if(!empty($range_price)) {
        $price = explode('-',$range_price);
        $args_svl = array(
            'post_type' => $post_type,
            'posts_per_page' => $posts_per_page,
            'paged' => $paged,
            'post_status' => 'publish',
            'meta_query' => array(
                array(
                    'key' => '_price',
                    'value' => $price,
                    'compare' => 'BETWEEN',
                    'type' => 'NUMERIC'
                    ),
                ),
        );
        
        $q_svl = new WP_Query( $args_svl );
        $total_records = $q_svl->found_posts;
        $total_pages = ceil($total_records/$posts_per_page);
        if($q_svl->have_posts()) {
            ob_start();
            ?>
                <div class="ajax_pagination wrapper-product-resulter-shop" posts_per_page="<?php echo $posts_per_page?>" post_type ="<?php echo $post_type?>" id_cat = "<?php echo $categories?>" price_range = "<?php echo $range_price?>">
                    <div class="list-products products column-4 mobile-column-2">
                        <?php 
                            while($q_svl->have_posts()):$q_svl->the_post();
								wc_get_template_part( 'content', 'product' );
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
    if(!empty($tax) && !empty($slug_attr)) {
        $args_svl = array(
            'post_type' => $post_type,
            'posts_per_page' => $posts_per_page,
            'paged' => $paged,
            'post_status' => 'publish',
            'tax_query' => array(
                array (
                    'taxonomy' => $tax,
                    'field' => 'slug',
                    'terms' => $slug_attr,
                )
            ),
        );
        $q_svl = new WP_Query( $args_svl );
        $total_records = $q_svl->found_posts;
        $total_pages = ceil($total_records/$posts_per_page);
        if($q_svl->have_posts()) {
            ob_start();
            ?>
                <div class="ajax_pagination wrapper-product-resulter-shop" posts_per_page="<?php echo $posts_per_page?>" post_type ="<?php echo $post_type?>" id_cat = "<?php echo $categories?>" tax ="<?php echo $tax?>" slug_attr = "<?php echo $slug_attr?>">
                    <div class="list-products products column-4 mobile-column-2">
                        <?php 
                            while($q_svl->have_posts()):$q_svl->the_post();
								wc_get_template_part( 'content', 'product' );
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
    if(!empty($status_product)) {
        if($status_product=='instock') {
            $args_svl = array(
                'post_type' => $post_type,
                'posts_per_page' => $posts_per_page,
                'paged' => $paged,
                'post_status' => 'publish',
                'meta_query' => array(
                    array(
                        'key' => '_stock_status',
                        'value' => 'instock'
                    ),
                    array(
                        'key' => '_backorders',
                        'value' => 'no'
                    ),
                ),
            );
            $q_svl = new WP_Query( $args_svl );
            $total_records = $q_svl->found_posts;
            $total_pages = ceil($total_records/$posts_per_page);
            if($q_svl->have_posts()) {
                ob_start();
                ?>
                    <div class="ajax_pagination wrapper-product-resulter-shop" posts_per_page="<?php echo $posts_per_page?>" post_type ="<?php echo $post_type?>" id_cat = "<?php echo $categories?>" price_range = "<?php echo $range_price?>" status_product="<?php echo $status_product?>">
                        <div class="list-products products column-4 mobile-column-2">
                            <?php 
                                while($q_svl->have_posts()):$q_svl->the_post();
                                    wc_get_template_part( 'content', 'product' );
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
        if($status_product=='onsale') {
            $args_svl = array(
                'post_type' => $post_type,
                'posts_per_page' => $posts_per_page,
                'paged' => $paged,
                'post_status' => 'publish',
                'meta_query'     => array(
                    'relation' => 'OR',
                    array( // Simple products type
                        'key'           => '_sale_price',
                        'value'         => 0,
                        'compare'       => '>',
                        'type'          => 'numeric'
                    ),
                    array( // Variable products type
                        'key'           => '_min_variation_sale_price',
                        'value'         => 0,
                        'compare'       => '>',
                        'type'          => 'numeric'
                    )
                )
            );
            $q_svl = new WP_Query( $args_svl );
            $total_records = $q_svl->found_posts;
            $total_pages = ceil($total_records/$posts_per_page);
            if($q_svl->have_posts()) {
                ob_start();
                ?>
                    <div class="ajax_pagination wrapper-product-resulter-shop" posts_per_page="<?php echo $posts_per_page?>" post_type ="<?php echo $post_type?>" id_cat = "<?php echo $categories?>" price_range = "<?php echo $range_price?>" status_product="<?php echo $status_product?>">
                        <div class="list-products products column-4 mobile-column-2">
                            <?php 
                                while($q_svl->have_posts()):$q_svl->the_post();
                                    wc_get_template_part( 'content', 'product' );
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
    }
}

//Show ingredient product
function ingredient_single_product_callback($description) {
    global $product;
    if(!empty($product)){    
        $id = $product->get_id();
        $term_list = get_the_terms($id, 'product-ingredient');
        if(!empty($term_list)) {
            ?>
            <div class="wrapper-ingredient">
                <h4 class="title">
                    <?php __('Kategorija:','hch-addons') ?>
                </h4>
                <div class="list-ingredient">
                    <?php 
                        foreach ($term_list as $key => $value) {
                            $url_image = get_field('image_ingre_tax', 'product-ingredient_'.$value->term_id);
                            ?>
                                <a class="item-ingredient" href="<?php echo get_term_link($value->term_id)?>">
                                    <img src="<?php echo $url_image?>" />
                                    <label><?php echo $value->name ?></label>
                                </a>
                            <?
                        }
                    ?>
                </div>
            </div>
            <?php
        }
    }



    if(!empty($description)) {
        ?>
        <div class="woocommerce-product-details__short-description">
            <p><?php echo $description; // WPCS: XSS ok. ?></p>
        </div>
        <?php
    }
}

// Show product filter popular
function be_show_result_filter_popular() {
    echo do_shortcode('[be_product_result_shop]');
}

function bacola_get_ingredient_url($termid){
	global $wp;
	if ( '' === get_option( 'permalink_structure' ) ) {
		$link = remove_query_arg( array( 'page', 'paged' ), add_query_arg( $wp->query_string, '', home_url( $wp->request ) ) );
	} else {
		$link = preg_replace( '%\/page/[0-9]+%', '', add_query_arg( null, null ) );
	}

	if(isset($_GET['filter_ingredient'])){
		$explode_old = explode(',',$_GET['filter_ingredient']);
		$explode_termid = explode(',',$termid);
		
		if(in_array($termid, $explode_old)){
			$data = array_diff( $explode_old, $explode_termid);
			$checkbox = 'checked';
		} else {
			$data = array_merge($explode_termid , $explode_old);
		}
	} else {
		$data = array($termid);
	}
	
	$dataimplode = implode(',',$data);
	
	if(empty($dataimplode)){
		$link = remove_query_arg('filter_ingredient',$link);
	} else {
		$link = add_query_arg('filter_ingredient',implode(',',$data),$link);
	}
	
	return $link;
}

function hch_woocommerce_product_query_tax_query( $tax_query, $instance ) {
	if(isset($_GET['filter_ingredient'])){
		if(!empty($_GET['filter_ingredient'])){
			$tax_query[] = array(
				'taxonomy' => 'product-ingredient',
				'field' 	=> 'id',
				'terms' 	=> explode(',',$_GET['filter_ingredient']),
			);
		}
	}
    return $tax_query; 
}; 


function hch_remove_klb_filter(){
	
	$output = '';

	$_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes();
    $min_price = isset( $_GET['min_price'] ) ? wc_clean( $_GET['min_price'] ) : 0; 
    $max_price = isset( $_GET['max_price'] ) ? wc_clean( $_GET['max_price'] ) : 0; 

	if(! empty( $_chosen_attributes ) || isset($_GET['filter_cat']) || isset($_GET['filter_ingredient']) || 0 < $min_price || 0 < $max_price || bacola_stock_status() == 'instock' || bacola_on_sale() == 'onsale'){

		global $wp;
	
		if ( '' === get_option( 'permalink_structure' ) ) {
			$baselink = remove_query_arg( array( 'page', 'paged' ), add_query_arg( $wp->query_string, '', home_url( $wp->request ) ) );
		} else {
			$baselink = preg_replace( '%\/page/[0-9]+%', '',  add_query_arg( null, null )  );
		}

		$output .= '<ul class="remove-filter">';
		
		$output .= '<li><a href="'.esc_url(remove_query_arg(array_keys($_GET))).'" class="remove-filter-element clear-all">'.esc_html__( 'Clear filters', 'bacola-core' ).'</a></li>';

		if ( ! empty( $_chosen_attributes ) ) {
			foreach ( $_chosen_attributes as $taxonomy => $data ) {
				foreach ( $data['terms'] as $term_slug ) {
					$term = get_term_by( 'slug', $term_slug, $taxonomy );
					
					$filter_name    = 'filter_' . wc_attribute_taxonomy_slug( $taxonomy );
					$explode_old = explode(',',$_GET[$filter_name]);
					$explode_termid = explode(',',$term->slug);
					$klbdata = array_diff( $explode_old, $explode_termid);
					$klbdataimplode = implode(',',$klbdata);
					
					if(empty($klbdataimplode)){
						$link = remove_query_arg($filter_name);
					} else {
						$link = add_query_arg($filter_name,implode(',',$klbdata),$baselink );
					}

					$output .= '<li><a href="'.esc_url($link).'" class="remove-filter-element attributes">'.esc_html($term->name).'</a></li>';

				}
			}
		}

		if(bacola_stock_status() == 'instock'){
		$output .= '<li><a href="'.esc_url(remove_query_arg('stock_status')).'" class="remove-filter-element stock_status">'.esc_html__('In Stock','bacola-core').'</a></li>';
		}
		
		if(bacola_on_sale() == 'onsale'){
		$output .= '<li><a href="'.esc_url(remove_query_arg('on_sale')).'" class="remove-filter-element on_sale">'.esc_html__('On Sale','bacola-core').'</a></li>';
		}

		if($min_price){
		$output .= '<li><a href="'.esc_url(remove_query_arg('min_price')).'" class="remove-filter-element min_price">' . sprintf( __( 'Min %s', 'woocommerce' ), wc_price( $min_price ) ) . '</a></li>';
		}
		
		if($max_price){
		$output .= '<li><a href="'.esc_url(remove_query_arg('max_price')).'" class="remove-filter-element max_price">' . sprintf( __( 'Max %s', 'woocommerce' ), wc_price( $max_price ) ) . '</a></li>';
		}
		
		if(isset($_GET['filter_cat'])){
			$terms = get_terms( array(
				'taxonomy' => 'product_cat',
				'hide_empty' => false,
				'parent'    => 0,
				'include' 	=> explode(',',$_GET['filter_cat']),
			) );
			
			foreach ( $terms as $term ) {
				$term_children = get_term_children( $term->term_id, 'product_cat' );
				$output .= '<li><a href="'.esc_url( bacola_get_cat_url($term->term_id) ).'" class="remove-filter-element product_cat" id="'.esc_attr($term->term_id).'">'.esc_html($term->name).'</a></li>';
				if($term_children){
					foreach($term_children as $child){
						$childterm = get_term_by( 'id', $child, 'product_cat' );
						if(in_array($childterm->term_id, explode(',',$_GET['filter_cat']))){ 
							$output .= '<li><a href="'.esc_url( bacola_get_cat_url($childterm->term_id) ).'" class="remove-filter-element product_cat" id="'.esc_attr($childterm->term_id).'">'.esc_html($childterm->name).'</a></li>';
						}
					}
				}
			}
		
		}

        if(isset($_GET['filter_ingredient'])){
			$terms = get_terms( array(
				'taxonomy' => 'product-ingredient',
				'hide_empty' => false,
				'parent'    => 0,
				'include' 	=> explode(',',$_GET['filter_ingredient']),
			) );
			
			foreach ( $terms as $term ) {
				$term_children = get_term_children( $term->term_id, 'product_cat' );
				$output .= '<li><a href="'.esc_url( bacola_get_cat_url($term->term_id) ).'" class="remove-filter-element product_cat" id="'.esc_attr($term->term_id).'">'.esc_html($term->name).'</a></li>';
				if($term_children){
					foreach($term_children as $child){
						$childterm = get_term_by( 'id', $child, 'product-ingredient' );
						if(in_array($childterm->term_id, explode(',',$_GET['filter_ingredient']))){ 
							$output .= '<li><a href="'.esc_url( bacola_get_cat_url($childterm->term_id) ).'" class="remove-filter-element product_cat" id="'.esc_attr($childterm->term_id).'">'.esc_html($childterm->name).'</a></li>';
						}
					}
				}
			}
		
		}
		
		$output .= '</ul>';
	}
	
	return $output;
}

function hch_catalog_ordering_start(){
    ?>
    <div class="before-shop-loop">
        <div class="shop-view-selector">
        <?php if(get_theme_mod('bacola_grid_list_view','0') == '1'){ ?>
        
            <?php if(bacola_shop_view() == 'list_view') { ?>
                <a href="<?php echo esc_url(add_query_arg('shop_view','list_view')); ?>" class="shop-view active">
                    <i class="klbth-icon-list-grid"></i>
                </a>
                <a href="<?php echo esc_url(add_query_arg(array('column' => '2', 'shop_view' => 'grid_view'))); ?>" class="shop-view">
                    <i class="klbth-icon-2-grid"></i>
                </a>
                <a href="<?php echo esc_url(add_query_arg(array('column' => '3', 'shop_view' => 'grid_view'))); ?>" class="shop-view">
                    <i class="klbth-icon-3-grid"></i>
                </a>
                <a href="<?php echo esc_url(add_query_arg(array('column' => '4', 'shop_view' => 'grid_view'))); ?>" class="shop-view">
                    <i class="klbth-icon-4-grid"></i>
                </a>
            <?php } else { ?>
                <a href="<?php echo esc_url(add_query_arg('shop_view','list_view')); ?>" class="shop-view">
                    <i class="klbth-icon-list-grid"></i>
                </a>
                <?php if(bacola_get_column_option() == 2){ ?>
                    <a href="<?php echo esc_url(add_query_arg(array('column' => '2', 'shop_view' => 'grid_view'))); ?>" class="shop-view active">
                        <i class="klbth-icon-2-grid"></i>
                    </a>
                    <a href="<?php echo esc_url(add_query_arg(array('column' => '3', 'shop_view' => 'grid_view'))); ?>" class="shop-view">
                        <i class="klbth-icon-3-grid"></i>
                    </a>
                    <a href="<?php echo esc_url(add_query_arg(array('column' => '4', 'shop_view' => 'grid_view'))); ?>" class="shop-view">
                        <i class="klbth-icon-4-grid"></i>
                    </a>
                <?php } elseif(bacola_get_column_option() == 3){ ?>
                    <a href="<?php echo esc_url(add_query_arg(array('column' => '2', 'shop_view' => 'grid_view'))); ?>" class="shop-view">
                        <i class="klbth-icon-2-grid"></i>
                    </a>
                    <a href="<?php echo esc_url(add_query_arg(array('column' => '3', 'shop_view' => 'grid_view'))); ?>" class="shop-view active">
                        <i class="klbth-icon-3-grid"></i>
                    </a>
                    <a href="<?php echo esc_url(add_query_arg(array('column' => '4', 'shop_view' => 'grid_view'))); ?>" class="shop-view">
                        <i class="klbth-icon-4-grid"></i>
                    </a>
                <?php } else { ?>
                    <a href="<?php echo esc_url(add_query_arg(array('column' => '2', 'shop_view' => 'grid_view'))); ?>" class="shop-view">
                        <i class="klbth-icon-2-grid"></i>
                    </a>
                    <a href="<?php echo esc_url(add_query_arg(array('column' => '3', 'shop_view' => 'grid_view'))); ?>" class="shop-view">
                        <i class="klbth-icon-3-grid"></i>
                    </a>
                    <a href="<?php echo esc_url(add_query_arg(array('column' => '4', 'shop_view' => 'grid_view'))); ?>" class="shop-view active">
                        <i class="klbth-icon-4-grid"></i>
                    </a>
                <?php } ?>

            <?php } ?>
        <?php } ?>
        </div>
        
        <div class="mobile-filter">
            <a href="#" class="filter-toggle">
                <i class="klbth-icon-filter"></i>
                <span><?php esc_html_e('Filter Products','bacola-core'); ?></span>
            </a>
        </div>
        
        <!-- For get orderby from loop -->
        <?php do_action('klb_catalog_ordering'); ?>
        
        
        <!-- For perpage option-->
        <?php if(get_theme_mod('bacola_perpage_view','0') == '1'){ ?>
            <?php $perpage = isset($_GET['perpage']) ? $_GET['perpage'] : ''; ?>
            <?php $defaultperpage = wc_get_default_products_per_row() * wc_get_default_product_rows_per_page(); ?>
            <?php $options = array($defaultperpage,$defaultperpage*2,$defaultperpage*3,$defaultperpage*4); ?>
            <form class="products-per-page product-filter" method="get">
                <span class="perpage-label"><?php esc_html_e('Show','bacola-core'); ?></span>
                <?php if (bacola_get_body_class('bacola-ajax-shop-on')) { ?>
                    <select name="perpage" class="perpage filterSelect" data-class="select-filter-perpage">
                <?php } else { ?>
                    <select name="perpage" class="perpage filterSelect" data-class="select-filter-perpage" onchange="this.form.submit()">
                <?php } ?>
                    <?php for( $i=0; $i<count($options); $i++ ) { ?>
                    <option value="<?php echo esc_attr($options[$i]); ?>" <?php echo esc_attr($perpage == $options[$i] ? 'selected="selected"' : ''); ?>><?php echo esc_html($options[$i]); ?></option>
                    <?php } ?>

                </select>
                <?php wc_query_string_form_fields( null, array( 'perpage', 'submit', 'paged', 'product-page' ) ); ?>
            </form>
        <?php } ?>
    </div>
    <?php echo hch_remove_klb_filter(); ?>
    <?php wp_enqueue_style( 'klb-remove-filter'); ?>
<?php
}

function add_element_scroll_jax_filter() {
    ?>
    <div class="product-custom-reponsive-ajax">
	</div>	
    <?php
}

function be_woocommerce_catalog_orderby_callback() {
    // $order = [
    //     'menu_order' => __( 'Default sorting', 'woocommerce' ),
    //     'popularity' => __( 'Najprej najpriljubljeni ', 'woocommerce' ),
    //     'date'       => __( 'Najprej najnovejši ', 'woocommerce' ),
    //     'price'      => __( 'Najprej najcenejši ', 'woocommerce' ),
    //     'price-desc' => __( 'Sort by price: high to low', 'woocommerce' ),
    //     'rating'     => __( 'Sort by average rating', 'woocommerce' ),
    //     'most_rating' => __( 'Najprej z največ ocenami ', 'woocommerce' ),
    // ];
    $order = [
        'menu_order' => __( 'Default sorting', 'woocommerce' ),
        'popularity' => __( 'Najprej najpriljubljeni ', 'woocommerce' ),
        'date'       => __( 'Najprej najnovejši ', 'woocommerce' ),
        'price'      => __( 'Najprej najcenejši ', 'woocommerce' ),
        'rating'     => __( 'Najprej najbolje ocenjeni ', 'woocommerce' ),
        'most_rating' => __( 'Najprej z največ ocenami ', 'woocommerce' ),
    ];
    return $order;

}

function be_custom_query_orderby_callback($args, $orderby, $order) {
    if($orderby=='most_rating') {
        $args['orderby'] = 'meta_value_num';
        $args['order'] = 'DESC';
        $args['meta_key'] = '_wc_review_count';
    }
    return $args;

}

function update_rating_most_product () {
    $args = array(
        'post_type'      => 'product',
        'posts_per_page' => -1,
        'post_status'=>'publish'
    );

    $loop = new WP_Query( $args );
    while ( $loop->have_posts() ) : $loop->the_post();
        $id_product = get_the_ID();
        $rating_most = get_field('rating_most_product',$id_product);
        if(empty($rating_most)) {
            update_field('rating_most_product',0,$id_product);
        }
    endwhile;

    wp_reset_query();
}

// function update_rating_most_single_product( $comment_ID, $comment_approved, $commentdata ) {
//     $id_product = $commentdata['comment_post_ID'];
//     $product = wc_get_product($id_product);
//     if(!empty($product)) {
//         $rating_most = get_field('rating_most_product',$id_product);
//         $rating_most = $rating_most + 1;
//         update_field('rating_most_product',$rating_most,$id_product);
//     }
// }

// add_action('wp_head',function(){
//     echo '<pre>';
//     print_r(get_post_meta(285));
//     echo '</pre>';

// })

function query_related_product ($slug_potype,$post_per_page) {
    global $post;
    $args = array(
		'post_type' => $slug_potype,
		'posts_per_page' => $post_per_page,
		'post_status'    => 'publish',
        'meta_query'		=> array(
            array(
                'key' => 'related_products',
                'value' => '"' . $post->ID . '"',
                'compare' => 'LIKE'
            )
        )
	);
    return $args;
}

function be_add_related_recipes_blog_video() {
    if ( ! is_singular( 'product' )) {
		return;
	}
    ?>
    <section class="klb-module site-module recently-viewed wrapper-related-product">
        <div class="container">
            <div class="content-related-product">
                <?php 
                    $args_re = query_related_product('recipe',2);
                    $loop_re = new WP_Query( $args_re );
                    ob_start();
                    if ( $loop_re->have_posts() ) {
                ?>
                    <div class="product-related">
                        <div class="title">
                            <svg width="53" height="53" viewBox="0 0 53 53" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect width="53" height="53" rx="5" fill="#EA2B0F"/>
                                <g clip-path="url(#clip0_485_2)">
                                <path d="M26.9991 21.1365C33.5924 21.1365 38.9557 26.6002 38.9557 33.3168H40.8109C40.8109 25.8744 35.1062 19.7663 27.9193 19.2825V16.8918H30.5518V15H23.4465V16.8899H26.0623V19.2825C18.8827 19.7739 13.1892 25.8782 13.1892 33.3168H15.0444C15.0444 26.6002 20.4077 21.1365 27.001 21.1365H26.9991Z" fill="white"/>
                                <path d="M42 35.1689H12V37.0588H42V35.1689Z" fill="white"/>
                                </g>
                                <defs>
                                <clipPath id="clip0_485_2">
                                <rect width="30" height="22.0588" fill="white" transform="translate(12 15)"/>
                                </clipPath>
                                </defs>
                            </svg>
                            <h4><?php echo __('Recepti','hch-addons')?></h4>
                        </div>
                        <div class="list-related">
                        <?php 
                            while ( $loop_re->have_posts() ) : $loop_re->the_post();
                                $rp_id = get_the_ID();
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
                            endwhile;
                        ?>
                        </div>
                    </div>
                <?php 
                    }
                    echo ob_get_clean();
                    wp_reset_postdata();

                    $args_p = query_related_product('post',2);
                    $loop_p = new WP_Query( $args_p );
                    ob_start();
                    if ( $loop_p->have_posts() ) {
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
                                    while ( $loop_p->have_posts() ) : $loop_p->the_post();
                                        $rp_id = get_the_ID();
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
                                    endwhile;
                                ?>
                                </div>
                            </div>
                        <?php
                    }
                    echo ob_get_clean();
                    wp_reset_postdata();
                   
                ?>
            </div>
            <?php
                 $args_rv = query_related_product('recipe-video',4);
                 $loop_rv = new WP_Query( $args_rv );
                 ob_start();
                 if ( $loop_rv->have_posts() ) {
                     ?>
                         <div class="product-related-recipe-video">
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
                                 while($loop_rv->have_posts()):$loop_rv->the_post();
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
                         </div>
                     <?php
                 }
            ?>
        </div>
    </section>
    <?php
}


function custom_taxonomies_terms_links($id,$slug_tax){
    $out = array();
    $terms = get_the_terms( $id, $slug_tax );
    if ( !empty( $terms ) ) {
        $last_index = count($terms) - 1;
        foreach ( $terms as $key=>$term ) {
            if($last_index != $key ) {
                $sepa = ',';
            }else{
                $sepa = '';
            }
            $out[] =
            ' <li><a href="'
            . get_term_link( $term->slug, $slug_tax ) .'">'
            . $term->name.$sepa
            . "</a></li>\n";
        }
        $out[] = "</ul>\n";
    }
    return implode('', $out );
}  


?>