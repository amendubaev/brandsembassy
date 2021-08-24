<?php
/**
 * Template Name: Политика
 */
get_header(); ?>
<?php while ( have_posts() ) : the_post(); ?>
    <div class="page-wrapper background-white">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="page-title"><h1><?php the_title(); ?></h1></div>
                </div>
                <div class="col-md-3 d-none d-md-block">
                    <div class="sidebar-policy">
                        <nav class="sidebar-nav">
                            <ul class="sidebar-nav--list">
                                <?php if( have_rows('section') ):
                                    $section_counter = 0;
                                    while ( have_rows('section') ) : the_row();
                                    $section_counter++; ?>
                                    <li class="nav-list--item"><a href="#<?php echo $section_counter; ?>"><strong><?php echo $section_counter; ?> <?php the_sub_field('section_title'); ?></strong></a></li>
                                    <?php if( have_rows('section_block') ):
                                    $block_counter = 0;
                                        while ( have_rows('section_block') ) : the_row();
                                            $block_counter++; ?>
                                            <li class="nav-list--item"><a href="#<?php echo $section_counter . '-' . $block_counter; ?>"><?php echo $section_counter . '.' . $block_counter; ?> <?php the_sub_field('block_title'); ?></a></li>
                                    <?php endwhile; endif; ?>
                                <?php endwhile; endif; ?>
                            </ul>
                        </nav>
                    </div>
                </div>
                <div class="col-md-9 col-12">
                    <?php if( have_rows('section') ):
                        $section_counter = 0;
                        while ( have_rows('section') ) : the_row();
                        $section_counter++; ?>
                        <div id="<?php echo $section_counter; ?>">
                            <h2 class="policy-title" ><?php the_sub_field('section_title'); ?></h2>
                        </div>
                        <?php if( have_rows('section_block') ):
                        $block_counter = 0;
                            while ( have_rows('section_block') ) : the_row();
                                $block_counter++; ?>
                                <div id="<?php echo $section_counter . '-' . $block_counter; ?>" class="policy-block">
                                    <h3 class="policy-subtitle"><?php the_sub_field('block_title'); ?></h3>
                                    <div><?php the_sub_field('block_text'); ?></div>
                                </div>
                        <?php endwhile; endif; ?>
                    <?php endwhile; endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php endwhile; ?>
<?php get_footer(); ?>
