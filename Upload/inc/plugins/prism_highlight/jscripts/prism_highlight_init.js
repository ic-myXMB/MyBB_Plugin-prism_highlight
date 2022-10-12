/*
 * MyBB: Prism Highlight Code
 *
 * File: prism_highlight_init.js
 * 
 * Authors: ic_myXMB
 *
 * MyBB Version: 1.8
 *
 * Plugin Version: 1.0
 * 
 */

// HighlightJS

// Init HighlightJS
document.addEventListener('DOMContentLoaded', (event) => {
    document.querySelectorAll('code').forEach((el) => {
         hljs.highlightElement(el);
    });
});		
// Highlight brPlugin
const brPlugin = {
    "before:highlightBlock": ({ block }) => {
         block.innerHTML = block.innerHTML.replace(/\n/g, '').replace(/<br[ /]*>/g, '\n');
    },
    "after:highlightBlock": ({ result }) => {
         result.value = result.value.replace(/\n/g, '<br>');
    }
};
// Init brPlugin for hljs
hljs.addPlugin(brPlugin);

// PrismJS

// load event happens after all resources are loaded for the page.
window.addEventListener('load', function () {
 // do logic	 
 $(document).ready( function() { 
 	 // wrap code tags in pre tags
     $('code').wrap('<pre class="line-numbers"></pre>');
  });
  // Init Prism Highlight
  $(Prism.highlightAll);
});    
