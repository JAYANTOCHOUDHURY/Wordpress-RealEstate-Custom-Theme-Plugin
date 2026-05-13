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
define( 'AUTH_KEY',         'ogRSAz/ u_+Z>aKi`LqXwx!G6U.>AS3eMZ2}*E3T4By]FnE~KKEb?LaWBCmdjyNx' );
define( 'SECURE_AUTH_KEY',  '=8$cEg(7WU5/BauDpd2hT1=R95Z7jSSdNJyr;]VApv<D2N:U?ilzsXZu;|sp-BKU' );
define( 'LOGGED_IN_KEY',    'GOJ6dl<P3qx.gRwZw#&3d32~yx+&Lgf$#QRx<WxFP`.>?Q>:9A7wpKz)]%*<$~qp' );
define( 'NONCE_KEY',        'uYqQPfhiypY>.z5YY#ZXUZ{K4}oSP#H5;B1%5{HV0,GC8Nup5Z%|y8pbo5?ylj2<' );
define( 'AUTH_SALT',        'm!q;I;/e6*RyBUerHohBw $Nq8R*8VQy~7` |>6eG>IkNQEG3u7^cdpd=mmQ$V;M' );
define( 'SECURE_AUTH_SALT', 'nVP||ec{CmYGDRa(&,pckP`mY3j4KKtZ+s_(=&*t5!BwaZzY=|vLdQG8bJ=er3<+' );
define( 'LOGGED_IN_SALT',   'Qk5#6G:J7lnd@(w_V># #09apal0Iq6mEjZE7ejo>ovm6.>%jlz=~N1U>u4VW@Lq' );
define( 'NONCE_SALT',       'RaYn?bI.fVD}XH>f^WsPo}|L?^a;K?9fxu?G3{yS+T7~k|x$?l=b)!x=]uS}c!aB' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 *
 * At the installation time, database tables are created with the specified prefix.
 * Changing this value after WordPress is installed will make your site think
 * it has not been installed.
 *
 * @link https://developer.wordpress.org/advanced-administration/wordpress/wp-config/#table-prefix
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
