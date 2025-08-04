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
 * * Localized language
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'local' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', 'root' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

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
define( 'AUTH_KEY',          '-?Rf)v+HP9Q+g-xnH00YR2KjPf;(%VgUE5$/D%pHQCC[iDSv>$l%[P>+3!Am([*v' );
define( 'SECURE_AUTH_KEY',   'OjGl1.`7+F!Y6v(afl{KSOB^og:&anM]iQda[[>OJrYgw>Q}%^Rh!&R}X^.${;v3' );
define( 'LOGGED_IN_KEY',     '<;wCAm[I]4.7HJ>9se4S!R#i73O@,rFQnmQzpb5v>eA.v>A%~=Pw.!:ocqW7K)Bw' );
define( 'NONCE_KEY',         '5.>tM^ ~i?BJ#uuFB(lRd#KZj1NBRN2^E*rT=lT9q.|Ev}SCKeqU)GP8aLTOu(l#' );
define( 'AUTH_SALT',         'q,$*H#ve`iNX@ctTxfE$?jy[W&8S7IPa*w)q86HqTOZ&Mc9.:vKD~@h{?< fsCe}' );
define( 'SECURE_AUTH_SALT',  '79+c{|CuTTP0^)(6xj(1=RV s^;%LAW|g|@DsR9H)$;5ZQ=>B*&)?nGq0$ohm<gk' );
define( 'LOGGED_IN_SALT',    '_:-{f>1CNV`5a]GH*]Upo/5.sHa$N%%+m=}++f<P)`eU6wV:@ 7@]k%/yE-O=;=/' );
define( 'NONCE_SALT',        'M`;iVMQ-N^<W3xtK`%p|%tNY0=Vt~2f<kA`B~0i_b78bd3YOdDD]Qw>E[=iW1*O}' );
define( 'WP_CACHE_KEY_SALT', 'qL_6uWfIQ)9I`R6p4O_Fe|d>3k8XB1<..mn~+8F+ h/g+o+yO>l-/O5N0>YQgLr]' );


/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';


/* Add any custom values between this line and the "stop editing" line. */



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
if ( ! defined( 'WP_DEBUG' ) ) {
	define( 'WP_DEBUG', false );
}

define( 'WP_ENVIRONMENT_TYPE', 'local' );
/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
