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
define('DB_NAME', 'Wordpress');

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
define('AUTH_KEY',         '!M~mV&f[}(bQd|~t%^Ek/ZPw?XO29 -_J1T09LJxh1Y>Q|XJy#OxerqF6FaENqPn');
define('SECURE_AUTH_KEY',  '*?Xz`*1ny|+~PcNEd/;mSX7a_I2CW3anhVcM?(#zrPkp?q$NJjieES+IlAu<KZwd');
define('LOGGED_IN_KEY',    'o{I`]Os[f7mg~vYW#4X T#;[+4kB*!R})0hW3|P&4m3mK}MguD)g+oT}e#C(w_I%');
define('NONCE_KEY',        'A*0h;b=c8 *|)`V1vcmGhrDCL6[[;c)G!5$M(c~.`; hd!4a>+ G4e.6uQ&|(4~E');
define('AUTH_SALT',        'W3`.aPG:+N[Q&UpmIhnLzxH|maGRF?C@;7TUn)9?8kH.F*(ePX|n%?-SomYX^6{z');
define('SECURE_AUTH_SALT', 'wAJY8c_=$93WwZ`[kAwQin1UP- ic)rVX1RwR{FFToC,wF4+`c@m`+~xPfHsE0p4');
define('LOGGED_IN_SALT',   's(-6 t9h]3Jilf}1u p3NAlWpq(cAK-2iSYU*p(zu!&GC4v=IC;GfFtSci[2Ga/6');
define('NONCE_SALT',       'Uo8^&i5*]%bvXA!RtRd:sJpJtqc,?L)78}F.N+7Qfg#-G? ,|U37uZnjz=#mEGjk');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'club30plus';

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
