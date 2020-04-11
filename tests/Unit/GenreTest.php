<?php
/**
 * Created by PhpStorm.
 * User: Herval
 * Date: 09/04/2020
 * Time: 21:58
 */

namespace Tests\Unit;


use App\Models\Genre;
use App\Models\Traits\Uuid;
use Illuminate\Database\Eloquent\SoftDeletes;
use Tests\TestCase;

class GenreTest extends TestCase
{
    private $genre;

    public function testIfUseTraits()
    {
        $traits = [
            SoftDeletes::class,
            Uuid::class
        ];

        $genreTraits = array_keys(class_uses(Genre::class));
        $this->assertEquals($traits, $genreTraits);
    }

    public function testFillableProperty()
    {
        $fillable = ['name', 'is_active'];
        $this->assertEquals($fillable, $this->genre->getFillable());
    }

    public function testDatesProperty()
    {
        $dates = ['deleted_at', 'created_at', 'updated_at'];
        foreach ($dates as $date) {
            $this->assertContains($date, $this->genre->getDates());
        }
        $this->assertCount(count($dates), $this->genre->getDates());
    }

    public function testCastsProperty()
    {
        $casts = [
            'id' => 'string',
            "is_active" => 'boolean'
        ];
        $this->assertEquals($casts, $this->genre->getCasts());
    }

    public function testIncrementingProperty()
    {
        $this->assertFalse($this->genre->incrementing);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->genre = new Genre();
    }
}
