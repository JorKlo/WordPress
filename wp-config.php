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
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'wordpress' );

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
define( 'AUTH_KEY',         'NgFUK_CQ7Q[Vp#+} o{LLHP^E0}}RLDCCPQYkSR`CTh4eHH-GXXDH7u{{~|xe,.l' );
define( 'SECURE_AUTH_KEY',  'B@N[dPF[Emj-9ar>P)>[re8hn`/^$-U,hGUfGL>zheOz/H2w}Rz.]*T RXl0HHOr' );
define( 'LOGGED_IN_KEY',    'BW|BLXqL9f4e@N(CCw_C}-TTR*~o` !{dmR+~QML`q7iDg!n$K*_,ZG:?R+EE8X ' );
define( 'NONCE_KEY',        'd>MR.<M>Z*MwBoX;y6GOwz&o)?N$4Ot%7H*i(O)yiJk&G[HCE4*MSO4A#j5v0Vtq' );
define( 'AUTH_SALT',        '3bQ$YHa3>Q@g(,9Qy,]EBtvE$<?8.O5fjaq$MbBh65XeDHIAx3lV@0#Bj>C`P>4}' );
define( 'SECURE_AUTH_SALT', '`^$e(70xuu;d%De6p*3m%1z:7+-LY|T:jXIY!ulJGu_-q0jc)vYOg3%9y3}T4yfb' );
define( 'LOGGED_IN_SALT',   'auS@tcm[w<K2@8o@[_CDCXD`q,Dt^k[.?^WNfWU]mj@+ biPNea`pTb=*c4gY&-A' );
define( 'NONCE_SALT',       '5,$Z_29p[pm%4$INnwR:aV;X5*T./k%DC MV~;u7AD1T0h~E-LQ$mm:wYd!@VJ&J' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_local_';

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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
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
