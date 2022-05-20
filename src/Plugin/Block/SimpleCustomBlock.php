<?php

/**
 * @file
 * Contains \Drupal\simple_custom_block\Plugin\Block
 */

namespace Drupal\simple_custom_block\Plugin\Block;

use Drupal\Core\Block\BlockBase;


/**
 * Provides a simple custom block
 *
 * @Block(
 *  id = "simple_custom_block",
 *  admin_label = @Translation("Simple Custom Block"),
 *  category = @Translation("Custom Block")
 * )
 */

class SimpleCustomBlock extends BlockBase{
	public function build(){
		$some_array = [
			0 => [
				'is_active' => 'active',
				'label' => 'lorem ipsum',
				'url' => 'http://google.com',
			],
			1 => [
				'is_active' => 'inactive',
				'label' => 'lorem ipsum',
				'url' => 'http://amazon.com',
			],
		];

		return [
			'#theme' => 'custom_block',
			'#active_tab' => 'some_string',
			'#body_text' => [
				'#markup' => 'some_html_string',
			],
			'#tabs' => $some_array,
		];
	}

}
