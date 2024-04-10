<?php 

get_header();
// $active_class = '';
// if(!empty(get_queried_object()->name)) {
// 	if(get_queried_object()->name == 'recipe') {
// 		$active_class = 'current_tax';
// 	}
// }
?>
<div class="wrapper-recipes-tax">
	<div class="container-tax">

		<div class="site-posts">
			<?php if (have_posts()) : 
			?>
			<div class="list-site-posts">
			<?php
				while (have_posts()) : the_post(); 
				$id_post = get_the_ID();
				$url_thumb = get_the_post_thumbnail_url($id_post,'full');
				$link = get_permalink($id_post);
				$term = get_the_terms($id_post,'recipe-cat');
				$title = get_the_title($id_post);
				$date = get_the_date('F j, Y',$id_post);
			
			?>
				<div class="item-recipe">
					<a href="<?php echo $link?>">
                        <div class="thum-wrapper">
                            <img class="thumb thumb-video" src="<?php echo $url_thumb ?>"/>
                            <div class="icon-play">
                                <svg xmlns="http://www.w3.org/2000/svg" width="74" height="74" viewBox="0 0 74 74" fill="none">
                                    <circle cx="37" cy="37" r="36" stroke="white" stroke-width="2"/>
                                    <path d="M48.6383 34.1291C50.3396 35.3238 50.3396 37.8448 48.6383 39.0394L33.5235 49.6528C31.5356 51.0487 28.7995 49.6267 28.7995 47.1976L28.7995 25.971C28.7995 23.5419 31.5356 22.1199 33.5235 23.5158L48.6383 34.1291Z" fill="white"/>
                                </svg>
                            </div>
                            <div class="ovelay"></div>
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
					</a>
				</div>
			<?php
				endwhile; 
				wp_reset_query();
			?>
			</div>
			<?php else : ?>
				<h2 class="text-not-post"><?php esc_html_e('No Posts Found', '4web-addons') ?></h2>
			<?php endif; ?>
		</div>
		<div class="pagination-custom">
			<?php 
				get_template_part( 'post-format/pagination' );
			?>
		</div>
	</div>
</div>
<?php get_footer(); ?>