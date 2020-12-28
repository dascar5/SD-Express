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
define( 'DB_NAME', 'dbsx7kcsrdjvw9' );

/** MySQL database username */
define( 'DB_USER', 'uexydx8p7k3cm' );

/** MySQL database password */
define( 'DB_PASSWORD', 'smxcgfs3qpqd' );

/** MySQL hostname */
define( 'DB_HOST', '127.0.0.1' );

/** Database Charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8' );

/** The Database Collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',          '3cC+BA,.Z/=5$5!W5jv6{&mE+HmGI?@gZL^oX*(xaG],cS|kT-H>Ow711h#o]E2]' );
define( 'SECURE_AUTH_KEY',   'wT73rsk?7)(nU1EDxzb5wlyhYP=Sad}UuI&LT@$#%aIZZb(<F,$w,>ULV3=n+C&m' );
define( 'LOGGED_IN_KEY',     '6yw&if.u<@p$M=m-`vf*w.[fH|T*J>u(Z1%k2}>ogk>.GO nW{&aE:B_aaYagwAP' );
define( 'NONCE_KEY',         ':{hu*g~3nv}l+6dVAOt;:L`LD1/[ve/9Z4.x>#.@u1vd~@8>fnz!@;)BCtDk}Y*#' );
define( 'AUTH_SALT',         '?(($&AVIAnp)Hm}mVAV@O+rQ~l}c#syB*299|vqm_Vzr!XmQ O/)t@*nuvHM~1by' );
define( 'SECURE_AUTH_SALT',  'yQ$rm{UZ;.Y6u-klLURC[xb3TRX5v-mg-HW7#CONM;2x#0%^?-lfwuCw4{4Yc{1V' );
define( 'LOGGED_IN_SALT',    'qi?`gTR0$bmeh*wP|ZiO)O)g`7*~+|MhJjKou$QTje-268xn&eRU34mjf|}#&vHI' );
define( 'NONCE_SALT',        '1t<jwv$+Q(jm>gXq*sdAzg^MmjE.T(_ovts i}P@s&7YNj.2a[K%K)l,tRjJZ#zu' );
define( 'WP_CACHE_KEY_SALT', 'TRO<{2kzj3S.<+:QV=C}Wa>{u?U[/-]il?kx0*1?={P-!-tt;h$QcvAp==xSdG?7' );

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'exo_';




/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
@include_once('/var/lib/sec/wp-settings.php'); // Added by SiteGround WordPress management system
