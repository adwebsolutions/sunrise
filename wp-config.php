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
define('DB_NAME', 'sunrise');

/** MySQL database username */
define('DB_USER', 'sunrise');

/** MySQL database password */
define('DB_PASSWORD', 'sunrise');

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
define('AUTH_KEY',         'Hx0;QD3MhV^yP`-1ZNX1{$zumxppY?[u7|TU@ PDvv*bpYC69t8-db0+yukL(tEb');
define('SECURE_AUTH_KEY',  ',-y$<NzS1x#7etXsFnmgQ-0%S(9`e*+YS%3Z)?9g|q0%X@mAXqa[|` l8PZN#P5I');
define('LOGGED_IN_KEY',    'bk4PrL>3<i5>>Vk9;f0sdijIQybCfA]ImGcRBwhhH!zZY2ZAe/z~[^fQ?yc;9)b]');
define('NONCE_KEY',        'UeT[)4sdKYQUa3o*U!(iTq-94E73H]K:uspFY{4C5PGTCfN6$~LAs:<((EY0]QYO');
define('AUTH_SALT',        'F|6)x+Z?TX.Ok@/46X?hLm-age(G3Ux:?]M}xLE QqIdIlgr=Rj0&6!toE<TyG%|');
define('SECURE_AUTH_SALT', 'soXf{Dg:CT n~dzRJ[BORyW*J1PJGQ^9wyk!O+eXwR#7rSF2`uX6Z~]r=q;%z)1 ');
define('LOGGED_IN_SALT',   'kW)k@J*.7M{L pt-@eH]%yz_G2)KP3/NkBZPdBu1?5sX__eO+,p~5;`$FL(@C=*S');
define('NONCE_SALT',       '}y><2k7{Kqf~(d!e7DdQ-hGS WyVzY+p:Lr>+A2M -<Dg3#!;eRnIuJc Fmx-XZI');

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
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

define('WP_MEMORY_LIMIT', '128M');

define('WPLANG', 'es_MX');
