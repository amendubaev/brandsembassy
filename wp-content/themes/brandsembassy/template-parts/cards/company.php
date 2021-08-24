<a class="card-item company-item" href="<?php the_permalink(); ?>">
    <div class="card-item--content">
        <div class="card-item--locations">
            <?php
                $locations = wp_get_object_terms($post->ID, 'locations');
                $walk = new Walker_Taxonomy();

                echo $walk->walk($locations, 0);
            ?>
        </div>

        <div class="card-item--title">
            <?php the_title(); ?>
        </div>

        <?php
        $industries = bre_get_post_child_terms($post->id, 'industries', 2);
        if ($industries) : ?>
            <div class="card-item--tags">
                <ul class="tag-items">
                    <?php foreach($industries as $industry) :  ?>
                        <li class="tag-item"><?php echo $industry->name; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>

        <?php if ($company_expert = get_field('attached_expert')) : ?>
            <div class="card-item--author">
                <?php if (has_post_thumbnail($company_expert)) : ?>
                    <div class="card-author--photo" style="background-image: url(<?php echo get_the_post_thumbnail_url($company_expert, 'full'); ?>);"></div>
                <?php endif; ?>
                <div class="card-author--content">
                    <div class="card-author--name"><?php echo get_the_title($company_expert); ?></div>
                    <div class="card-author--date"><?php echo get_field('expert_skills', $company_expert); ?></div>
                </div>
            </div>
        <?php endif; ?>

        <?php if($company_experts = get_field('another_experts')) :
            $count_experts = count($company_experts) + ($company_expert ? 1 : 0); ?>
            <div class="card-item--meta">
                <span class="card-items--count"><?php printf( _n('%s expert', '%s experts', $count_experts, 'brandsembassy'), $count_experts); ?></span>
            </div>
        <?php endif; ?>
    </div>
</a>