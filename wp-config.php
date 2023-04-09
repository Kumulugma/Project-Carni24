<?php






//define( 'WP_DEBUG', true );
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
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'u521951204_carni24' );

/** MySQL database username */
define( 'DB_USER', 'u521951204_carni24' );

/** MySQL database password */
define( 'DB_PASSWORD', '~q8RHPjF1' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '%x_ep}VAbpJcGVub7[x6dt 7hS?2/S>9}wkFlG^P7ZK ihEAMH.fAhU$T1/mmQDn' );
define( 'SECURE_AUTH_KEY',  '/(naOYnG0Wm}HYF=w$Gsp+2xV7Mc6mv$q7ty 3/.RqAv*k43;K];+W7r^s_#WE$:' );
define( 'LOGGED_IN_KEY',    'xzRr c_*ZNK!?mL4,3B{?@+Q,9}q=#/jUk&hSLt,v8}#t2s!dA}5ySZ/T(VMb<uY' );
define( 'NONCE_KEY',        '5kvmLl)Wj]bN0cQ*`v2J<wprIE:`|iB$.6]VwXl@5X(;~8>:nd:+9IV|!56&xD;X' );
define( 'AUTH_SALT',        '-A2%2C2HEHF_m%^SGW}YxIfwkfrx|^FOQvWx%PRa5[^2+X7}ZJXaYrr&_ue6@p^G' );
define( 'SECURE_AUTH_SALT', 'zox:)@~d[739.UTwtLkZ_EZN))p#zIMI8b7w>o{Ak[R#80RQqS:dvmy](RYWj,-V' );
define( 'LOGGED_IN_SALT',   'elo$m;fAcbbHQ p.gA.eA`WcP=E}}OkSzZEJH2~X6wTf,PReI@De~n<D3O@scVhd' );
define( 'NONCE_SALT',       '6U,13T[#q7mPAi]fcyCXd|9n8G2/[P$M$AV9oKF0%lS!j@K6Vc?YJxGpHf$!o%&)' );

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'c24_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define('WP_DEBUG', false);
define('WP_AUTO_UPDATE_CORE', true);
define('DISABLE_WP_CRON', true);
define('CONCATENATE_SCRIPTS', true);

define( 'FS_METHOD', 'direct' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
//Disable File Edits
define('DISALLOW_FILE_EDIT', true);