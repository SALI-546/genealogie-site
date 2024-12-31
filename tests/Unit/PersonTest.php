<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Person;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;

class PersonTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_calculates_degree_of_relationship_correctly()
    {
        // Créer des personnes et des relations pour tester
        $personA = Person::create(['created_by' => 1, 'first_name' => 'A', 'last_name' => 'Smith']);
        $personB = Person::create(['created_by' => 1, 'first_name' => 'B', 'last_name' => 'Smith']);
        $personC = Person::create(['created_by' => 1, 'first_name' => 'C', 'last_name' => 'Smith']);

        // Créer des relations
        DB::table('relationships')->insert([
            ['created_by' => 1, 'parent_id' => $personA->id, 'child_id' => $personB->id],
            ['created_by' => 1, 'parent_id' => $personB->id, 'child_id' => $personC->id],
        ]);

        // Tester le degré entre A et C
        $degree = $personA->getDegreeWith($personC->id);
        $this->assertEquals(2, $degree);

        // Tester le degré entre A et A (0)
        $degreeSame = $personA->getDegreeWith($personA->id);
        $this->assertEquals(0, $degreeSame);

        // Tester le degré avec une personne non liée
        $personD = Person::create(['created_by' => 1, 'first_name' => 'D', 'last_name' => 'Smith']);
        $degreeFalse = $personA->getDegreeWith($personD->id);
        $this->assertFalse($degreeFalse);
    }
}
