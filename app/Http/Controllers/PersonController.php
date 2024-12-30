<?php

namespace App\Http\Controllers;

use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PersonController extends Controller
{
    /**
     * Afficher la liste des personnes avec le nom de l'utilisateur qui les a créées.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        // Charger les personnes avec leur créateur
        $people = Person::with('creator')->paginate(10);
        return view('people.index', compact('people'));
    }

    /**
     * Afficher une personne spécifique avec la liste de ses enfants et parents.
     *
     * @param  int  $id
     * @return \Illuminate\View\View
     */
    public function show($id)
    {
        // Charger la personne avec ses enfants et parents
        $person = Person::with(['children', 'parents'])->findOrFail($id);
         // Définir l'ID de la personne cible (1265 dans ce cas)
    $targetPersonId = 1265;
    
    // Calculer le degré de parenté avec la personne cible
    $degree = $person->getDegreeWith($targetPersonId);
    
    // Optionnel : Récupérer le chemin de parenté si nécessaire
    // Vous pouvez modifier la méthode getDegreeWith pour retourner le chemin si besoin

    return view('people.show', compact('person', 'degree', 'targetPersonId'));
    }

    /**
     * Afficher le formulaire de création d'une nouvelle personne.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('people.create');
    }

    /**
     * Valider les données de création et insérer une nouvelle personne.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validation des données entrantes
        $validated = $request->validate([
            'first_name'    => 'required|string|max:255',
            'last_name'     => 'required|string|max:255',
            'birth_name'    => 'nullable|string|max:255',
            'middle_names'  => 'nullable|string|max:255',
            'date_of_birth' => 'nullable|date',
        ]);

        // Formater les données selon les règles spécifiées
        $validated['created_by'] = Auth::id(); // ID de l'utilisateur authentifié

        // Première lettre en majuscule, le reste en minuscules
        $validated['first_name'] = ucfirst(strtolower($validated['first_name']));

        // Formatage des prénoms du milieu
        if (!empty($validated['middle_names'])) {
            $validated['middle_names'] = implode(', ', array_map(function($name) {
                return ucfirst(strtolower(trim($name)));
            }, explode(',', $validated['middle_names'])));
        } else {
            $validated['middle_names'] = null;
        }

        // Toutes les lettres en majuscules
        $validated['last_name'] = strtoupper($validated['last_name']);

        // Toutes les lettres en majuscules ou copie du last_name
        $validated['birth_name'] = !empty($validated['birth_name']) ? strtoupper($validated['birth_name']) : strtoupper($validated['last_name']);

        // Format de date ou NULL
        $validated['date_of_birth'] = $validated['date_of_birth'] ?? null;

        // Création de la nouvelle personne
        Person::create($validated);

        // Redirection avec message de succès
        return redirect()->route('people.index')->with('success', 'Personne créée avec succès.');
    }
}
