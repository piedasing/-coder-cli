/**
 * @license Copyright (c) 2003-2015, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {
	// Define changes to default configuration here. For example:
	// config.language = 'fr';
	// config.uiColor = '#AADC6E';
	// For the complete reference:
	// http://docs.ckeditor.com/#!/api/CKEDITOR.config

	// The toolbar groups arrangement, optimized for two toolbar rows.
	config.extraPlugins = 'templates,youtube';
	config.toolbarGroups = [
		{ name: 'clipboard',   groups: [ 'clipboard', 'undo' ] },
		{ name: 'editing',     groups: [ 'find', 'selection', 'spellchecker' ] },
		{ name: 'links' },
		{ name: 'insert' , groups: [ 'Youtube'] },
		//{ name: 'forms' },
		{ name: 'tools' },
		{ name: 'document',	   groups: [ 'mode', 'document', 'doctools','-', 'Templates' ] },
		{ name: 'others' },

		{ name: 'basicstyles', groups: [ 'basicstyles', 'cleanup' ] },
		{ name: 'paragraph',   groups: [ 'list', 'indent', 'blocks', 'align' ] },
		{ name: 'styles' },
		{ name: 'colors' },
		{ name: 'about' }
	];

	// Remove some buttons, provided by the standard plugins, which we don't
	// need to have in the Standard(s) toolbar.
	config.removeButtons = 'Subscript,Superscript,About';
	config.height='300px';
	

	//上傳檔案
	config.filebrowserUploadUrl= '../comm/ckeditorupload_file.php';
	
	//Flash
	config.filebrowserFlashUploadUrl = '../comm/ckeditorupload_flash.php';
	
	//上傳檔案 圖片
	config.filebrowserImageUploadUrl = '../comm/ckeditorupload.php';
	
	//不拿掉 class & id
	config.allowedContent = true;
	//config.entities = false;
	//config.entities_latin = false;
	//config.entities_greek = false;
	config.font_names = 'Arial;Arial Black;Comic Sans MS;Courier New;Tahoma;Times New Roman;Verdana;新細明體;細明體;標楷體;微軟正黑體';

	//不要自動加P
    CKEDITOR.config.autoParagraph = false;

	//不要把空格 取代成 &nbsp;
    config.fillEmptyBlocks = false;

	/*config.entities  = false;
config.basicEntities = false;

config.htmlEncodeOutput = false;*/

	//config.entities_processNumerical = true;
	//config.entities_processNumerical = 'force';

	//config.entities_greek = false;
	//config.entities_latin = false;

    CKEDITOR.dtd.a.div = 1;
    CKEDITOR.dtd.a.p = 1;
    CKEDITOR.dtd.label.div = 1;
    CKEDITOR.dtd.a.span = 1;
    CKEDITOR.dtd.a.h2 = 1;
    CKEDITOR.dtd.a.h3 = 1;
    CKEDITOR.dtd.a.h4 = 1;

};
