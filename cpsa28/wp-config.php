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
define('DB_NAME', 'cpsa28');

/** Database username */
define('DB_USER', 'root');

/** Database password */
define('DB_PASSWORD', 'root');

/** Database hostname */
define('DB_HOST', 'localhost');

/** Database charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

/** The database collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

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
define('AUTH_KEY',         '1CzndjIcbL|d|TxAj`kxS>g3r#5`ZB2RaEtf/&#zVy[0n@PhtJ%}r6NSf[gSP:&!');
define('SECURE_AUTH_KEY',  '%i jxg3T~5Y2;%U8tI@Bn0CB,0]_N!&pcaQ)SnaTSDM4SqRI|Sz@);j@r7EydY*a');
define('LOGGED_IN_KEY',    '!(#&0+> >rga/#{!UTSS,@,B[1d$@$p^{YmM vg a0h3p$T>Y%JG.av).,2 `J0|');
define('NONCE_KEY',        '>mTO-K5v+zO[>yp_>78{i[%PSOq)zJ(@!p3C3*I0OO<N>bW<0F]]dD/[#PqYPU, ');
define('AUTH_SALT',        '}4.dVv@8TtsCsX7RI[9t[`ZJ~;iW9,Y:U00UXLeVsA%)e*gRV|r2jr4`0?;>)NkJ');
define('SECURE_AUTH_SALT', 'WstiSc?8$#?B*EASCZ5nwX#).CdaGyj|:OH_`ckO(a$v.H*$b!19bP}?t31vRiyh');
define('LOGGED_IN_SALT',   'GJ6@^YMad49)hT:y%.W6_2H70LkuI{#9ocH^2)6d~#][?h2mt2r.=D?-W-GITouU');
define('NONCE_SALT',       '5[pv}9dG4&GKyY=2OWWuoHS`v2W@(u8}B9i>l{&D w%5Z nfvT&Jiu}Mk1m>1.}D');

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
define('WP_DEBUG', false);
define('WP_DEBUG_DISPLAY', false);

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if (! defined('ABSPATH')) {
	define('ABSPATH', __DIR__ . '/');
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
