<?php get_header(); ?>

<?php
$term = get_term_by('slug', get_query_var('term'), 'industries');
$template = $term->parent ? 'branch-item' : 'branches';

get_template_part('taxonomy-industries', $template);
?>

<?php get_footer(); ?>
