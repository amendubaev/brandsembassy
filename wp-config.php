<?php

/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the
 * installation. You don't have to use the web site, you can
 * copy this file to "wp-config.php" and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * MySQL settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'wordpress');
define('FS_METHOD', 'direct');

/** MySQL database username */
define('DB_USER', 'root');
define( 'ALLOW_UNFILTERED_UPLOADS', true );

/** MySQL database password */
define('DB_PASSWORD', 'password');


/** MySQL hostname */
define('DB_HOST', 'db');


/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');


/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         ';#$r0Yo; (cT7?|7GvbRT^gPmb,GS?j~nz8Urw%-5t/Bxa?|NE_~IW4*Oxuw`E~t');

define('SECURE_AUTH_KEY',  'F=p!Xtds,Cst)ZbuB.srv1;8UTO@(ds3|Aw<SM=Ed9~efbbtInTn` :`sKb8V}]y');

define('LOGGED_IN_KEY',    'K1k~T~)Ep@O>7BToM $jc-lu14dP3<Pi|Z&)4 Kg4Kq5!}q*o2jBl$CDqRQVb6QZ');

define('NONCE_KEY',        '%Wmn*+%Fir],w%tsvYQIJl%y1mO./w(zmvjvd:E-F`>DyU-;dd_YJJ -(NuL>3`2');

define('AUTH_SALT',        '>&M_iLOF0:eo.Ha3Rhj?{RX@<$iW[T|wvzfMDly#lX0q;R*QvGXt4QiIT./79HEq');

define('SECURE_AUTH_SALT', 'SbE)ZEA:!7)7Mw,pm}1K-kTq0[1;bH:(S(t9!Ihle}*M9*||P>&j$9yZ:![OVX`$');

define('LOGGED_IN_SALT',   '9V^j04rv=I[khTLb~.N{rQ7w1SXll-P5B ??<r>k/X<^yDIq0g00#%r^,Pf^MQ?}');

define('NONCE_SALT',       'phXWx[%ScA7A)c$i%eX10FZb=y&2We+#YA;4Uoq+PoV!d9O^(UzCd}rDvi`y#o(.');


/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';


/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the Codex.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */

define('WP_MEMORY_LIMIT', '256M');
// Enable WP_DEBUG mode
define('WP_DEBUG', true);
// define('WP_DEBUG', false);
define('WP_DEBUG_LOG', false);
define('WP_DEBUG_DISPLAY', true);
@ini_set('display_errors', 1);
define('DISABLE_PDF_CACHE', true);
define('DOMPDF_PAPER_SIZE', 'A4');
define('DOMPDF_ENABLE_REMOTE', TRUE);

define('ADMIN_COOKIE_PATH', '/');
define('COOKIE_DOMAIN', '');
define('COOKIEPATH', '');
define('SITECOOKIEPATH', ''); 

// // Use dev versions of core JS and CSS files (only needed if you are modifying these core files)
// define('SCRIPT_DEBUG', true);
/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');