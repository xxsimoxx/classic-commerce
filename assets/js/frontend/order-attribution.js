( function ( wc_order_attribution ) {
	'use strict';
	// Cache params reference for shorter reusability.
	const params = wc_order_attribution.params;

	// Helper functions.
	const $ = document.querySelector.bind( document );
	const propertyAccessor = ( obj, path ) => path.split( '.' ).reduce( ( acc, part ) => acc && acc[ part ], obj );
	const returnNull = () => null;
	const stringifyFalsyInputValue = ( value ) => value === null || value === undefined ? '' : value;

	/**
	 * Get the order attribution data.
	 *
	 * @returns {Object} Schema compatible object.
	 */
	function getData() {
		const entries = Object.entries( wc_order_attribution.fields )
				.map( ( [ key, property ] ) => [ key, accessor( sbjs.get, property ) ] );
		return Object.fromEntries( entries );
	}

	/**
	 * Update `wc_order_attribution` input elements' values.
	 *
	 * @param {Object} values Object containing field values.
	 */
	function updateFormValues( values ) {
		// Update `<wc-order-attribution-inputs>` elements if any exist.
		for( const element of document.querySelectorAll( 'wc-order-attribution-inputs' ) ) {
			element.values = values;
		}

	};

	/**
	 * Define an element to contribute order attribute values to the enclosing form.
	 * To be used with the classic checkout.
	 */
	window.customElements.define( 'wc-order-attribution-inputs', class extends HTMLElement {
		// Our bundler version does not support private class members, so we use a convention of `_` prefix.
		// #values
		// #fieldNames
		constructor(){
			super();
			// Cache fieldNames available at the construction time, to avoid malformed behavior if they change in runtime.
			this._fieldNames = Object.keys( wc_order_attribution.fields );
			// Allow values to be lazily set before CE upgrade.
			if ( this.hasOwnProperty( '_values' ) ) {
			  let values = this.values;
			  // Restore the setter.
			  delete this.values;
			  this.values = values || {};
			}
		}
		/**
		 * Stamp input elements to the element's light DOM.
		 *
		 * We could use `.elementInternals.setFromValue` and avoid sprouting `<input>` elements,
		 * but it's not yet supported in Safari.
		 */
		connectedCallback() {
            this.innerHTML = '';
			const inputs = new DocumentFragment();
			for( const fieldName of this._fieldNames ) {
                const input = document.createElement( 'input' );
				input.type = 'hidden';
				input.name = `${params.prefix}${fieldName}`;
				input.value = stringifyFalsyInputValue( ( this.values && this.values[ fieldName ] ) || '' );
				inputs.appendChild( input );
			}
			this.appendChild( inputs );
		}

		/**
		 * Update form values.
		 */
		set values( values ) {
			this._values = values;
			if( this.isConnected ) {
				for( const fieldName of this._fieldNames ) {
					const input = this.querySelector( `input[name="${params.prefix}${fieldName}"]` );
					if( input ) {
						input.value = stringifyFalsyInputValue( this.values[ fieldName ] );
					} else {
						console.warn( `Field "${fieldName}" not found. Most likely, the '<wc-order-attribution-inputs>' element was manipulated.`);
					}
				}
			}
		}
		get values() {
			return this._values;
		}
	} );


}( window.wc_order_attribution ) );
