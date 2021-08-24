<?php
    global $wp;
?>

<div class="home-search--field">
    <form action="<?php echo home_url( $wp->request ); ?>">
        <div class="search-inputs"><?php bre_generate_hidden_inputs(['text']); ?></div>

        <div class="search-field">
            <input class="search-field--input" type="text" name="text"
                   placeholder="<?php echo $placeholder ?? __('Search', 'brandsembassy'); ?>"
                   value="<?php echo isset($_GET['text']) ? $_GET['text'] : ''?>">
        </div>

        <button type="submit" class="search-button"><span class="icon icon-search"></span></button>
    </form>
</div>