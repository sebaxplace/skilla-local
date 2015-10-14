/**
 * @license Copyright (c) 2003-2014, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	 config.language = 'it';
	 //config.uiColor = '#AADC6E';
	 toolbar: [
	   		{ name: 'document', items: [ 'Source', '-', 'NewPage', 'Preview', '-', 'Templates' ] },	// Defines toolbar group with name (used to create voice label) and items in 3 subgroups.
	   		[ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ],			// Defines toolbar group without name.
	   		'/',																					// Line break - next group will be placed in new line.
	   		{ name: 'basicstyles', items: [ 'Bold', 'Italic' ] }
	   	]
	 
};

CKEDITOR.replace( '.ckeditor', {
	uiColor: '#14B8C4',
	toolbar: [
	  		{ name: 'document', items: [ 'Source', '-', 'NewPage', 'Preview', '-', 'Templates' ] },	// Defines toolbar group with name (used to create voice label) and items in 3 subgroups.
	  		[ 'Cut', 'Copy', 'Paste', 'PasteText', 'PasteFromWord', '-', 'Undo', 'Redo' ],			// Defines toolbar group without name.
	  		'/',																					// Line break - next group will be placed in new line.
	  		{ name: 'basicstyles', items: [ 'Bold', 'Italic' ] }
	  	]
});
