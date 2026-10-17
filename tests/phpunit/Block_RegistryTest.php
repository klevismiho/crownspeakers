<?php

namespace CS\Tests;

class Block_StructureTest extends TestCase {

	public function test_all_blocks_have_required_files(): void {

		$blocks_dir = HERU_THEME_DIR . '/blocks';

		$required_files = array(
			'block.json',
			'edit.js',
			'index.js',
			'save.js',
			'view.js',
			'render.php',
			'editor.scss',
			'style.scss',
		);

		foreach ( glob( $blocks_dir . '/*', GLOB_ONLYDIR ) as $block_path ) {

			foreach ( $required_files as $file ) {

				$this->assertFileExists(
					$block_path . '/' . $file,
					sprintf(
						'Missing %s in block %s',
						$file,
						basename( $block_path )
					)
				);
			}
		}
	}
}