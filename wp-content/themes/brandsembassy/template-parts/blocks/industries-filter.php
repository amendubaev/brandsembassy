<?php
    $searched_terms = bre_get_searched_terms();
?>

<div class="col-md-4">
    <div class="filter-column">
        <div class="filter-header">
            <div class="filter-name"><?php echo __('Industries and branches', 'brandsembassy'); ?></div>
            <div class="filter-reset" data-reset-taxonomy="industries"><?php echo __('Reset', 'brandsembassy'); ?></div>
        </div>
        <div class="filter-fields" data-taxonomy="industries">
            <div class="filter-field">
                <?php bre_get_the_names_with_count(bre_get_term_parents($searched_terms['industries']), __('Any industry', 'brandsembassy'), false); ?>
            </div>
            <div class="filter-field">
                <?php bre_get_the_names_with_count($searched_terms['industries'], __('Any branch', 'brandsembassy'), false); ?>
            </div>
        </div>
    </div>
</div>
