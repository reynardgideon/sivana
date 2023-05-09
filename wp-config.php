<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'evaluasi.knpk.xyz' );

/** Database username */
define( 'DB_USER', 'talantan' );

/** Database password */
define( 'DB_PASSWORD', 'ENter123!@#' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '8lGD^KMkzN~ap<<K1H!6_X1+0StQStg`td>]~:;x8~*,I(5Y6vv]J6PHG.V2[-a/' );
define( 'SECURE_AUTH_KEY',  '@~hnb?o]$n:%AUUid+/eR$,bG?S-k>&2T!jMr4257X&Xwxehdp_%srxIl/pS~4w1' );
define( 'LOGGED_IN_KEY',    'SI-ftEaP^$1wAK{Rnmh>,.-~Lly~kYemsi[LFrH9rn{wGzUWm|r8wl{ 1T6/Sc^j' );
define( 'NONCE_KEY',        'eq]c,R<xy!bN~=#%Y5bo->$oA6Cvtu8*.w!wlK[k+hqgcQ~dUG^vpn!{W/dib@<A' );
define( 'AUTH_SALT',        'e80r2.R.lIcc53H-Yy/iZd!>Bi)Fch{u?KEx/za^N0JiOxMTOiP6b.`onNKa;vCd' );
define( 'SECURE_AUTH_SALT', ')DK6S3<N~~av+H6r{=yRCt6NQv>Z.34ebnqKDOeSt-n5ibT9$%+;LJUW)YpD3H?-' );
define( 'LOGGED_IN_SALT',   't#qhu.!e+=AB/qI hyzHS3[B[4ox?^Sx?qN<iN^HCtkn`|#p82J=x]<9j%&F4,x@' );
define( 'NONCE_SALT',       ':0$1z%xJn&`8V_R^P_l&T}# K.s{b^![kFyX%<>t2JYK?I-03YtCTis1hoPZ`T}1' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

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
define( 'WP_DEBUG', false);

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
