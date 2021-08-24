<div class="card-item--benefit">
    <div class="card-item--counter"><?php echo str_pad($benefit_number, 2, '0', STR_PAD_LEFT); ?></div>
    <div class="card-item--description">
        <?php the_sub_field('benefit_text'); ?>
    </div>
</div>
