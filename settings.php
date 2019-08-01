<?php
add_action('admin_menu', 'dirtysuds_export_admin_add_page');
function dirtysuds_export_admin_add_page() {
	add_options_page('Export to InDesign', 'Export to InDesign', 'manage_options', 'dirtysuds_export', 'dirtysuds_export_options_page');
}

function dirtysuds_export_options_page() {
	echo '<div class="wrap">';
	//screen_icon();
	echo '<h2>Export to InDesign Settings</h2>',
	'<form name="form1" method="post" action="options.php">';
	settings_fields('dirtysuds_export_option');
	do_settings_sections('dirtysuds_export');
	submit_button('Save Changes');
	echo '</form></div>';
}

function dirtysuds_export_admin_init(){
	//add_settings_section( $id, $title, $callback, $page )
	add_settings_section(
		'dirtysuds_export_main',
		'Tagged Text Settings',
		'dirtysuds_export_section_taggedText',
		'dirtysuds_export'
	);
	//add_settings_field( $id, $title, $callback, $page, $section = 'default', $args = array() )
	add_settings_field(
		'dirtysuds_export_text_outputFormat',
		'Output Encoding',
		'dirtysuds_export_setting_outputFormat',
		'dirtysuds_export',
		'dirtysuds_export_main'
	);
	add_settings_field(
		'dirtysuds_export_text_NormalParagraphStyle',
		'Style Name',
		'dirtysuds_export_setting_NormalParagraphStyle',
		'dirtysuds_export',
		'dirtysuds_export_main'
	);
	add_settings_field(
		'dirtysuds_export_text_Font',
		'Font Name',
		'dirtysuds_export_setting_Font',
		'dirtysuds_export',
		'dirtysuds_export_main'
	);
	add_settings_field(
		'dirtysuds_export_text_Size_Font',
		'Size Font',
		'dirtysuds_export_setting_Size_Font',
		'dirtysuds_export',
		'dirtysuds_export_main'
	);
	//register_setting( $option_group, $option_name, $args )
	register_setting(
		'dirtysuds_export_option',
		'dirtysuds_export_options',
		'dirtysuds_export_options_validate'
	);
}
add_action('admin_init', 'dirtysuds_export_admin_init');

function dirtysuds_export_section_taggedText() {
	echo 'Configuración para la exportación al InDesign.';//descripcion del plugin
}

function dirtysuds_export_setting_outputFormat() {
	$options = get_option('dirtysuds_export_options');
	echo "<select id='dirtysuds_export_text_outputFormat' name='dirtysuds_export_options[outputFormat]'>",
	'<option value=\'&lt;ANSI-MAC&gt;\'',
	str_replace(1,' selected',$options['outputFormat']=='<ANSI-MAC>'),
	'>Mac</option>',
	'<option value=\'&lt;ANSI-WIN&gt;\'',
	str_replace(1,' selected',$options['outputFormat']=='<ANSI-WIN>'),
	'>Windows</option>',
	'</select> Select the platform you run InDesign on';
}

function dirtysuds_export_setting_NormalParagraphStyle() {
	$options = get_option('dirtysuds_export_options');
	echo "<input id='dirtysuds_export_text_NormalParagraphStyle' name='dirtysuds_export_options[NormalParagraphStyle]' size='40' type='text' value='{$options['NormalParagraphStyle']}' /> by default 'NormalParagraphStyle'";
}

function dirtysuds_export_setting_Font() {
	$options = get_option('dirtysuds_export_options');
	echo "<input id='dirtysuds_export_text_Font' name='dirtysuds_export_options[Font]' size='40' type='text' value='{$options['Font']}' />
by default 'Times New Roman'";
}

function dirtysuds_export_setting_Size_Font() {
	$options = get_option('dirtysuds_export_options');
	echo "<input id='dirtysuds_export_text_Size_Font' name='dirtysuds_export_options[Size_Font]' size='40' type='text' value='{$options['Size_Font']}' />
by default '10'";
}

function dirtysuds_export_options_validate($input) {
	$options = get_option('dirtysuds_export_options');
	
	$options['outputFormat']         = trim($input['outputFormat']);
	$options['NormalParagraphStyle'] = trim($input['NormalParagraphStyle']);
	$options['Font'] 				 = trim($input['Font']);
	$options['Size_Font'] 			 = trim($input['Size_Font']);

	if(!preg_match('/^[a-zA-Z]{1,32}$/', $options['NormalParagraphStyle']))
		$options['NormalParagraphStyle'] = 'NormalParagraphStyle';
	if(!preg_match('/^(<ANSI-MAC>|<ANSI-WIN>)$/', $options['outputFormat']))
		$options['outputFormat'] = '<ANSI-MAC>';
	if(!preg_match('/^[a-zA-Z0-9 ]{1,32}$/', $options['Font']))
		$options['Font'] = 'Times New Roman';
	if(!preg_match('/^[0-9]{1,2}$/', $options['Size_Font']))
		$options['Size_Font'] = '10';
	
	return $options;
}
