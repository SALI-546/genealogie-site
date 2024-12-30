<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Person extends Model
{
    use HasFactory;

    protected $fillable = [
        'created_by',
        'first_name',
        'last_name',
        'birth_name',
        'middle_names',
        'date_of_birth',
    ];

    // Relation avec l'utilisateur créateur
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    // Relation avec les enfants
    public function children()
    {
        return $this->belongsToMany(Person::class, 'relationships', 'parent_id', 'child_id');
    }

    // Relation avec les parents
    public function parents()
    {
        return $this->belongsToMany(Person::class, 'relationships', 'child_id', 'parent_id');
    }

    /**
     * Calcule le degré de parenté avec une autre personne.
     *
     * @param int $target_person_id
     * @return int|false
     */
    public function getDegreeWith($target_person_id)
{
    if ($this->id == $target_person_id) {
        return 0;
    }

    $visited = [];
    $queue = [];
    $degree = 0;

    // Initialiser la queue avec la personne courante
    $queue[] = ['id' => $this->id, 'degree' => 0];
    $visited[$this->id] = true;

    while (!empty($queue)) {
        $current = array_shift($queue);
        $currentId = $current['id'];
        $currentDegree = $current['degree'];

        // Limiter le degré à 25
        if ($currentDegree >= 25) {
            return false;
        }

        // Récupérer les parents et enfants
        $relatedIds = DB::table('relationships')
            ->where('parent_id', $currentId)
            ->orWhere('child_id', $currentId)
            ->get()
            ->map(function ($relationship) use ($currentId) {
                return ($relationship->parent_id == $currentId) ? $relationship->child_id : $relationship->parent_id;
            })
            ->toArray();

        foreach ($relatedIds as $relatedId) {
            if ($relatedId == $target_person_id) {
                return $currentDegree + 1;
            }

            if (!isset($visited[$relatedId])) {
                $visited[$relatedId] = true;
                $queue[] = ['id' => $relatedId, 'degree' => $currentDegree + 1];
            }
        }
    }

    // Si aucune connexion trouvée dans le seuil de 25
    return false;
}

}
