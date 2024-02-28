<?php 

get_header();
?>

<div class="wrapper-recipes-tax">
	<div class="list-filter-recipes-tax">
		12312
	</div>
	<div class="site-posts">
		<?php if (have_posts()) : while (have_posts()) : the_post(); 

			$id_post = get_the_ID();
			$url_thumb = get_the_post_thumbnail_url($id_post,'full');
			$link = get_permalink($id_post);
			$term = get_the_terms($id_post,'recipe-cat');
			$title = get_the_title($id_post);
			$date = get_the_date('F j, Y',$id_post);
			if(count($term)>=2) {
				if($term[0]->term_id==get_queried_object()->term_id) {
					$name_term  = $term[1]->name;
				}else{
					$name_term = $term[0]->name;
				}
			}else{
				if($term[0]->term_id==get_queried_object()->term_id) {
					$name_term = '';
				}else{
					$name_term = $term[0]->name;
				}
			}
			
		?>
			<div class="item-recipe">
				<a href="<?php echo $link?>">
					<img class="thumb" src="<?php echo $url_thumb ?>"/>
					<div class="tax-reipec">
						<?php 
							if(!empty($name_term)) {
								echo $name_term;
							}
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
				</a>
			</div>
		<?php
			endwhile; 
			get_template_part( 'post-format/pagination' );
			wp_reset_query();
		?>
		<?php else : ?>

			<h2><?php esc_html_e('No Posts Found', 'bacola') ?></h2>

		<?php endif; ?>
	</div>
</div>
