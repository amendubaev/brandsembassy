<!DOCTYPE html>
<html>
<head>
    <title><?php wp_title(''); ?></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
    <link rel="stylesheet"  href="<?php echo get_stylesheet_directory_uri() . '/assets/css/main.css'; ?>" type="text/css" media="all">
</head>

<body>
    <style>
        body {
            font-family: "DejaVu Sans";
            background: white;
        }

        .post-header-content {
            clear: both;
            position: relative;
        }

        .post-header-photo-block {
            position:absolute;
            left: 0;
            width: 25%;
        }

        .post-header--name,
        .post-header--middlename {
            line-height: 1;
        }

        .post-category--item {
            font-size: 14px;
        }

        .page-content {
            padding: 0 15px;
        }

        .post-header--title {
            display: inline-block;
            margin-top: 15px;
            width: 100%;
        }

        .post-header--categories {
            margin-top: 10px;
            font-size: 13px;
        }

        .post-content {
            padding: 0 40px;
        }

        .post-header--text {
            margin-left: 30%;
        }

        .container {
            position: relative;
            padding-right: 15px;
            padding-left: 15px;
        }

        .post-header--name,
        .post-header--middlename, {
            font-size: 18px;
            font-wight: 700;
        }
        .post-description--title {
            font-size: 16px;
            font-weight: 700;
        }
        .post-description--text {
            font-size: 13px;
            line-height: 16px;
        }
        .post-content {
            margin-top: 5%;
        }
        .post-content--descriptions {
            margin-top: 7%;
        }
    </style>
    <div class="page-content">
        <?php
        $expert_socials = array_filter([
            'facebook' => get_field('facebook'),
            'linkedin' => get_field('linkedin'),
            'instagram' => get_field('instagram'),
            'twitter' => get_field('twitter'),
            'youtube' => get_field('youtube'),
            'vk' => get_field('vk')
        ]);

        $expert_descriptions = array_filter([
            'Деятельность' => get_field('activity'),
            'Коллектив' => get_field('team'),
            'Проекты' => get_field('projects'),
            'Факты и события' => get_field('facts')
        ]);
        ?>

        <div class="post-header">
            <div class="container">
                <div class="post-header-content">
                    <div class="post-header-photo-block">
                        <?php if(has_post_thumbnail()) : ?>
                            <img src="<?php bre_the_post_thumbnail_url('full', false, true); ?>" width="100%">
                        <?php endif; ?>
                    </div>

                    <div class="post-header--text">
                        <div class="post-header--title">
                            <h1 class="post-header--name"><?php the_title(); ?></h1>
                            <h2 class="post-header--middlename"><?php echo get_post_field('post_content', $post->ID); ?></h2>
                        </div>
                        <div class="post-header--categories">
                            <?php $industries = bre_get_post_child_terms(get_the_ID(), 'industries'); ?>
                            <?php if ($industries) : ?>
                                <?php foreach ($industries as $industry) : ?>
                                    <span class="post-category--item">
                                        <?php echo (next($industries) ) ? $industry->name . ',&nbsp;' : $industry->name; ?>
                                    </span>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>

                        <div class="company-experts row">
                            <?php
                                // Attached expert
                                $attached_experts = new WP_Query(array(
                                    'post_type' => 'speakers',
                                    'post__in'	=> get_field('attached_expert'),
                                ));

                                $experts = [];
                                if ($attached_experts->posts) {
                                    $experts[] = $attached_experts->posts[0];
                                }

                                // Another experts
                                $another_experts = get_field('another_experts');
                                if ($another_experts) {
                                    foreach ($another_experts as $another_expert) {
                                        $experts[] = $another_expert;
                                    }
                                }
                            ?>

                            <?php if ($experts) : ?>
                                <?php foreach ($experts as $expert) : ?>
                                    <div>
                                        <div>
                                            <?php if (has_post_thumbnail($expert->ID)) : ?>
                                                <img src="<?php bre_the_post_thumbnail_url('full', get_the_post_thumbnail_url($expert->ID), true); ?>" width="60">
                                            <?php endif; ?>
                                            <div  class="card-author--content">
                                                <div class="card-author--name"><?php echo get_the_title($expert->ID); ?></div>
                                                <div class="card-author--date"><?php echo get_field('expert_skills', $expert->ID); ?></div>
                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="post-content">
            <div class="container">
                <div class="row">
                    <div>
                        <div class="post-content--descriptions">
                            <?php foreach ($expert_descriptions as $expert_description_title => $expert_description_text): ?>
                                <div class="post-description">
                                    <div class="post-description--title"><?php echo $expert_description_title; ?></div>
                                    <div class="post-description--text"><?php echo $expert_description_text; ?></div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>