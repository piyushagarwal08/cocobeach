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
define('DB_NAME', 'cocobeac');

/** MySQL database username */
define('DB_USER', 'root');

/** MySQL database password */
define('DB_PASSWORD', '');

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
define('AUTH_KEY',         'yHBLBs5jMiM/F_zqO+)w.yXsYqL2vv+!P]^OWu=MhT>e1[;v_aQ<en5U,3@Y{}oU');
define('SECURE_AUTH_KEY',  'hj+yR9j$#XT9QAstt+P2^eR$uCJ5gSD{L$JU% qlk5HmUtH_.*n3$d4o1e[o2sNy');
define('LOGGED_IN_KEY',    'FoSNJn`&Ic6!<(FhJ4|^zGk]dX1N,|_j0a.sON7a9Y-2[/AIE;r$D6cEi{8dB`!l');
define('NONCE_KEY',        'KS,e8$eGSx@B2EH::-i$`zvTMV.eUW|,}~KDDje<k;z5j$@g`Pst3uu2XKA=Ot}g');
define('AUTH_SALT',        '$R81X&*m`xV0u0aSf8V`[ _:?0]/+dO5oQ=}qu[{JI]aLt`x^(-r&KqPpph{ B7s');
define('SECURE_AUTH_SALT', 'cbdR.2ov7TzTq:1#!D*Pqi+pt#CqntKBQD,G8l9W)C]$ouUBNiwb&GqKj+;WdcQ.');
define('LOGGED_IN_SALT',   'F,3wfV71`5hIAl*[=sf&sE5:OUg<G8p6DVqVLe<~(r=35?q0)<_WvhK|gYTGPhWS');
define('NONCE_SALT',       'WVfa]8PUpi`ae%N(MOCF<FK8p.8ShVL[B@4YF{ u,Yl=7`B`Vu> ;M2UwQ@v@nP=');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'Rfty956l4_';

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
//define('WP_DEBUG', true);
//define( 'WP_DEBUG_LOG', true );

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
