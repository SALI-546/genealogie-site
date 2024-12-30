<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TestController extends Controller
{
    public function testDegree()
    {
        DB::enableQueryLog();
        $timestart = microtime(true);
        $person = Person::findOrFail(84);
        $degree = $person->getDegreeWith(1265);
        // Afficher le résultat, le temps d'exécution et le nombre de requêtes SQL
        return response()->json([
            "degree" => $degree,
            "time" => microtime(true) - $timestart,
            "nb_queries" => count(DB::getQueryLog())
        ]);
    }

   
}
