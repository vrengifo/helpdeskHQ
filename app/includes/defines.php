<?PHP
//include static defines
  include_once(dirname(__FILE__).'/defines_static.php');
  require_once(dirname(__FILE__).'/my_defines.php');


//type of database.
//access = microsoft access
//ado = generic ado
//ado_access = access using ado
//vfp = visual foxpro
//ibase = interbase
//mssql = microsoft sql
//mysql = mysql
//mysqlt =  mysql with transaction support
//oci8 = oracle 8
//odbc = generic odbc
//oracle = oracle 7 or 8
//postgres = postgreSQL (experimental)
//sybase = sybase (experimental)
  //define('DB_TYPE', 'oci8');
  define('DB_TYPE', 'mysql');

  //ADODB_CACHE_DIR needs to be set to a path that exists,
  // the web server has read/write access to, and
  // should not be viewable to others
  if (file_exists('/tmp/session')) {
      $ADODB_CACHE_DIR='/tmp/session';
  } elseif(file_exists('/windows/temp')) {
      $ADODB_CACHE_DIR='/windows/temp';
  } elseif(file_exists('/temp')) {
      $ADODB_CACHE_DIR='/temp';
  };
  
  
// *****************************
// * Menu section
// *****************************

//path to ttf fonts for dynamically generated images.
  define('FONT_PATH', dirname(__FILE__).'/../utilities/fonts/');

//font to use for dynamically generated menu buttons
// define('MENU_FONT','ARIAL.TTF');

//initial font color dynamically generated menu buttons
//  define('MENU_COLOR1','000000');

//rollover font color dynamically generated menu buttons
//  define('MENU_COLOR2','339933');

/*  define('SOFTWARE_SHOW_AP', '1');
  define('SOFTWARE_SHOW_AR', '1');
  define('SOFTWARE_SHOW_PAYROLL', '1');
  define('SOFTWARE_SHOW_INVENTORY', '1');
  define('SOFTWARE_SHOW_INVENTORY_PO', '1');
  define('SOFTWARE_SHOW_GENERAL_LEDGER', '1');
  define('SOFTWARE_SHOW_DOCMGMT', '1');
  define('SOFTWARE_SHOW_MESSAGE', '1');
  define('SOFTWARE_SHOW_FIXED_ASSETS', '0');
  define('SOFTWARE_SHOW_PRINT_MANAGEMENT', '0');
  define('SOFTWARE_SHOW_PRINT_MANAGEMENT_IMP', '0');
  define('SOFTWARE_SHOW_PRINT_MANAGEMENT_CUSTOM_CBL', '0');
//show menu items as images
  define('MENU_SHOW_AS_IMAGE', '0');
//show explain items
  define('EXPLAIN_SHOW_AS_IMAGE', '0');
//show icons on explain* submenus
  define('EXPLAIN_SHOW_PICTURES', '0');
*/
  
// *****************************
// * Form behavior section
// *****************************

//whether to highlight active fields in forms.
  define('FIELD_HIGHLIGHT', '1');

//the colors to highlight with for FIELD_HIGHLIGHT.  You _WANT_ offcolor to be the same as the normal textbox background color.
  define('FIELD_HIGHLIGHT_ON_COLOR', '#E7EEF5');
  define('FIELD_HIGHLIGHT_OFF_COLOR', '#FFFFFF');

//whether enter key tabs to next field or submits form.
  define('FIELD_TAB', '1');

//whether text boxes have their contents selected when the field is highlighted.
  define('FIELD_AUTO_SELECT', '1');


// *****************************
// * AR section
// *****************************

/*// definition for max number of sales tax rates for one customer
  define('MAX_CUSTOMER_SALESTAX', '3');

//starting ar order number
  define('AR_ORDERNUMBER_START', '1');

//AR Order due date modification. Normally the due date defaults to the current date.  If you would like to set it 2 days in the future, define this as '+2'
  define('AR_ORDER_DUEDATE_MOD', '1');

//whether to lookup and show on hand qty's on order entry
  define('AR_ORDER_SHOW_ONHAND_QTY', '1');

//the default answer to whether to specify shipping weight and cost per package
  define('AR_ORDER_SHIP_WEIGHT_PER_PACKAGE', '1');
*/

// *****************************
// * Inventory section
// *****************************

/*//default categoryid for documents autoinserted into document manager when adding an item
  define('INV_DOCMGMT_DEF_CATEGORY', '1');


// *****************************
// * Estimating section
// *****************************

//whether or not to show certain inventory types
  define('SOFTWARE_SHOW_PRINT_MANAGEMENT_OFFSET_SHEET', '0');
  define('SOFTWARE_SHOW_PRINT_MANAGEMENT_OFFSET_WEB', '0');
  define('SOFTWARE_SHOW_PRINT_MANAGEMENT_DIGITAL', '0');
  define('SOFTWARE_SHOW_PRINT_MANAGEMENT_SCREEN', '0');
  define('SOFTWARE_SHOW_PRINT_MANAGEMENT_FLEXO', '0');
  define('SOFTWARE_SHOW_PRINT_MANAGEMENT_VENDED', '0');
//number of quantities for quote
  define('EST_QUOTE_QTY', '5');
//subject of quote email sent to customer
  define('EST_QUOTE_EMAIL_SUBJECT', 'Here is the quote information you requested');
//addl text to be emailed to customer above quote information
  define('EST_QUOTE_EMAIL_BODY1', '');
//addl text to be emailed to customer below quote information
  define('EST_QUOTE_EMAIL_BODY2', '');
*/

// *****************************
// * Barcode section
// *****************************
/*
//various defaults for the barcode class
  define('BARCODE_IMAGE_TYPE', 'png');
  define('BARCODE_CODE_TYPE', 'C39');
//a width of 325 will allow barcodes of up to 20 characters.  If all of your item codes will be shorter than this, you can decrease this value to increase readability.
  define('BARCODE_IMAGE_WIDTH', '325');
  define('BARCODE_IMAGE_HEIGHT', '50');
  define('BARCODE_IMAGE_XRES', '1');
  define('BARCODE_IMAGE_FONT', '1');

//whether to show item barcodes on shipping tickets.  Requires GD library to work.
  define('SHOW_BARCODES_ON_SHIPTICKET', '0');
*/

// *****************************
// * Login section
// *****************************
/*
//whether to allow internal users to login to this site
  define('ALLOW_LOGIN_INTERNAL', '1');

//whether to allow external customers to login to this site
  define('ALLOW_LOGIN_CUSTOMER', '1');

//whether to allow external vendors to login to this site
  define('ALLOW_LOGIN_VENDOR', '1');

//whether logins are case-sensitive.  Default 0, is case-sensitive
  define('LOGIN_CASE_INSENSITIVE', '0');
*/

// *****************************
// * Icon Display section
// *****************************
/*
//this is the icon shown for the customer lookup pop up window.  This can be any URL, absolute or relative.
  define('IMAGE_CUSTOMER_LOOKUP', 'images/lookupcust.png');

//this is the icon shown for the customer add pop up window.  This can be any URL, absolute or relative.
  define('IMAGE_CUSTOMER_ADD', 'images/addcust.png');

//this is the icon shown for the customer add pop up window.  This can be any URL, absolute or relative.
  define('IMAGE_CUSTOMER_EDIT', 'images/edit.gif');

//this is the icon shown for the customer lookup pop up window.  This can be any URL, absolute or relative.
  define('IMAGE_VENDOR_LOOKUP', 'images/lookupvend.png');

//this is the icon shown for the customer add pop up window.  This can be any URL, absolute or relative.
  define('IMAGE_VENDOR_ADD', 'images/addvend.png');

//this is the icon shown for the item lookup pop up window.  This can be any URL, absolute or relative.
  define('IMAGE_ITEM_LOOKUP', 'images/lookupitem.png');

//this is the icon shown for the item add pop up window.  This can be any URL, absolute or relative.
  define('IMAGE_ITEM_ADD', 'images/additem.png');

//this is the icon shown for the date lookup pop up window.  This can be any URL, absolute or relative.
  define('IMAGE_DATE_LOOKUP', 'images/calendar.gif');
*/

// *****************************
// * Shopping Cart section
// *****************************
/*
//shopping cart site shows items from which company (0 for all)
  define('SHOP_CART_COMPANY_ID', '0');

//shopping cart site default price level
  define('SHOP_CART_PRICE_LEVEL', '1');

//shopping cart site default quantity
  define('SHOP_CART_QUANTITY', '1');

//surcharge added to all shipping rates.  set to -1.06 by default, to correct ups's errored returns
  define('SHIPPING_SURCHARGE', '-1.06');
*/

// *****************************
// * General Preferences section
// *****************************

//language to use by default
  define('DEFAULT_LANG', SD_ENGLISH);
  
//whether to show pop up tool tips.
  define('SHOW_TOOLTIPS', '1');

//automatically open print dialog box for printable format pages
  define('PRINT_AUTO_POPUP', '0');

//Directory for uploaded inventory image files.  This needs a trailing slash.  It can be a relative (to the web dir) or absolute path.  Your web server must have full permission to this directory.
  define('IMAGE_UPLOAD_DIR', 'uploads/');

//maximum upload size for inventory images, in bytes.  This cannot exceed the maximum defined in php.ini
  define('IMAGE_UPLOAD_SIZE_MAX', '1000000');

//whether to show item images (if they exist) at various places in the software.
  define('IMAGE_SHOW', '1');

//whether to be in demo mode.
  define('DEMO_MODE', '0');

//currency symbol to display for monetary values.
  define('CURRENCY_SYMBOL', '$');

//whether to allow NOLA users to send external (internet) email through the system
  define('ALLOW_EXTERNAL_EMAIL', '1');

//Site Name is displayed various places throughout the site.  It should be your company name, or something associated with it
  define('SITE_NAME', 'Noguska');

//NOLA Software version
  define('SOFTWARE_VERSION', '1.1.2');

//whether to allow 'instant' messaging.  This checks for messages on almost every page load.
  define('MESSAGING_INSTANT_ON', '0');

//this determines what alignment the left sides of many tables have.
  define('TABLE_LEFT_SIDE_ALIGN', 'right');

//this is the size of the footer at the bottom of all pages.  This size can be 0+/-3, and is relative to the 'normal' text size of the web browser (0)
  define('FOOTER_TEXT_SIZE', '-2');

//number of decimal places you prefer to see on monetary values.  This will never truncate significant decimal places.
  define('PREFERRED_DECIMAL_PLACES', '2');

//whether or not to capitalize words that should not be capitalized
  define('ABNORMAL_CAPS', '1');

//optimizes selection screens for companies with many customers (>~200).
  define('MANY_CUSTOMERS', '0');

//optimizes selection screens for companies with many vendors (>~200).
  define('MANY_VENDORS', '0');

//optimizes selection screens for companies with many items (>~200).
  define('MANY_ITEMS', '0');

//optimizes selection screens for companies with many composite items (>~200).
  define('MANY_ITEMS_COMPOSITE', '0');

//mime types of files user is allowed to upload using document manager
//list of registered mime types is available at ftp://ftp.isi.edu/in-notes/iana/assignments/media-types/
  $allowedFileTypes = array("image/gif", "text/html", "text/plain", "text/richtext", "text/tab-separated-values", "image/jpeg", "image/pjpeg", "image/png", "image/gif", "image/tiff", "application/msword", "application/pdf", "application/sgml", "application/vnd.ms-powerpoint", "application/vnd.ms-project", "application/vnd.ms-works", "application/vnd.lotus-wordpro", "application/vnd.lotus-approach", "application/vnd.lotus-1-2-3");

//disallowed file extentions
   $disallowedfileext=array('.php','.phps','.php3');
 

// *****************************
// * Customs section
// *****************************

//for xbs custom, use special login page (authentication/interface.php)
  define('XBS_LOGON_SHOW','0');

//for cbl custom, use lowest area to determine next size paper on custom entry, or use fewest cuts
  define('CBL_PAPER_CUSTOM_USE_AREA','1');

//for cbl custom, notes for 1 color quote
  define('CBL_QUOTE_NOTES_ONE_COLOR','<b>1-Color Artwork:</b> If possible, please provide enough samples or camera ready copies to set multiple up on 11"x17" or 8.5"x14".  This speeds up our system which allows your order to ship out quicker.');

//for cbl custom, notes for 2 color quote
  define('CBL_QUOTE_NOTES_TWO_COLOR','<b>Special Notes for 2-Color Orders:</b> Artwork must be provided camera ready, color separated and set as many up on 8.5"x11", 8.5"x14", or 11"x17" to avoid composition charges.  This form is quoted without tight registration unless selected above.');

//for cbl custom, notes for all  quote
  define('CBL_QUOTE_NOTES','<b>Composition/Paste Up:</b> $30.00/hour (min. charge of $6.00)');

//for cbl custom, notes to show on logon
  define('CBL_LOGON_NOTES','');

//for cbl custom, logo to show on quote
  define('CBL_QUOTE_LOGO','0');
  define('CBL_QUOTE_LOGO_IMAGE','images/cbllogo.jpg');
?>
