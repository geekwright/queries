<?php
// Module Info

// The name of this module
define("_MI_QUERIES_NAME", "Queries");

// A brief description of this module
define("_MI_QUERIES_DESC", "Publish genealogical queries for access by other researchers.");

// Text for menu
define("_MI_QUERIES_MENU_ADD", "Submit Query");
define("_MI_QUERIES_MENU_REVIEW", "Review");

// Text for admin area
define("_MI_QUERIES_ADMAIN", "Index");
define("_MI_QUERIES_ABOUT", "About");
define("_MI_QUERIES_PERMISSIONS", "Permissions");
define("_MI_QUERIES_AD_PERM_TITLE", "Permissions for Submit Query");
define("_MI_QUERIES_AD_PERM_1", "Auto-Approve");
define("_MI_QUERIES_AD_PERM_2", "Add/Edit/Delete for Others");

// Text for config options

define('_MI_QUERIES_PREF_DATE', "Date Format");
define('_MI_QUERIES_PREF_DATE_DSC', "Format passed to formatTimeStamp()");

define('_MI_QUERIES_PREF_ROWS', "Rows to Display");
define('_MI_QUERIES_PREF_ROWS_DSC', "Maximum rows to display per page.");

define('_MI_QUERIES_POST_ANON', "Anonymous Posting");
define('_MI_QUERIES_POST_ANON_DSC', "Allow posting from Anonymous guests.");

define('_MI_QUERIES_CAPTCHA', "Enable Posting Captcha");
define('_MI_QUERIES_CAPTCHA_DSC', "Require Captcha on Query Posting.");

// notifications

define('_MI_QUERIES_GLOBAL_NOTIFY', 'All Queries');
define('_MI_QUERIES_GLOBAL_NOTIFY_DSC', "Notification options that apply to all queries.");

define('_MI_QUERIES_SINGLE_NOTIFY', 'Individual Query');
define('_MI_QUERIES_SINGLE_NOTIFY_DSC', "Notification options that apply to a single query.");

define('_MI_QUERIES_NEW_NOTIFY', 'New Query');
define('_MI_QUERIES_NEW_NOTIFYCAP', 'Notify me whenever someone posts a new query.');
define('_MI_QUERIES_NEW_NOTIFYDSC', 'Receive notification when anyone posts a query.');
define('_MI_QUERIES_NEW_NOTIFYSBJ', '[{X_SITENAME}] auto-notify : new query posted');

define ('_MI_QUERIES_NEW_NEED_APPROVAL', 'New Query Needs Approval');
define ('_MI_QUERIES_NEW_NEED_APPROVAL_CAP', 'Query Approval Needed.');
define ('_MI_QUERIES_NEW_NEED_APPROVAL_DSC', 'Receive notification when a submitted query needs approval.');
define ('_MI_QUERIES_NEW_NEED_APPROVAL_SBJ', '[{X_SITENAME}] auto-notify : Query Needs Approval');

define("_MI_QUERIES_LIST", "Queries List");
define("_MI_QUERIES_ORDER_BY_NEWEST", "Ordered by time posted, newest first");
