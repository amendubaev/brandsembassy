<?php
    $order = $_GET['order'] ?? 'asc';
?>

<div class="filter-sort">
    <span class="filter-label" for="sorting"><?php echo __('Sorting:', 'brandsembassy'); ?></span>
    <div class="filter-selected"><span class="filter-sort--link"><?php echo __('Alphabetically (A-z)', 'brandsembassy'); ?></span><span class="icon icon-arrow-down"></span>
        <ul id="sorting">
            <li data-value="asc" <?php echo (strtolower($order) === 'asc') ? 'class="sort-selected"' : '' ?>><a class="sort-link" href="?order=asc"><?php echo __('Alphabetically (A-z)', 'brandsembassy'); ?></a></li>
            <li data-value="desc" <?php echo (strtolower($order) === 'desc') ? 'class="sort-selected"' : '' ?>><a class="sort-link" href="?order=desc"><?php echo __('Alphabetically (Z-a)', 'brandsembassy'); ?></a></li>
        </ul>
    </div>
</div>
