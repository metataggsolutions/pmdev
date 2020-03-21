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

// ** MySQL settings ** //
/** The name of the database for WordPress */
define('WP_CACHE', true);
define( 'WPCACHEHOME', '/home1/ab9857/dev.metatagg.ca/pmdev/wp-content/plugins/wp-super-cache/' );
define( 'DB_NAME', 'ab9857_pmdevdb' );

/** MySQL database username */
define( 'DB_USER', 'ab9857_pmdevusr' );

/** MySQL database password */
define( 'DB_PASSWORD', 'H0!XD?mgNOMH' );

/** MySQL hostname */
define( 'DB_HOST', 'localhost' );

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
define('AUTH_KEY',         'MpX=$d 3[!&GIlw+r|HiC&>&tTPA[ZhP[V#-4Y~I}hb?Z4wb0ug:wT^0fer]oy07');
define('SECURE_AUTH_KEY',  'kNSKv_qds<^},m_&/Y]ZCN~Go(f;_#m-{E++V(NjjR|-@U1^n7d={2*b#[?~c<a{');
define('LOGGED_IN_KEY',    'yhy21-H<;U;;)cCXarGz+~,mT}mE=BzA&tQ2q$jD3B08Eq$<v)^%WFx_;`8=y3h5');
define('NONCE_KEY',        '$1vZm|!|INy$:!J&opI2o+cEu)&:L yG*;ZhO9Z|f-4/6%-wWlF_E@u]+0+Hu0,U');
define('AUTH_SALT',        'cdLnTwbNe<]kw!f8>;LIAs`Qvh(:$ZZ^x?Y&|mm!LmcTwsjLeCz&-D@|7TWT4-7h');
define('SECURE_AUTH_SALT', 'Fb90n5ISnz:j:cVl3Y%;G{vIm=P}Q`|}nb~>N2-A/y-5p0lhSh0`j~5Q4:)+:H*G');
define('LOGGED_IN_SALT',   'WB!Ps*gwE;H/{&oFTq+WcGwVIj~7`4pK,zj=DOZz+I3QfA{C&_.qe|C>sR~1r,jl');
define('NONCE_SALT',       '**^D{|6KiwUV5/D0Z7Rjb+yjCGZ w(u@R7cr>]<v,$|h/;V%Pg+&Z-+.|V+vXZS#');

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';




/* That's all, stop editing! Happy blogging. */
define( 'WP_DEBUG', false );
define('WP_DEBUG_LOG', false);
define('WP_DEBUG_DISPLAY', false);
@ini_set('display_errors', 0);
/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) )
	define( 'ABSPATH', dirname( __FILE__ ) . '/' );

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
