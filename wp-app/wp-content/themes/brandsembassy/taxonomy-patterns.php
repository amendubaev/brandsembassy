<?php get_header(); ?>

<?php
$term = get_term_by('slug', get_query_var('term'), 'patterns');
$template = $term->parent ? 'megatrend-item' : 'megatrends';

get_template_part('taxonomy-patterns', $template);
?>

<?php get_footer(); ?>
