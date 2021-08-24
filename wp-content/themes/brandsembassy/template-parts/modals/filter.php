<div class="popup popup-search">
    <div class="popup-content popup-content--search">
        <div class="container-fluid">
            <div class="popup-close d-md-none d-block"><span class="icon icon-close"></span></div>
            <div class="popup-header">
                <div class="row">
                    <div class="col-md-8 col-12">
                        <div class="category-search">
                            <input type="text" placeholder="<?php echo __('Search', 'brandsembassy'); ?>" id="searchField" name="s" class="popup-search--field">
                        </div>
                    </div>
                </div>
                <div id="filterColumnsHeader" class="row align-items-center">
                    <div class="col-md-6 col-12">
                        <div class="category-search--title">
                            <span class="icon icon-arrow-left"></span>
                            <span id="headerParentTerm" class="category-search--text"></span>
                        </div>
                    </div>
                    <div class="col-md-6 col-12">
                        <div class="category-search--title">
                            <span id="headerChildTerm" class="category-search--text">
                                <span id="childsAmount" class="category-search--counter"></span>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            <div id="filterColumns" class="row">
                <!-- Parent categories -->
                <div class="col-md-6 col-12">
                    <div id="searchResult" class="category-search--terms category-search--first"></div>
                </div>
                <!-- Child categories -->
                <div class="col-md-6 col-12">
                    <div id="childs"></div>
                </div>
            </div>
        </div>
    </div>

    <!--  Find column  -->
    <div class="popup-content popup-content--find">
        <div class="container-fluid">
            <?php
            global $wp;
            $current_url = home_url( add_query_arg( array(), $wp->request ) );
            ?>
            <form id="searchForm" action="<?php echo $current_url; ?>/" method="get">
                <div id="searchInputs">
                    <?php
                    $terms = ['industries_id', 'locations_id', 'patterns_id'];
                    ?>
                    <?php foreach ($terms as $term) : ?>
                        <?php if(isset($_GET[$term])) : ?>
                            <?php foreach (array_unique($_GET[$term]) as $term_id) : ?>
                                <input type="hidden" name="<?php echo $term; ?>[]" value="<?php echo $term_id?>">
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>

                <div class="popup-header">
                    <div class="category-find">
                        <div class="row align-items-center">
                            <div class="col-md-6">
                                <div class="category-search--title"><?php echo __('Selected', 'brandsembassy'); ?></div>
                            </div>
                            <div class="col-md-6">
                                <button type="submit" class="category-find--button"><?php echo __('Apply', 'brandsembassy'); ?></button>
                            </div>
                        </div>
                    </div>
                    <div class="popup-close"><?php echo __('Close', 'brandsembassy'); ?> <span class="icon icon-close"></span></div>
                </div>
                <div class="category-find--results">
                    <div class="category-find--result">
                        <span id="findParent" class="category-result--count"></span> <span data-category="term-name"></span>
                    </div>
                    <div class="category-find--result">
                        <span id="findChild" class="category-result--count"></span> <span data-category="term-name"></span>
                    </div>
                </div>
                <div id="searchTerms" class="category-find--terms"></div>
            </form>
        </div>
    </div>
</div>