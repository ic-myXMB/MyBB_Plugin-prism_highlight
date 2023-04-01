# MyBB_Plugin-prism_highlight
 /*
 * MyBB: Prism Highlight Code
 * 
 * Authors: ic_myXMB
 *
 * MyBB Version: 1.8.x (was created at time using 1.8.31)
 *
 * Current Plugin Version: 1.0.4
 *
 * Langs: English, EnglishGB, Espanol, French, Italiano, German
 *
 * Current HighlightJS version:  v11.7.0
 * Current PrismJS version:  v1.29.0
 *
 */

Description: Code and PHP codeblocks syntax highlighting for MyBB using a combo of PrismJS (for code syntax highlighting / styling) and HighlightJS(for detecting code Langs).

INSTALL INSTRUCTIONS:

 1) Upload the contents of the "Upload" folder to your forum root directory
 2) Visit ACP settings and choose to enable or disable plugin
 3) Visit ACP settings and choose what codeblock style you desire (8 presently)
 4) Have Fun!

CHANGELOG:

- Version 1.0.4

   - added collapse / expand functionality and styling to the codeblock since prism was displaying codeblock full. (To accomplish such opted to to use one of the variants of such styling with css lang translations shared in a past tutorial by vintagedaddyo)

- Version 1.0.3

   - update current HighlightJS version from "v11.6.0" to:  v11.7.0
   - minor cleanup / edits
   - changed existing themes .css files to .min.css files

- Version 1.0.2

   - minor code edits (gid and group[gid] related)
   - added plugin page settings link  

- Version 1.0.1

   - Localization support (currently: English, EnglishGB, Espanol, French, Italiano, German)
   - Added 8 styles from the Prism JS site
   - Contains Ability to select desired codeblock styles (8 currently)
   - Contains ability to enable or disable the plugin
   - Contains localization support
   - Contains ability to detect the code language by using Highlight JS to do so and then Style and Highlight via Prism JS
   - Wraps all code tags in pre tags via JS
   - Contains ability to Select / Copy code on each codeblock
   - Contains Latest / Current HighlightJS / PrismJS version

- Version 1.0.0

   - Initial release

