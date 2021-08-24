<div class="wrap">
    <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>

    <form method="post" action="<?php echo esc_html( admin_url( 'admin.php' ) ); ?>" enctype="multipart/form-data">
        <input type="hidden" name="action" value="industries-import" />

        <div>
            <h2>Импортируйте индустрии из CSV файла</h2>

            <div class="options">
                <p>
                    <label>Выберите CVS файл</label>
                    <br />
                    <input type="file" name="industries_csv" accept=".csv"/>
                </p>
            </div>

            <?php
            wp_nonce_field( 'industries_csv_import', 'industries_csv' );
            submit_button('Импорт');
            ?>
    </form>
</div>