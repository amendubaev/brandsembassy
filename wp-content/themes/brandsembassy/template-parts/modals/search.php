<form role="search" method="get" class="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>">
    <div class="section_home--search">
        <div class="home-search--field">
            <div class="search-field">
				<input class="search-field--input" type="text" value="<?php echo get_search_query(); ?>" name="s" placeholder="<?php echo __('Search', 'brandsembassy'); ?>">
            </div>
            <div class="search-categories">
                <span class="search-categories--text"><?php echo __('All sections', 'brandsembassy'); ?></span>
                <div class="search-category--list">
                    <div class="search-list--field">
                        <input class="search-list--checkbox" type="checkbox" id="patterns" name="patterns" value="patterns">
                        <label for="patterns"><?php echo __('Patterns', 'brandsembassy'); ?></label>
                    </div>
                    <div class="search-list--field">
                        <input class="search-list--checkbox" type="checkbox" id="megatrends" name="patterns" value="patterns">
                        <label for="megatrends"><?php echo __('Megatrends', 'brandsembassy'); ?></label>
                    </div>
                    <div class="search-list--field">
                        <input class="search-list--checkbox" type="checkbox" id="industries" name="industries" value="industries">
                        <label for="industries"><?php echo __('Industries', 'brandsembassy'); ?></label>
                    </div>
                    <div class="search-list--field">
                        <input class="search-list--checkbox" type="checkbox" id="branches" name="industries" value="industries">
                        <label for="branches"><?php echo __('Branches', 'brandsembassy'); ?></label>
                    </div>
                    <div class="search-list--field">
                        <input class="search-list--checkbox" type="checkbox" id="companies" name="companies" value="companies">
                        <label for="companies"><?php echo __('Companies', 'brandsembassy'); ?></label>
                    </div>
                </div>
            </div>
            <button type="submit" class="search-button">
                <span class="icon icon-search"></span>
            </button>
        </div>
    </div>
</form>