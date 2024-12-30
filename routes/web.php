<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PersonController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TestController;
use App\Http\Controllers\InvitationController;
use App\Http\Controllers\ModificationProposalController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Rediriger la racine vers la liste des personnes
Route::get('/', function () {
    return redirect()->route('people.index');
});

// Tableau de bord protégé par authentification et vérification d'email
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Routes protégées par authentification pour la gestion du profil
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Routes pour la gestion des personnes (CRUD)
Route::resource('people', PersonController::class)->middleware('auth');
Route::get('/test-degree', [TestController::class, 'testDegree']);

// Invitations
Route::middleware(['auth'])->group(function () {
    Route::post('/invitations', [InvitationController::class, 'sendInvitation'])->name('invitations.send');
    Route::get('/invitations/accept/{token}', [InvitationController::class, 'acceptInvitation'])->name('invitations.accept');
});

// Propositions de Modifications
Route::middleware(['auth'])->group(function () {
    Route::post('/proposals/{person_id}', [ModificationProposalController::class, 'proposeModification'])->name('proposals.propose');
    Route::post('/proposals/{proposal_id}/vote', [ModificationProposalController::class, 'vote'])->name('proposals.vote');
});


// Inclure les routes d'authentification de Breeze
require __DIR__.'/auth.php';
