<?php

namespace Test\Unit\Product\Domain\ValueObjects;

use App\Product\Domain\ValueObjects\CategoryId;
use PHPUnit\Framework\TestCase;

class CategoryIdTest extends TestCase
{
    private CategoryId $categoryId;

    protected function setUp(): void
    {
        $this->categoryId = new CategoryId('valid-id');
    }

    public function testCategoryIdCanBeCreatedWithValidId(): void
    {
        $this->assertEquals('valid-id', $this->categoryId->value);
    }

    public function testCategoryIdMatchesReturnsTrueWhenIdsMatch(): void
    {
        $matchingCategoryId = new CategoryId('valid-id');

        $this->assertTrue($this->categoryId->matches($matchingCategoryId));
    }

    public function testCategoryIdMatchesReturnsFalseWhenIdsDoNotMatch(): void
    {
        $nonMatchingCategoryId = new CategoryId('non-matching-id');

        $this->assertFalse($this->categoryId->matches($nonMatchingCategoryId));
    }
}
