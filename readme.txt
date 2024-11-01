=== Plugin Name ===
Contributors: 
Donate link: 
Tags: politics, campaigns, daylife, finance, shortcode
Requires at least: 3.1
Tested up to: 3.1-alpha
Stable tag: .11

Uses custom post types, custom taxonomies, and the DayLife API to create a searchable database of candidates, insertable into posts via infobox

== Description ==

Creates a database of political candidates to store party, race, state, district, incumbency, and cash on hand. Queries the DayLife News API to retrieve photos, recent coverage, bio, quotes, etc. Provides functionality to insert candidate data as a drop-in infobox via a shortcode or allows users to search for and compare candidates by any of the above dimensions.

Created in one day at the Online News Association Hacks/Hackers competition in Washington D.C. (October, 2010). Special thanks to DayLife for sponsoring the event and for helping to integrate the API.

(Please Note: Still work in progress, and as a result, a very rough, pre-alpha build.)

== Installation ==

1. Download an unzip to the WordPress plugin directory
2. Navigate to Candidates -> Options in the dashboard and enter your API keys when prompted
3. Add candidates to the candidate database via the "Add Candidate" menu
4. If desired, add the shortcode "[ccviz_search]" to a page to create a search page.
5. If desired, add the shortcode "[ccviz_box candidate='X']" where X is the candidates post ID to insert an infobox into a post

== Frequently Asked Questions ==

== Screenshots ==

1. Example infobox inserted into a post

== Changelog ==

= .12 =
Added Daylife logo to comply with API ToS

= .11 = 
Fixed paths

= .1 =
Initial version

= Todo =
DATA
 *Financial Data API Wrapper
 * Financial Data Integration
 * API Endpoint for Candidate Data
SEARCH
 * Make search work
 * Style search box and results
 * AJAX search results
PORTABILITY
 * Default Taxonomy on Activation
 * Check/Prompt for API Keys on Activation
 * Un-Hardcode URLs (JavaScript)
META & 
 * Exclusive Taxonomies
 * Better labeling of Meta data on backend	
 * Nonce Meta
 * Remove extra metaboxes from add/edit candidate page
USABILITY
 * Prompt to select DayLife ID on candidate add (disambiguation)
 * Better shortcode identifier (candidate name instead of ID, etc.)
 * Links to sign-up for APIs
DOCUMENTATION
 * In line
 * Readme