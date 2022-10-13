/*
 * MyBB: Prism Highlight Code
 *
 * File: prism_highlight_init.js
 * 
 * Authors: ic_myXMB
 *
 * MyBB Version: 1.8
 *
 * Plugin Version: 1.0.1
 * 
 */

// HighlightJS

// Add Event Listener
document.addEventListener('DOMContentLoaded', (event) => {
	// Query Selector
    document.querySelectorAll('code').forEach((el) => {
    	 // Init HighlightJS el
         hljs.highlightElement(el);
    });
});		
// Highlight brPlugin
const brPlugin = {
	// Before
    "before:highlightBlock": ({ block }) => {
    	 // Block inner Html
         block.innerHTML = block.innerHTML.replace(/\n/g, '').replace(/<br[ /]*>/g, '\n');
    },
    // After
    "after:highlightBlock": ({ result }) => {
    	 // Result value
         result.value = result.value.replace(/\n/g, '<br>');
    }
};
// Init brPlugin for hljs
hljs.addPlugin(brPlugin);

// PrismJS

// Load event happens after all resources are loaded for the page.
window.addEventListener('load', function () {
 // Ready do logic	 
 $(document).ready( function() { 
 	 // Wrap code tags in pre tags
     $('code').wrap('<pre class="line-numbers"></pre>');
  });
  // Init Prism Highlight
  $(Prism.highlightAll);
});    
