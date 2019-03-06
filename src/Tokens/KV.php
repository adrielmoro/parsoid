<?php
declare( strict_types = 1 );

namespace Parsoid\Tokens;

/**
 * Represents a Key-value pair.
 */
class KV implements \JsonSerializable {
	/** @var mixed Commonly a string, but where the key might be templated,
	 *  this can be an array of tokens even. */
	public $k;

	/** @var mixed string, Token, or an array of tokens even */
	public $v;

	/** @var int[]|null wikitext source offsets (Unicode char units) */
	public $srcOffsets;

	/** @var string|null wikitext source */
	public $ksrc;

	/** @var string|null wikitext source */
	public $vsrc;

	/**
	 * @param mixed $k
	 *     Commonly a string, but where the key might be templated,
	 *     this can be an array of tokens even.
	 * @param mixed $v
	 *     The value: string, token, of an array of tokens
	 * @param int[]|null $srcOffsets wikitext source offsets (Unicode char units)
	 * @param mixed|null $ksrc
	 * @param mixed|null $vsrc
	 */
	public function __construct( $k, $v, $srcOffsets = null, $ksrc = null, $vsrc = null ) {
		$this->k = $k;
		$this->v = $v;
		$this->srcOffsets = $srcOffsets;
		if ( $ksrc ) {
			$this->ksrc = $ksrc;
		}
		if ( $vsrc ) {
			$this->vsrc = $vsrc;
		}
	}

	/**
	 * Lookup a string key in a KV array and return the first matching KV object
	 *
	 * @param KV[]|null $kvs
	 * @param string $key
	 * @return KV|null
	 */
	public static function lookupKV( ?array $kvs, string $key ): ?KV {
		if ( $kvs === null ) {
			return null;
		}

		foreach ( $kvs as $kv ) {
			// PORT-FIXME: JS trim() will remove non-ASCII spaces (such as NBSP) too,
			// while PHP's won't. Does that matter?
			if ( is_string( $kv->k ) && trim( $kv->k ) === $key ) {
				return $kv;
			}
		}

		return null;
	}

	/**
	 * Lookup a string key (first occurrence) in a KV array
	 * and return the value of the KV object
	 *
	 * @param KV[]|null $kvs
	 * @param string $key
	 * @return mixed
	 */
	public static function lookup( ?array $kvs, string $key ) {
		$kv = self::lookupKV( $kvs, $key );
		// PORT_FIXME: Potential bug lurking here ... if $kv->v is an array
		// this will return a copy, which if modified will not reflect
		// in the original KV object.
		return $kv === null ? null : $kv->v;
	}

	/**
	 * @inheritDoc
	 */
	public function jsonSerialize(): array {
		$ret = [ "k" => $this->k, "v" => $this->v ];
		if ( $this->srcOffsets ) {
			$ret["srcOffsets"] = $this->srcOffsets;
		}
		if ( $this->ksrc ) {
			$ret["ksrc"] = $this->ksrc;
		}
		if ( $this->vsrc ) {
			$ret["vsrc"] = $this->vsrc;
		}
		return $ret;
	}
}
