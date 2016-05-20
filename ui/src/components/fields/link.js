import React, { Component, PropTypes } from 'react';
import classNames from 'classnames';
import _ from 'lodash';

import styles from './link.pcss';

const TARGET_OPTIONS = [
	{
		target: '',
		target_label: 'Stay in Window',
	},
	{
		target: '_blank',
		target_label: 'Open New Window',
	},
];

class Link extends Component {
	constructor(props) {
		super(props);
		this.handleChange = this.handleChange.bind(this);
	}

	handleChange() {
		// code to connect to actions that execute on redux store
	}

	render() {
		const Options = _.map(TARGET_OPTIONS, (option) =>
			<option value={option.target} key={_.uniqueId('link-option-id-')} >{option.target_label}</option>
		);

		// styles
		const targetClasses = classNames({
			[styles.inputGeneric]: true,
			[styles.inputTarget]: true,
			'pllink-target': true,
		});
		const urlClasses = classNames({
			[styles.inputGeneric]: true,
			'pllink-url': true,
		});
		const labelClasses = classNames({
			[styles.inputGeneric]: true,
			'pllink-label': true,
		});

		return (
			<div className={styles.panel}>
				<fieldset className={styles.fieldset}>
					<legend className={styles.label}>{this.props.label}</legend>
					<span className={urlClasses}>
						<input type="text" name={this.props.name} value={this.props.default.url} size="40" placeholder="URL" />
					</span>
					<span className={labelClasses}>
						<input type="text" name={this.props.name} value={this.props.default.label} size="40" placeholder="Label" />
					</span>
					<span className={targetClasses}>
						<select defaultValue={this.props.default.target} >
							{Options}
						</select>
					</span>
					<p className={styles.description}>{this.props.description}</p>
				</fieldset>
			</div>
		);
	}
}

Link.propTypes = {
	label: PropTypes.string,
	name: PropTypes.string,
	description: PropTypes.string,
	strings: PropTypes.array,
	default: PropTypes.object,
};

Link.defaultProps = {
	label: '',
	name: '',
	description: '',
	strings: [],
	default: '',
};

export default Link;