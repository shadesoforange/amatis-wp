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
define('DB_NAME', 'amatis-wp');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', 'oranges83');

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
define('AUTH_KEY',         'Dl`5:anwq>}xj{_`rf37E7PI3^w~fhlX$_y,joOcb#|b+#HEpOIn2|)U$eqHwX7Q');
define('SECURE_AUTH_KEY',  '.Hd|j`QE{E/C:FwX~2d>jQ CVq;7xT<WIBvPq,tX#BSroFZW?~T:nwaP9D]!t;1!');
define('LOGGED_IN_KEY',    '&[Vqg;U@|DHssmu^zj94%W81_}aT`Cfo$1Q8pIjSES+Fw|mgBXT!Bsed28t4r:Tz');
define('NONCE_KEY',        '/(NradGtMaA]>|>4I?any4fL2J~Z%ap*#Aeobf-0f~kuC|z5@.@|4xCxmIt<&JWx');
define('AUTH_SALT',        '8&^&4YWi&W5&oGuU};@zwSEm|j(ZXszaP1Sj]i2IZ=!CD OTZp<tye`,BR)t*;E?');
define('SECURE_AUTH_SALT', '@}R48:UruUP_teWHv+tAU%}h^ gm-,JVa92Kynu0CUC9]v7VZS@%|:,~C2%sc(!s');
define('LOGGED_IN_SALT',   ':Y9MEnp`J%sfDy(2BhCC}zRY~Rg>gvL-Ih/E+ht5`H)<y7Y0pZIL#60m/}r[=U+.');
define('NONCE_SALT',       'PodT{o|Jvs@AaZ:J2xx8t&`<H]!5h !Thzt;u-.6M ~1N.gZff]*4m$@5h>}d<>x');

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
