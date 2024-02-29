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



function keton_force_template_recipe_cat( $template ) {
    if( is_tax('recipe-cat') || is_archive('recipe')) {
        $template = B_HELPERS_DIR .'/template/archive-recipe.php';
    }

    if(is_singular('recipe-video')) {
        $template = B_HELPERS_DIR .'/template/single-recipe-video.php';
    }
    // if(is_archive('recipe')) {
    //     $template = B_HELPERS_DIR .'/template/archive-recipe.php';
    // }
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
      return '<iframe class="video" src="https://player.vimeo.com/video/' . $matches[1] . '?autoplay=1&loop=1&title=0&byline=0&portrait=0&muted=1" width="auto" height="auto" frameborder="0" allow="autoplay; fullscreen" allowfullscreen></iframe>';
    }
    preg_match($regExpYt, $url, $matches);
    if (isset($matches[7]) && is_array($params) && isset($params['img']) && $params['img']) {
          return '<a href="' . $url . '"' . (isset($params['class']) ? ' class="popup-youtube ' . $params['class'] . '"' : '') . '><img src="http://img.youtube.com/vi/' . $matches[7] . '/hqdefault.jpg" alt=""></a>';
    } else if (isset($matches[7])) {
      return '<iframe class="video" width="auto" height="auto" src="https://www.youtube.com/embed/' . $matches[7] . '?autoplay=1&mute=1" allowfullscreen></iframe>';
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
        return "0 oddaja";
    }
    return $count.'. oddaja';
}

?>