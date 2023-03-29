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
 * Plugin Version: 1.0.2
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

	// Configuration link
	if(empty($prism_highlight_settingsgroup_cache)) {
		// Query
		$query = $db->simple_select('settinggroups', 'gid, name', 'isdefault = 0');
        
        // While
		while($group = $db->fetch_array($query)) {
			// Cache 
			$prism_highlight_settingsgroup_cache[$group['name']] = $group['gid'];
		}
	}

    // Gid
	$gid = isset($prism_highlight_settingsgroup_cache['prism_highlight']) ? $prism_highlight_settingsgroup_cache['prism_highlight'] : 0;
    
    // Config Link
	$prism_highlight_config = '<br />';
    
    // If Gid
	if($gid) {
		
	    // Globals
		global $mybb;
		
        // Config Link
		$prism_highlight_config = '<a style="float: right;" href="index.php?module=config&amp;action=change&amp;gid='.$gid.'">'.$lang->prism_highlight_config.'</a>';
	}

    // Return  
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
<link href=\"inc/plugins/prism_highlight/themes/default.css\" rel=\"stylesheet\" />
<style>
  .codeblock {
	 background: #F5F2F0;
   }
   pre[class*=\"language-\"].line-numbers {
     padding-left: 2.8em;
   }   
</style>";

  }

  // Style 1: Dark
  if ($mybb->settings['prism_highlight_setting_2'] == "1") {

  	 // Style
     $codeblock_style = "<!-- Prism Dark CSS -->
<link href=\"inc/plugins/prism_highlight/themes/dark.css\" rel=\"stylesheet\" />
<style>
  .codeblock {
	 background: #4c3f33;
	 color: #ffffff;
   }
   pre[class*=\"language-\"].line-numbers {
     padding-left: 2.8em;
   } 
   pre[class*=\"language-\"] {
     border: unset;
     box-shadow: unset;
   }       
</style>";

  }

  // Style 2: Funky
  if ($mybb->settings['prism_highlight_setting_2'] == "2") {

  	 // Style
     $codeblock_style = "<!-- Prism Funky CSS -->
<link href=\"inc/plugins/prism_highlight/themes/funky.css\" rel=\"stylesheet\" />
<style>
   .codeblock {
     background: url('data:image/svg+xml;charset=utf-8,<svg%20version%3D\"1.1\"%20xmlns%3D\"http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg\"%20width%3D\"100\"%20height%3D\"100\"%20fill%3D\"rgba(0%2C0%2C0%2C.2)\">%0D%0A<polygon%20points%3D\"0%2C50%2050%2C0%200%2C0\"%20%2F>%0D%0A<polygon%20points%3D\"0%2C100%2050%2C100%20100%2C50%20100%2C0\"%20%2F>%0D%0A<%2Fsvg>');
     background-size: auto;
     background-size: 1em 1em;
   }
   pre[class*=\"language-\"].line-numbers {
     padding-left: 2.8em;
   } 
   pre[class*=\"language-\"] {
     background: #000 url('data:image/svg+xml;charset=utf-8,<svg%20version%3D\"1.1\"%20xmlns%3D\"http%3A%2F%2Fwww.w3.org%2F2000%2Fsvg\"%20width%3D\"100\"%20height%3D\"100\"%20fill%3D\"rgba(0%2C0%2C0%2C.2)\">%0D%0A<polygon%20points%3D\"0%2C50%2050%2C0%200%2C0\"%20%2F>%0D%0A<polygon%20points%3D\"0%2C100%2050%2C100%20100%2C50%20100%2C0\"%20%2F>%0D%0A<%2Fsvg>') !important;
     background-size: auto;
     background-size: 1em 1em;
     opacity: 0.9;
   }     
</style>";

  }  

  // Style 3: Okaidia
  if ($mybb->settings['prism_highlight_setting_2'] == "3") {

  	 // Style
     $codeblock_style = "<!-- Prism Okaidia CSS -->
<link href=\"inc/plugins/prism_highlight/themes/okaidia.css\" rel=\"stylesheet\" />
<style>
  .codeblock {
	 background: #272822;
	 color: #f8f8f2;
   }
   pre[class*=\"language-\"].line-numbers {
     padding-left: 2.8em;
   }
</style>";

  }  

  // Style 4: Twilight
  if ($mybb->settings['prism_highlight_setting_2'] == "4") {

  	 // Style
     $codeblock_style = "<!-- Prism Twilight CSS -->
<link href=\"inc/plugins/prism_highlight/themes/twilight.css\" rel=\"stylesheet\" />
<style>
  .codeblock {
	 background: #141414;
	 color: #ffffff;
   }
   pre[class*=\"language-\"].line-numbers {
     padding-left: 2.8em;
   } 
   pre[class*=\"language-\"] {
     border: unset;
   }     
</style>";

  }

  // Style 5: Coy
  if ($mybb->settings['prism_highlight_setting_2'] == "5") {

  	// Style
     $codeblock_style = "<!-- Prism Coy CSS -->
<link href=\"inc/plugins/prism_highlight/themes/coy.css\" rel=\"stylesheet\" />
<style>
  .codeblock {
	 background: #ffffff;
   }
   pre[class*=\"language-\"].line-numbers {
     padding-left: 2.8em;
   }
</style>";

  }

  // Style 6: Solarized Light
  if ($mybb->settings['prism_highlight_setting_2'] == "6") {

  	 // Style
     $codeblock_style = "<!-- Prism Solarized Light CSS -->
<link href=\"inc/plugins/prism_highlight/themes/solarized-light.css\" rel=\"stylesheet\" />
<style>
  .codeblock {
	 background: #fdf6e3;
   }
   pre[class*=\"language-\"].line-numbers {
     padding-left: 2.8em;
   }     
</style>";

  } 

  // Style 7: Tomorrow Night
  if ($mybb->settings['prism_highlight_setting_2'] == "7") {

  	 // Style
     $codeblock_style = "<!-- Prism Tomorrow Night CSS -->
<link href=\"inc/plugins/prism_highlight/themes/tomorrow-night.css\" rel=\"stylesheet\" />
<style>
  .codeblock {
	 background: #2d2d2d;
	 color: #ccc;
   }
   pre[class*=\"language-\"].line-numbers {
     padding-left: 2.8em;
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
<style>
  .codeblock code {
	 display: unset;
   }   
</style>";

  	 // Add Codeblock Style Inline
     $prism_highlight = "".$codeblock_style."";

  }

  // If Plugin Not Active 
  if ($mybb->settings['prism_highlight_setting_1'] == "0") { 
  	 // Empty Codeblock Style Inline
     $prism_highlight = "";
  }  

}

?>
