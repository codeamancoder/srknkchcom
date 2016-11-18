<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL ayarları - Bu bilgileri sunucunuzdan alabilirsiniz ** //
/** WordPress için kullanılacak veritabanının adı */
define('DB_NAME', 'srkn_srknkcc');

/** MySQL veritabanı kullanıcısı */
define('DB_USER', 'srkn_srknkcc');

/** MySQL veritabanı parolası */
define('DB_PASSWORD', 'ryfXmHGL*34#');

/** MySQL sunucusu */
define('DB_HOST', 'localhost');

/** Yaratılacak tablolar için veritabanı karakter seti. */
define('DB_CHARSET', 'utf8');

/** Veritabanı karşılaştırma tipi. Herhangi bir şüpheniz varsa bu değeri değiştirmeyin. */
define('DB_COLLATE', '');

/**#@+
 * Eşsiz doğrulama anahtarları.
 *
 * Her anahtar farklı bir karakter kümesi olmalı!
 * {@link http://api.wordpress.org/secret-key/1.1/salt WordPress.org secret-key service} servisini kullanarak yaratabilirsiniz.
 * Çerezleri geçersiz kılmak için istediğiniz zaman bu değerleri değiştirebilirsiniz. Bu tüm kullanıcıların tekrar giriş yapmasını gerektirecektir.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '$+~u`/X-@2N[rt^3bI(cWh_T}%k.mCZ]L0Ki5OM.J^=d y;^;Aydl-V|/ZdYVP&<');
define('SECURE_AUTH_KEY',  'SgxJ0mPg4+7NXS5U$KacFN7s#,%3Jx|Z0H=LdRgL7&Hs0[Vyvy*7bsA7m<Ve_e* ');
define('LOGGED_IN_KEY',    'S}JsuvRy%O5x{d}X|2/:jo.FC;OY2acPvL+Kx;Jc2,f#yu-4d-8<{J7@|[x:T}1m');
define('NONCE_KEY',        '7=GO8+w1o?I;r><!AH3&f}1}pv*>m-5ju%G*1N?Xkm@PxS+(?a*WpnSvwh;%QH/i');
define('AUTH_SALT',        ':]6SNTR%24w`r-%ST)b~]W?8%VImt&GmxiS=%cXY8j2,^p*i(RXibqp)>#o_xzZx');
define('SECURE_AUTH_SALT', ']~D-:c`)Q;j}QhL(*rPi}h+,;B#8%^qrfGVGRYXaK#ZV/[EznMv8VjDCLTjC&l2S');
define('LOGGED_IN_SALT',   '$hyg)S=+[NeL/=2B.HthD{3!_gq{bccM**ZDWRIQ%xLOu4zH}=|2%[:~R.<JAW T');
define('NONCE_SALT',       'q(}T*SsMo>B ~|JXBBc_F7b9$U!V6v^,Kt![Np3|x/>&K?JM~#qf.Vun;@6*^0_>');
/**#@-*/

/**
 * WordPress veritabanı tablo ön eki.
 *
 * Tüm kurulumlara ayrı bir önek vererek bir veritabanına birden fazla kurulum yapabilirsiniz.
 * Sadece rakamlar, harfler ve alt çizgi lütfen.
 */
$table_prefix  = 'wp_';

/**
 * WordPress yerel dil dosyası, varsayılan ingilizce.
 *
 * Bu değeri değiştirmenize gerek yok! Zaten Türkçe'ye ayarlı.
 * tr_TR.mo Türkçe dil dosyasının wp-content/languages dizini altında olduğundan emin olun.
 * Türkçe çeviri hakkında öneri ve eleştirilerinizi iletisim@wordpress-tr.com adresine iletebilirsiniz.
 *
 */
define('WPLANG', 'tr_TR');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/* Hepsi bu kadar. Mutlu bloglamalar! */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
