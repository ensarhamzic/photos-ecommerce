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
 * * ABSPATH
 *
 * @link https://wordpress.org/documentation/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'defaultdb' );

/** Database username */
define( 'DB_USER', 'avnadmin' );

/** Database password */
define( 'DB_PASSWORD', 'AVNS_wyBAFFlxmeLKGvU8txs' );

/** Database hostname */
define( 'DB_HOST', 'mysql-24538f9b-photos-ecommerce.a.aivencloud.com:14643' );

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
define('AUTH_KEY',         'hcg>cxuxgchNpr_YjK+UB]s.--Y?j}]!{g5Xa*@Ps9rglMx7pUkv;YoR,Ad=hTVo');
define('SECURE_AUTH_KEY',  '-%Hv+|O9S$A~Me0<;~1X{];]lXm>v#m8a(p|K30b% GI+@z?wTf,cpieB-KlD@5T');
define('LOGGED_IN_KEY',    'BGSk83@Su{Kl!W__KxVJWjwdQTdR2U&yS|zj]|-~)G$G^-J/yEI3jl@AK>w&@151');
define('NONCE_KEY',        'Qwtn.T]j`-F>lUQu9g6.~zNnlLphz6|*q^%YxaUQf;F?QhdDRhW<>C<b%=a)_mA~');
define('AUTH_SALT',        'DR+m0EI<yN9F6vgaD3oB9o>L8-ZhpYr.!k qS;b$/YzY:2t|g2iXPN# b w@<UNo');
define('SECURE_AUTH_SALT', '8N,X|SZd>S!Dj{o xuz^i4LvpADVXmTBN)s. `:6%5MQPIz~6Oe${bZc6pr{xb]~');
define('LOGGED_IN_SALT',   'W:!-Dh+m#Jgz//@*)0;(_8x1~-,d4-uG>T/ZoFkHKlCU mM4%u@0#6;f-`abV9]1');
define('NONCE_SALT',       '{t^rSP8&-sV-idpt>3QEUi]^[<uMJ6U1O6Y/ap7;L]#-VeDGeSGus^fy:f<36b +');

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
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
 * @link https://wordpress.org/documentation/article/debugging-in-wordpress/
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
