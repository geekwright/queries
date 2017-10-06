<?php
/**
* English language constants used in the user side of the module
*
* @copyright	Copyright 2009-2010 geekwright, LLC
* @license		http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU General Public License (GPL)
* @since		1.0
* @author		Richard Griffith <richard@geekwright.com>
* @package		queries
* @version		$Id$
*/

define('_MD_QUERIES_ADMIN_PAGE', ':: Admin page ::');

define("_MD_QUERIES_ALL_POSTS", "All posts");
// Text for add a query fields

define("_MD_QUERIES_USER", "Researcher");
define("_MD_QUERIES_USER_DSC", "Select the User this Query applies to.");

define("_MD_QUERIES_TITLE", "Title");
define("_MD_QUERIES_TITLE_DSC", "Enter a title for this query. It is recommended to include surnames and locations pertaining to the query.");

define("_MD_QUERIES_QUERYTEXT", "Query");
define("_MD_QUERIES_QUERYTEXT_DSC", "Enter your query here.");

// fields for anonymous entries
define("_MD_QUERIES_NAME", "Researcher Name");
define("_MD_QUERIES_NAME_DSC", "Researcher's Name");

define("_MD_QUERIES_EMAIL", "Researcher Email");
define("_MD_QUERIES_EMAIL_DSC", "Researcher's Email Address");

// admin only fields
define("_MD_QUERIES_COMMENTS", "Comment Count");
define("_MD_QUERIES_COMMENTS_DSC", "");

define("_MD_QUERIES_APPROVED", "Approved");
define("_MD_QUERIES_APPROVED_DSC", "Is query approved for public display?");

define("_MD_QUERIES_POSTED", "Post Date");
define("_MD_QUERIES_POSTED_DSC", "");

define("_MD_QUERIES_CONV1", "Conversion Data 1");
define("_MD_QUERIES_CONV1_DSC", "");

define("_MD_QUERIES_CONV2", "Conversion Data 2");
define("_MD_QUERIES_CONV2_DSC", "");

// messages

define("_MD_QUERIES_NO_ANON", "Anonymous posting is not allowed. Please log in.");
define("_MD_QUERIES_TOKEN_ERR", "The security token check failed. Please try again.");
define("_MD_QUERIES_DB_INS_ERR", "The insert failed. The error was: ");
define("_MD_QUERIES_DB_UPD_ERR", "The update failed. The error was: ");
define("_MD_QUERIES_DB_NOTFOUND", "The requested query does not exist.");

define("_MD_QUERIES_EDIT_MSG_ADD", "Query posted.");
define("_MD_QUERIES_EDIT_MSG_UPD", "Query updated.");
define("_MD_QUERIES_EDIT_MSG_DEL", "Query deleted.");
define("_MD_QUERIES_EDIT_MSG_PENDING", "Query submitted for approval.");

define("_MD_QUERIES_EDIT_BUTTON", "Submit");
define("_MD_QUERIES_EDIT_CAPTION", "Post this Query");

define("_MD_QUERIES_EDIT_DEL", "Check this box to delete this query on submit.");

define("_MD_QUERIES_REVIEW_ERR_1", "You do not have the authority to perform this function.");
define("_MD_QUERIES_REVIEW_ERR_2", "The security token check failed.");
define("_MD_QUERIES_REVIEW_ERR_4", "The update failed. The error was: ");
define("_MD_QUERIES_REVIEW_UPDMSG", '%d Records Processed');
define("_MD_QUERIES_VIEW_ACTIONS", "Actions");
define("_MD_QUERIES_ACTIONS_APPROVE", "Approve");
define("_MD_QUERIES_ACTIONS_CONFIRM", "Are you sure?");
define("_MD_QUERIES_ACTIONS_DELETE", "Delete");
define("_MD_QUERIES_REQUIRED_ERR", "%s is required. Please fill it in and try again.");

define("_MD_QUERIES_VIEW_NO_NAME", "(not available)");
define("_MD_QUERIES_VIEW_NO_EMAIL", "(not provided)");
define("_MD_QUERIES_VIEW_HIDE_EMAIL", "(not shown)");

define("_MD_QUERIES_PRINT_FOOTER", "<br /><br />[i]This query was posted on:[/i]<br />");

// breadcrumb
define("_MD_QUERY_BC_ROOT", "Queries");
define("_MD_QUERY_BC_EDIT", "Post Query");
define("_MD_QUERY_LIST_BY_DATE", "By Date");
define("_MD_QUERY_LIST_REVIEW", "Review Unapproved");
define("_MD_QUERY_VIEW_SINGLE", "View Query");
