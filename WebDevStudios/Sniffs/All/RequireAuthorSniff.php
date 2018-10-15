<?php // @codingStandardsIgnoreLine: Filename is ok.
/**
 * Require the author tag.
 *
 * The since tag is required on all docblock elements.
 *
 * @since   1.2.0
 * @package WebDevStudios\Sniffs
 */

namespace WebDevStudios\Sniffs\All;

use PHP_CodeSniffer_Sniff;
use PHP_CodeSniffer_File;

/**
 * Require the return tag.
 *
 * @author Aubrey Portwood
 * @since  1.2.0
 */
class RequireAuthorSniff extends BaseSniff {

	/**
	 * What are we parsing?
	 *
	 * @author Aubrey Portwood
	 * @since  1.2.0
	 *
	 * @var array
	 */
	public $supportedTokenizers = [ // @codingStandardsIgnoreLine: camelCase required here.
		'PHP',
		'JS',
	];

	/**
	 * Register on all docblock comments.
	 *
	 * @author Aubrey Portwood
	 * @since  1.2.0
	 *
	 * @return array List of tokens.
	 */
	public function register() {
		return [

			/**
			 * PHP/JS Docblock.
			 *
			 * @link http://php.net/manual/en/language.basic-syntax.comments.php
			 *
			 * @since 1.2.0
			 */
			T_DOC_COMMENT_OPEN_TAG,
		];
	}

	/**
	 * Process file.
	 *
	 * @param  PHP_CodeSniffer_File $file            The file object.
	 * @param  int                  $doc_block_start Where the docblock starts.
	 *
	 * @since 1.2.0
	 */
	public function process( PHP_CodeSniffer_File $file, $doc_block_start ) {
		$this->tokens = $file->getTokens();
		$start_token  = $this->get_token( $doc_block_start );
		$end          = $this->get_closer_token_position( $start_token );

		// The @author in the comment block, false by default.
		$has_author_tag = false;
		for ( $i = $doc_block_start; $i <= $end; $i++ ) {

			// Get the content of the token.
			$content = $this->get_token_content( $this->get_token( $i ) );
			if ( stristr( $content, '@author' ) ) {

				// We found an @author in the block.
				$has_author_tag = true;
			}
		}

		if ( ! $has_author_tag ) {
			$this->error( $file, $end, 'Documenting @author is helpful. If the author is unknown, you can use @author Unknown.' );
		}
	}
}
