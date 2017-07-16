<?php get_header(); ?>

<section id="content">
	<div class="container">
		<div class="row">
			<div id="main" class="col-sm-8 col-md-9">
				<div>
					<?php 
					$desc = '';
					if ( is_tag() ) { $desc = tag_description(); }
					if ( is_category() ) { $desc = category_description(); }
					if ( ! empty( $desc ) ) { ?>
						<h5 class="description box">
							<?php echo ( $desc ); ?>
						</h5>
					<?php } ?>
				</div>
				<div class="page">
					<div class="post-content">
						<?php if ( have_posts() ): ?>
							<div class="blog-infinite">
								<?php while(have_posts()): the_post();
									trav_get_template( 'loop-blog.php', '/templates' );
								endwhile; ?>
							</div>
							<?php
								global $ajax_paging;
								if ( ! empty( $ajax_paging ) ) {
									next_posts_link( __( 'LOAD MORE POSTS', 'trav' ) );
								} else {
									echo paginate_links( array( 'type' => 'list' ) );
								}
							?>
						<?php else: ?>
							<div class="travelo-box">
								<?php echo __( 'No posts found.', 'trav' ) ?>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<div class="sidebar col-sm-4 col-md-3">
				<?php dynamic_sidebar( 'sidebar-post' ); ?>
			</div>
		</div>
	</div>
</section>

<?php get_footer();