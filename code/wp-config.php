<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, and ABSPATH. You can find more information by visiting
 * {@link https://codex.wordpress.org/Editing_wp-config.php Editing wp-config.php}
 * Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'consultl_wp360');

/** MySQL database username */
define('DB_USER', 'consultl_wp360');

/** MySQL database password */
define('DB_PASSWORD', '4[U3SP-P46');

/** MySQL hostname */
define('DB_HOST', 'localhost');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

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
define('AUTH_KEY',         'sjhl16fd5vbdcrc09vocwyfps6daga7hktrnkmkikdgrjra7rt0cifadvundqn2m');
define('SECURE_AUTH_KEY',  'zmruapkyzaomsizgqlfx3aoxzmza0eirtkmlqhopm4ttbo8ktmzmmkfm5dzbvlhx');
define('LOGGED_IN_KEY',    'uty6m0aonase95wodjqgerghjhxjnh1bqlgyak6xy9lnw0oi9x9wfg7y1kayjye3');
define('NONCE_KEY',        'vopys5r6wjlizq7tnajcldnqxdbkzyiqt9qw2eczmk8r1d3hdrudnjzeh0pdtbvr');
define('AUTH_SALT',        'brzfvijhvztvxytzqjvwqakqet3pwfqrv4spumhezmknlgqotpavrjhymropwea2');
define('SECURE_AUTH_SALT', 'mqimz3udddum4icpgntrzqcrjxyvxshayfyp2cn1ics8ulrmas4ke0ocyw6bchm4');
define('LOGGED_IN_SALT',   '24hywjq5g0s2wdtzhvkdgb5zko0dj6hptex8jlb6glpjvmkdo7xthwsbakird49g');
define('NONCE_SALT',       '8i42zab8qllwywlxmtxuhsa4oaj3r0pejfjkjzzsrkx1zow6k7sujr72kv83nzjs');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
