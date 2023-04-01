<?php
/*
 * MyBB: Prism Highlight Code
 *
 * File: prism_highlight.php
 * 
 * Authors: ic_myXMB
 *
 * MyBB Version: 1.8
 *
 * Plugin Version: 1.0.4
 * 
 */

// If Not Defined
if(!defined("IN_MYBB")) {
	
  // Then Die
  die("Direct initialization of this file is not allowed.<br /><br />Please make sure IN_MYBB is defined.");
}

// Add Plugin Hooks

// Load in Showthread
$plugins->add_hook("showthread_start", "prism_highlight");

// Load in Portal 
$plugins->add_hook("portal_start", "prism_highlight");

// Plugin Information
function prism_highlight_info() {
    
    // Globals
    global $db, $lang, $prism_highlight_settingsgroup_cache;
    
	// Lang Load
	$lang->load("prism_highlight");

	// Settings link
	if(empty($prism_highlight_settingsgroup_cache)) {
		
		// Gid Query
		$gid_query = $db->simple_select('settinggroups', 'gid, name', 'isdefault = 0');
        
		// While
		while($group = $db->fetch_array($gid_query)) {

			// Cache 
			$prism_highlight_settingsgroup_cache[$group['name']] = $group['gid'];
		}
	}

	// Gid
	$gid = isset($prism_highlight_settingsgroup_cache['prism_highlight']) ? $prism_highlight_settingsgroup_cache['prism_highlight'] : 0;
    
	// Settings Link
	$prism_highlight_config = '<br />';
    
	// If Gid
	if($gid) {

		// Globals
		global $mybb;
		
		// Settings Link
		$prism_highlight_config = '<a style="float: right;" href="index.php?module=config&amp;action=change&amp;gid='.$gid.'"><img src="../inc/plugins/prism_highlight/images/settings.png" width="16px" height="16px" style="padding: 2px; vertical-align:middle;">'.$lang->prism_highlight_config.'</a>';
	}

    // Array Return  
    return array(
        'name' => $lang->prism_highlight_name,
        'description' => $lang->prism_highlight_description .$prism_highlight_config,
        'website' => $lang->prism_highlight_website,
        'author' => $lang->prism_highlight_author,
        'authorsite' => $lang->prism_highlight_author_site,
        'version' => $lang->prism_highlight_version,
        'codename' => $lang->prism_highlight_code_name,
        'compatibility' => $lang->prism_highlight_compatability
    );

}

// Plugin Activate
function prism_highlight_activate() {

    // Add Plugin Settings

    // Globals
    global $db, $mybb, $lang; 

    // Language Load
    $lang->load("prism_highlight");

    // Settings Group
    $settinggroups = array(
        'name'          => 'prism_highlight', 
        'title'         => $db->escape_string($lang->prism_highlight_settingsgroup_title),
        'description'   => $db->escape_string($lang->prism_highlight_settingsgroup_description),
        'disporder'     => '101',
        'isdefault'     => '0'
    );

    // Group
    $group['gid'] = $db->insert_query('settinggroups', $settinggroups);

    // Gid
    $gid = $db->insert_id();

    // Disporder
    $disporder = '0';
    
    // Setting 1
    $setting_1 = array(
        'sid'           => '0',
        'name'          => 'prism_highlight_setting_1',
        'title'         => $db->escape_string($lang->prism_highlight_setting_1_title),
        'description'   => $db->escape_string($lang->prism_highlight_setting_1_description),
        'optionscode'   => 'yesno',
        'value'         => '1',
        'disporder'     => $disporder++,
        'gid'           => intval($gid)
    );

    // Query Insert
    $db->insert_query('settings', $setting_1);

    // Setting 2
    $setting_2 = array(
        'sid'           => '0',
        'name'          => 'prism_highlight_setting_2',
        'title'         => $db->escape_string($lang->prism_highlight_setting_2_title),
        'description'   => $db->escape_string($lang->prism_highlight_setting_2_description),
        'optionscode'   => "select\n0=Default\n1=Dark\n2=Funky\n3=Okaidia\n4=Twilight\n5=Coy\n6=Solarized Light\n7=Tomorrow Night",
        'value'         => '0',
        'disporder'     => $disporder++,
        'gid'           => intval($gid)
    );

    // Query Insert
    $db->insert_query('settings', $setting_2);
    
    // Rebuild Settings
    rebuild_settings(); 

    // Edit Templates
    require_once MYBB_ROOT."/inc/adminfunctions_templates.php";

    // Add Template Edits

    // Showthread Template
    find_replace_templatesets('showthread', '#'.preg_quote('</head>').'#i', '{$prism_highlight}</head>');

    // Portal Template
    find_replace_templatesets('portal', '#'.preg_quote('</head>').'#i', '{$prism_highlight}</head>');

}

// Plugin Deactivate
function prism_highlight_deactivate() {

  // Remove Plugin Settings

  // Globals  
  global $db;

  // Delete Query
  $db->delete_query('settinggroups', 'name = \'prism_highlight\'');
  $db->delete_query('settings', 'name LIKE \'%prism_highlight%\'');

  // Edit Templates
  require_once MYBB_ROOT."/inc/adminfunctions_templates.php";

  // Remove Template Edits

  // Showthread Template
  find_replace_templatesets('showthread', '#'.preg_quote('{$prism_highlight}</head>').'#i', '</head>');
  
  // Portal Template
  find_replace_templatesets('portal', '#'.preg_quote('{$prism_highlight}</head>').'#i', '</head>');

}

// prism_highlight Func
function prism_highlight() {

  // Styles (in themes dir)
  
  // Globals
  global $mybb, $prism_highlight;
  
  // Style 0: Default
  if ($mybb->settings['prism_highlight_setting_2'] == "0") {

     // Style
     $codeblock_style = "<!-- Prism Default CSS -->
<link href=\"inc/plugins/prism_highlight/themes/default.min.css\" rel=\"stylesheet\" />
<style>
  .codeblock {
	 background: #F5F2F0;
   }
   pre[class*=\"language-\"].line-numbers {
     padding-left: 3.8em;
   }
   .collapsed:after, .expanded:after {
     border: 1px solid #F5F2F0;
     background: #F5F2F0;
     color: #333;
   }
   .collapsed:hover:after, .expanded:hover:after {
     background: #F5F2F0;
     color: #555;
   }    
</style>";

  }

  // Style 1: Dark
  if ($mybb->settings['prism_highlight_setting_2'] == "1") {

     // Style
     $codeblock_style = "<!-- Prism Dark CSS -->
<link href=\"inc/plugins/prism_highlight/themes/dark.min.css\" rel=\"stylesheet\" />
<style>
  .codeblock {
	 background: #4c3f33;
	 color: #ffffff;
   }
   pre[class*=\"language-\"].line-numbers {
     padding-left: 3.8em;
   } 
   pre[class*=\"language-\"] {
     border: unset;
     box-shadow: unset;
   }
   .collapsed:after, .expanded:after {
     border: 1px solid #4c3f33;
     background: #4c3f33;
     color: #A0A0A0;
   }
   .collapsed:hover:after, .expanded:hover:after {
     background: #4c3f33;
     color: #fff;
   }
</style>";

  }

  // Style 2: Funky
  if ($mybb->settings['prism_highlight_setting_2'] == "2") {

     // Style
     $codeblock_style = "<!-- Prism Funky CSS -->
<link href=\"inc/plugins/prism_highlight/themes/funky.min.css\" rel=\"stylesheet\" />
<style>
   .codeblock {
     background: url('data:image/svg+xml;charset=utf-8,<svg%20version%3D\"1.1\"%20xmlns%3D\"http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg\"%20width%3D\"100\"%20height%3D\"100\"%20fill%3D\"rgba(0%2C0%2C0%2C.2)\">%0D%0A<polygon%20points%3D\"0%2C50%2050%2C0%200%2C0\"%20%2F>%0D%0A<polygon%20points%3D\"0%2C100%2050%2C100%20100%2C50%20100%2C0\"%20%2F>%0D%0A<%2Fsvg>');
     background-size: auto;
     background-size: 1em 1em;
   }
   pre[class*=\"language-\"].line-numbers {
     padding-left: 3.8em;
   } 
   pre[class*=\"language-\"] {
     background: #000 url('data:image/svg+xml;charset=utf-8,<svg%20version%3D\"1.1\"%20xmlns%3D\"http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg\"%20width%3D\"100\"%20height%3D\"100\"%20fill%3D\"rgba(0%2C0%2C0%2C.2)\">%0D%0A<polygon%20points%3D\"0%2C50%2050%2C0%200%2C0\"%20%2F>%0D%0A<polygon%20points%3D\"0%2C100%2050%2C100%20100%2C50%20100%2C0\"%20%2F>%0D%0A<%2Fsvg>') !important;
     background-size: auto;
     background-size: 1em 1em;
     opacity: 0.9;
   }
   .collapsed:after, .expanded:after {
     border: 0px;
     background: transparent;
     color: #E92990;
   }
   .collapsed:hover:after, .expanded:hover:after {
     background: transparent;  
     color: #13C2EF;
   }        
</style>";

  }  

  // Style 3: Okaidia
  if ($mybb->settings['prism_highlight_setting_2'] == "3") {

     // Style
     $codeblock_style = "<!-- Prism Okaidia CSS -->
<link href=\"inc/plugins/prism_highlight/themes/okaidia.min.css\" rel=\"stylesheet\" />
<style>
  .codeblock {
	 background: #272822;
	 color: #f8f8f2;
   }
   pre[class*=\"language-\"].line-numbers {
     padding-left: 3.8em;
   }
   .collapsed:after, .expanded:after {
     border: 1px solid #272822;
     background: #272822;
     color: #f8f8f2;
   }
   .collapsed:hover:after, .expanded:hover:after {
     background: #272822;
     color: #fff;
   }   
</style>";

  }  

  // Style 4: Twilight
  if ($mybb->settings['prism_highlight_setting_2'] == "4") {

     // Style
     $codeblock_style = "<!-- Prism Twilight CSS -->
<link href=\"inc/plugins/prism_highlight/themes/twilight.min.css\" rel=\"stylesheet\" />
<style>
  .codeblock {
	 background: #141414;
	 color: #ffffff;
   }
   pre[class*=\"language-\"].line-numbers {
     padding-left: 3.8em;
   } 
   pre[class*=\"language-\"] {
     border: unset;
   } 
   .collapsed:after, .expanded:after {
     border: 1px solid #141414;
     background: #141414;
     color: #A0A0A0;
   }
   .collapsed:hover:after, .expanded:hover:after {
     background: #141414;
     color: #ffffff;
   }       
</style>";

  }

  // Style 5: Coy
  if ($mybb->settings['prism_highlight_setting_2'] == "5") {

     // Style
     $codeblock_style = "<!-- Prism Coy CSS -->
<link href=\"inc/plugins/prism_highlight/themes/coy.min.css\" rel=\"stylesheet\" />
<style>
  .codeblock {
	 background: #fff;
   }
   pre[class*=\"language-\"].line-numbers {
     padding-left: 3.8em;
   }
   .collapsed:after, .expanded:after {
     border: 1px solid #fff;
     background: #fff;
     z-index: 999;
     color: #333;
   }
   .collapsed:hover:after, .expanded:hover:after {
     background: #fff;
     color: #000;
   }   
</style>";

  }

  // Style 6: Solarized Light
  if ($mybb->settings['prism_highlight_setting_2'] == "6") {

     // Style
     $codeblock_style = "<!-- Prism Solarized Light CSS -->
<link href=\"inc/plugins/prism_highlight/themes/solarized-light.min.css\" rel=\"stylesheet\" />
<style>
  .codeblock {
	 background: #fdf6e3;
   }
   pre[class*=\"language-\"].line-numbers {
     padding-left: 3.8em;
   }  
   .collapsed:after, .expanded:after {
     border: 1px solid #fdf6e3;
     background: #fdf6e3;
     color: #333;
   }
   .collapsed:hover:after, .expanded:hover:after {
     background: #fdf6e3;
     color: #000;
   }      
</style>";

  } 

  // Style 7: Tomorrow Night
  if ($mybb->settings['prism_highlight_setting_2'] == "7") {

     // Style
     $codeblock_style = "<!-- Prism Tomorrow Night CSS -->
<link href=\"inc/plugins/prism_highlight/themes/tomorrow-night.min.css\" rel=\"stylesheet\" />
<style>
  .codeblock {
	 background: #2d2d2d;
	 color: #ccc;
   }
   pre[class*=\"language-\"].line-numbers {
     padding-left: 3.8em;
   } 
   .collapsed:after, .expanded:after {
     border: 1px solid #2d2d2d;
     background: #2d2d2d;
     color: #ccc;
   }
   .collapsed:hover:after, .expanded:hover:after {
     background: #2d2d2d;
     color: #fff;
   }     
</style>";

  }   

  // Add To / Remove From Head On Showthread And Portal templates

  // If Plugin Active
  if ($mybb->settings['prism_highlight_setting_1'] == "1") { 

     // Globals
     global $headerinclude;

     // Headerinclude
     $headerinclude .= "
<!-- Highlight JS --> 
<script src=\"inc/plugins/prism_highlight/jscripts/highlight.min.js\"></script>
<!-- Prism JS --> 
<script src=\"inc/plugins/prism_highlight/jscripts/prism.js\"></script>
<!-- Highlight JS And Prism JS Init -->  
<script src=\"inc/plugins/prism_highlight/jscripts/prism_highlight_init.js\"></script>
<!-- Codeblock Expand/Collapse (CSS lang) -->
<style> 
.codeblock code {
	display: unset;
} 
.no_bottom_border {
    border-bottom: 0;
}
.collapsed, .expanded {
    overflow: hidden;
    border: 1px solid #D3D3D3;
    background: #FAFAFA;
    text-align: justify;
    padding: 10px;
    border-radius: 5px;
    margin: 0;
    position: relative;
}
.collapsed {
    max-height: 212px;
    opacity: 0.7;
}
.expanded {
    height: 100%;
    opacity: 1;
    padding-bottom: 35px;
}
.collapsed:after, .expanded:after {
    position: absolute;
    height: 25px;
    bottom: 0px;
    left: 1px;
    right: 1px;
    cursor: pointer;
    border: 1px solid #A5A5A5;
    background: #D3D3D3;
    color: #4A4A4A;
    text-align: center;
    line-height: 25px;
    font-weight: bold;
    font-family: Tahoma, Verdana, Arial, Sans-Serif;
    font-size: 13px;
    border-radius: 0px 0px 5px 5px;
}
.collapsed:after {
    opacity: 0.9;
}
.expanded:after {
    opacity: 0.7;
}
.collapsed:hover:after, .expanded:hover:after {
    opacity: 1;
    background: #838383;
    color: #1A1A1A;
}
/** Lang() Translations **/

/* english */

.collapsed:lang(en):after{
   content: 'Show More';
}

.expanded:lang(en):after{
   content: 'Show Less';
}

/* english-us */

.collapsed:lang(en-us):after{
   content: 'Show More';
}

.expanded:lang(en-us):after{
   content: 'Show Less';
}

/* english-gb */

.collapsed:lang(en-gb):after{
   content: 'Show More';
}

.expanded:lang(en-gb):after{
   content: 'Show Less';
}

/* espanol */

.collapsed:lang(es):after{
   content: 'Mostrar más';
}

.expanded:lang(es):after{
   content: 'Muestra menos';
}

/* french */

.collapsed:lang(fr):after{
   content: 'Montre plus';
}

.expanded:lang(fr):after{
   content: 'Montrer moins';
}

/* italiano */

.collapsed:lang(it):after{
   content: 'Mostra di più';
}

.expanded:lang(it):after{
   content: 'Mostra meno';
}

/* german */

.collapsed:lang(de):after{
   content: 'Zeige mehr';
}

.expanded:lang(de):after{
   content: 'Weniger anzeigen';
}
</style>
<!-- Codeblock Expand/Collapse -->
<script type=\"text/javascript\">
$(document).ready(function () {
    $('div.codeblock').addClass('collapsed');
        $('div.codeblock').click(function(event){
        $(this).toggleClass('expanded collapsed');
        event.preventDefault();
    });
});
</script>";

     // Add Codeblock Style CSS File Links
     $prism_highlight = "".$codeblock_style."";

  }

  // If Plugin Not Active 
  if ($mybb->settings['prism_highlight_setting_1'] == "0") { 

     // Empty Codeblock Style
     $prism_highlight = "";
  }  

}

?>
