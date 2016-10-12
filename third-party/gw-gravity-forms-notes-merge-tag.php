<?php
/**
 * Gravity Wiz // Gravity Forms // Notes Merge Tag
 * Include entry notes in notifications (and other places merge tags are supported post-entry-creation).
 * http://gravitywiz.com/
 */

require_once( WP_PLUGIN_DIR . '/gravityforms/entry_detail.php');

if( class_exists( 'GFFormsModel' ) && class_exists( 'GFEntryDetail' ) ) {

	function get_notes($html) {
		$classname = 'detail-note-content gforms_note_note';
		$dom = new DOMDocument;
		$dom->loadHTML($html);
		$xpath = new DOMXPath($dom);
		$results = $xpath->query("//*[@class='" . $classname . "']");

		if ($results->length > 0) {
			return $review = $results->item(0)->nodeValue;
		} else {
			return;
		}
	}

	add_filter( 'gform_replace_merge_tags', function( $text, $form, $entry ) {

		if( strpos( $text, '{notes}' ) === false ) {
			return $text;
		}

		$notes        = GFFormsModel::get_lead_notes( $entry['id'] );
		$notes_markup = '';

		if ( ! empty( $notes ) ) {
			ob_start();
			GFEntryDetail::notes_grid( $notes, false );
			$notes_markup = get_notes( ob_get_clean() );
		}

		return str_replace( '{notes}', $notes_markup, $text );
	}, 10, 3 );
}