<?php
/**
 * A class of utilities for dealing with strings.
 */

namespace ClassicCommerce\Utilities;

/**
 * A class of utilities for dealing with strings.
 */
final class StringUtil {

	/**
	 * Checks to see whether or not a string starts with another.
	 *
	 * @param string $string The string we want to check.
	 * @param string $starts_with The string we're looking for at the start of $string.
	 * @param bool   $case_sensitive Indicates whether the comparison should be case-sensitive.
	 *
	 * @return bool True if the $string starts with $starts_with, false otherwise.
	 */
	public static function starts_with( string $string, string $starts_with, bool $case_sensitive = true ): bool {
		$len = strlen( $starts_with );
		if ( $len > strlen( $string ) ) {
			return false;
		}

		$string = substr( $string, 0, $len );

		if ( $case_sensitive ) {
			return strcmp( $string, $starts_with ) === 0;
		}

		return strcasecmp( $string, $starts_with ) === 0;
	}

	/**
	 * Checks to see whether or not a string ends with another.
	 *
	 * @param string $string The string we want to check.
	 * @param string $ends_with The string we're looking for at the end of $string.
	 * @param bool   $case_sensitive Indicates whether the comparison should be case-sensitive.
	 *
	 * @return bool True if the $string ends with $ends_with, false otherwise.
	 */
	public static function ends_with( string $string, string $ends_with, bool $case_sensitive = true ): bool {
		$len = strlen( $ends_with );
		if ( $len > strlen( $string ) ) {
			return false;
		}

		$string = substr( $string, -$len );

		if ( $case_sensitive ) {
			return strcmp( $string, $ends_with ) === 0;
		}

		return strcasecmp( $string, $ends_with ) === 0;
	}

    	/**
	 * Check if a string is null or is empty.
	 *
	 * @param string|null $value The string to check.
	 * @return bool True if the string is null or is empty.
	 */
	public static function is_null_or_empty( ?string $value ) {
		return is_null( $value ) || '' === $value;
	}

	/**
	 * Check if a string is null, is empty, or has only whitespace characters
	 * (space, tab, vertical tab, form feed, carriage return, new line)
	 *
	 * @param string|null $value The string to check.
	 * @return bool True if the string is null, is empty, or contains only whitespace characters.
	 */
	public static function is_null_or_whitespace( ?string $value ) {
		return is_null( $value ) || '' === $value || ctype_space( $value );
	}
}
