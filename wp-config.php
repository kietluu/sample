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
define('DB_NAME', 'question_bank');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'admin123456');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8mb4');

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
define('AUTH_KEY',         'zv#A~$K{R+7PG??K+ZK|dN>g+]k=N)92~rjiY&K45OCD$[w+:_bm 30I1-lgDo)d');
define('SECURE_AUTH_KEY',  '.#+rI6|G@S4~nEim;R%/r9qS?fIk~%6+X@aM< T9&*{byDp8rG:pn;1kW<=ejx.k');
define('LOGGED_IN_KEY',    ' W$k?gBk=q/cqe+B9i1n}sg`ju1VV$f!gLX/<J*>Y#C&C%9k]ULRl_J[}RF4*a]#');
define('NONCE_KEY',        'l`oXqL|8I8_1s}rSs]GSJz66C3-bVni@TeFbArms}_)I=J)*J:u3EfBiRHh.>t!P');
define('AUTH_SALT',        'UhSP+a=k+)>1l;q{jDT^ bpPbt}#O01Hrt]WR@|<gyJmLjL|`.-|rywFeZ)-oH8=');
define('SECURE_AUTH_SALT', '/q/!QgDq z|oo..Ubw:P Jz/H^[q[?(.$w} =s?1:$i){,%!gx$DC/K.su/!0@jl');
define('LOGGED_IN_SALT',   'XRmk?_RWpd:^_rlYW(CM64?6xZrt>g1mI5iZ]Rb<oh;Adbqoj.9Az&X>uykW`9^3');
define('NONCE_SALT',       'b#+fxgv0q!q7-68?c @_n_N=qBAhhCS{f@XCxtg!=WvDgXW~g/+,yRP14J$|bgs`');

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
define('WP_DEBUG', true);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
