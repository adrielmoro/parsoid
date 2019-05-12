<?php
declare( strict_types = 1 );

namespace Parsoid\Html2Wt\DOMHandlers;

use DOMElement;
use Parsoid\Html2Wt\SerializerState;

class AHandler extends DOMHandler {

	public function __construct() {
		parent::__construct( false );
	}

	/** @inheritDoc */
	public function handle(
		DOMElement $node, SerializerState $state, bool $wrapperUnmodified = false
	): ?DOMElement {
		$state->serializer->linkHandler( $node );
		return null;
	}

	// TODO: Implement link tail escaping with nowiki in DOM handler!
}