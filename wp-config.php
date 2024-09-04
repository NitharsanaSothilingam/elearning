<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the website, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'e-learning' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

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
define( 'AUTH_KEY',         '}p ${6E#M5?omn|b =RmB&UiK;GY$ ?)1:*({AaH2vEs4i@1[zHhUL$<[VpSHoxv' );
define( 'SECURE_AUTH_KEY',  '38[PW,Qzx|6Yhk)Vo?&9wd8&ud&XycNFr!wjF&xJmRfy-F$s=0fa@*mQXsHPi!]i' );
define( 'LOGGED_IN_KEY',    'nKnOp9`E!+fM(WDsl6$#9`cE]@0D?aWK{|oy;DXHweIKc(uQviMJ%ul,w~5.5.B.' );
define( 'NONCE_KEY',        'uCo2[S3/:SY5S[WA3~26eI}f`mG~sox=#;d3_Ge?g8ijDVNY D=DLR<DN9F*GYhQ' );
define( 'AUTH_SALT',        'P7YK`dWc!IPUM_59YT$i$aOC]5W)(KdJ`8!BN:b<ox#eJ?;2<[K8+&byako@J9LU' );
define( 'SECURE_AUTH_SALT', '#gd1[0m?rhB[H3jV.D1N9;+d-hS_l}M i{uemp>l>|qsTl@^lafJ_FxjBiN(o3gP' );
define( 'LOGGED_IN_SALT',   'U<61(!~Y.3g!UQCp0;T6)!!OL85SFOU%PDod-a-ZC;WaJjWE]w?QWw/&}ff/c(f[' );
define( 'NONCE_SALT',       'p;~YnjJ{Bl0[MVNkQrD5x]KTO->,D:1@8@[_Z1]2foby8(_NvCY/vG{X0vJi%|`R' );

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
 * @link https://developer.wordpress.org/advanced-administration/debug/debug-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
