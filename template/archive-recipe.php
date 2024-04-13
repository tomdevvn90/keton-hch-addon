<?php

get_header();
$active_class = '';
$slug_tax = isset($_GET['cat']) ? $_GET['cat'] : '';
if(!empty(get_queried_object()->name) && $slug_tax == '') {
	if(get_queried_object()->name == 'recipe') {
		$active_class = 'current_tax';
	}
}
?>
<div class="wrapper-recipes-tax">
	<div class="container-tax">
		<div class="wrapper-breadcrum-custom">
			<?php 
				echo do_shortcode('[keton_breadcrumb]')
			?>
		</div>
		<div class="page-archive-title">
			<h2>
				<?php 
					echo get_field('page_recipe_archive_global','options')['title'];
				?>
			</h2>
		</div>
		<div class="list-filter-recipes-tax">
			<?php
				$all_cats = be_get_taxonomies('recipe-cat');
				if(!empty($all_cats)) {
					?>
					<div class="item-filter-cat <?php echo $active_class?>" >
						<a href="/recipe">
							VSI RECEPTI
						</a>
					</div>
					<?php
					foreach ($all_cats as $key => $cat) {
						$id_term = intval($key);
						$term = get_term_by('id', $id_term, 'recipe-cat');
						$active_class = '';
						if($slug_tax == $term->slug) {
							$active_class = 'current_tax';
						}
						?>
							<div class="item-filter-cat <?php echo $active_class?>">
								<a href="<?php echo get_term_link($id_term)?>">
									<?php echo $cat?>
								</a>
							</div>
						<?php
					}
				}
			?>
		</div>
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
				if(!empty(get_queried_object()->term_id)) {
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
				}else{
					$name_term = $term[0]->name;
				}
			?>
				<div class="item-recipe">
					<a href="<?php echo $link?>">
						<img class="thumb" src="<?php echo $url_thumb ?>"/>
						<div class="tax-recipe">
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