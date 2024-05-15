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
define( 'AUTH_KEY',          'FS,OE~Wf6[@~c-4Ba|y=ES^_}Oo+OJ,C||~aP/qz2`X3d{Pmm0=VO=~iy);+d `;' );
define( 'SECURE_AUTH_KEY',   'JHy7aZ=$x]N8`?[oj,gpjBY7ewq-L]UO?f0O/&[xE1T3Fk~EiS*piztH4}(k5IE(' );
define( 'LOGGED_IN_KEY',     'oFxA-GzX*q`-8#FLN0fu>@v~b*%FSg.NAPwLIex~D$Xj3j-J~AZSHK]kEJ!V4c/U' );
define( 'NONCE_KEY',         '#Al+4l-1u@nf1FMb1@Hi[i~{e[rtlt7|VGlMg_c?*.c>T!&KRW*ec?h&5L5B,(if' );
define( 'AUTH_SALT',         'bVL#Jr;yv$%m&}Qd*<*8^N %_w8ltcg%c]IW@VcMmmsC#>FB*5{fx_uqG_!z-RxM' );
define( 'SECURE_AUTH_SALT',  'M`4z.3SPyRFO=ea[Ox|E}hj^R#_I^^zFWL;0V-w0*<,{X!CY]Q6E_;!B$r2hv0L6' );
define( 'LOGGED_IN_SALT',    'CQX(f[SZw8cEa/w&}lBD4txpc?K!]Sw:X:uZd~f`&y[K%iKz0eSc.~lnIBxy]GL;' );
define( 'NONCE_SALT',        'o]|Ann_s~c,WhD3j_#ot~A^GS BW5<rICeKG@$<L|vOPSMT]c]z+WIk|igF5W6ha' );
define( 'WP_CACHE_KEY_SALT', 'hK, 14/jw3dlW$gOo=5nn)_<v}ppgPF)rB}&M6!ahNJq9wh(WnD`8ZLhmH11j>wK' );


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
