<?php

namespace Heru\Tests;

use Heru\Theme\Post_Type_People;

class Post_Type_People_Test extends TestCase
{
	public function test_class_can_be_created()
	{
		$post_type = new Post_Type_People();

		$this->assertInstanceOf(
			Post_Type_People::class,
			$post_type
		);
	}
}