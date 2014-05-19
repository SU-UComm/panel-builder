<?php


namespace ModularContent\Fields;


abstract class Field {

	/** @var string $label The human-readable label for the field */
	protected $label = '';

	/** @var string $name The machine-readable name for the field*/
	protected $name = '';

	/** @var string $description A helpful description for the field */
	protected $description = '';

	protected $defaults = array(
		'name' => '',
		'label' => '',
		'description' => '',
	);

	public function __construct( $args = array() ) {
		// this allows subclasses to set defaults by overriding the property declaration
		foreach ( array_keys($this->defaults) as $key ) {
			if ( empty($this->defaults[$key]) && isset($this->$key) ) {
				$this->defaults[$key] = $this->$key;
			}
		}

		// merge defaults with passed args
		$args = wp_parse_args($args, $this->defaults);

		// only set properties that we know about
		foreach ( array_keys($this->defaults) as $key ) {
			$this->$key = $args[$key];
		}

		$this->sanitize_name();
	}

	protected function sanitize_name() {
		$this->name = preg_replace('/[^\w]/', '_', $this->name);
	}

	public function render() {
		$this->render_before();
		$this->render_label();
		$this->render_field();
		$this->render_description();
		$this->render_after();
	}

	/**
	 * @return string The form field name associated with this input
	 */
	public function get_name() {
		return $this->name;
	}

	/**
	 * Get a list of variables that should be passed on to views from this input
	 *
	 * The default implementation is pretty simple. Specific inputs may return
	 * more complicated data.
	 *
	 * @param int $post_id
	 * @return array
	 */
	public function get_vars( $post_id ) {
		$vars = array(
			$this->name => $this->get_value($post_id),
		);
		return $vars;
	}

	public function get_value() {
		return FALSE; // TODO
	}

	protected function render_before() {
		$this->render_opening_tag();
		$this->print_hasOwnProperty_statements();
	}

	protected function render_opening_tag() {
		printf('<div class="panel-input input-name-%s">', $this->esc_class($this->name));
	}

	protected function print_hasOwnProperty_statements() {
		$base = 'data.fields';
		$default = '{}';
		$parts = explode('.', $this->name);
		$number_of_parts = count($parts);
		$current = 0;
		foreach ( $parts as $p ) {
			$current++;
			if ( $current == $number_of_parts ) {
				$default = '""';
			}
			printf('<# if ( !%s.hasOwnProperty("%s") ) { %s.%s = %s; } #>', $base, $p, $base, $p, $default);
			$base .= '.'.$p;
		}
	}

	protected function render_label() {
		if ( !empty($this->label) ) {
			printf('<label class="panel-input-label">%s</label>', $this->label);
		}
	}

	protected function render_field() {
		printf('<span class="panel-input-field"><input name="%s" value="%s" /></span>', $this->get_input_name(), $this->get_input_value());
	}

	protected function render_description() {
		if ( $this->description ) {
			printf('<p class="description panel-input-description">%s</p>', $this->description);
		}
	}

	protected function render_after() {
		$this->render_closing_tag();
	}

	protected function render_closing_tag() {
		echo '</div>'."\n";
	}

	protected function get_input_name() {
		$parts = explode('.', $this->name);
		$name = '{{data.panel_id}}';
		foreach ( $parts as $p ) {
			$name .= '['.$p.']';
		}
		return $name;
	}

	protected function get_input_value() {
		return sprintf("{{data.fields.%s}}", $this->name);
	}

	protected function esc_class( $class ) {
		return esc_attr(preg_replace('/[^\w]/', '_', $class));
	}
} 