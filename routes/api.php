<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\ProduitController;
use App\Http\Controllers\Api\CategorieController;
use App\Http\Controllers\Api\PharmacieController;
use App\Http\Controllers\Api\ProprietaireController;
use App\Http\Controllers\Api\AdministrateurController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->group(function(){

    Route::prefix('pharmacie')->group(function() {
        Route::post('/ajouterPharmacie', [PharmacieController::class, 'ajouterPharmacie']);
        Route::put('/modifierPharmacie/{pharmacie}', [PharmacieController::class, 'modifierPharmacie']);
        Route::get('/detailsPharmacie/{id}', [PharmacieController::class, 'detailsPharmacie']);
        Route::delete('/supprimerPharmacie/{pharmacie}', [PharmacieController::class, 'supprimerPharmacie']);
    });

    Route::prefix('admin')->group(function() {
        Route::post('/ajouterProprietaire', [AdministrateurController::class, 'ajouterProprietaire']);
        Route::post('/regions', [AdministrateurController::class, 'ajoutRegion']);
        Route::post('/departement', [AdministrateurController::class, 'ajoutDepartement']);
        Route::post('/quartier', [AdministrateurController::class, 'ajoutQuartier']);
        Route::post('/bloquer/{proprietaireId}', [AdministrateurController::class, 'bloquerDebloquerProprietaire']);
        Route::get('/listeProprietaires', [AdministrateurController::class, 'listeProprietaires']);
    });

    Route::prefix('proprietaire')->group(function() {

        Route::post('/ajouterAgentPharmacie/{pharmacie}', [ProprietaireController::class, 'ajouterAgentPharmacie']);
        Route::get('/listerMesPharmacies', [ProprietaireController::class, 'listerMesPharmacies']);
        Route::get('/listerMesAgentPharmacies', [ProprietaireController::class, 'listerMesAgentPharmacie']);
        Route::post('/bloquerAgent/{agentId}', [ProprietaireController::class, 'bloquerDebloquerAgentPharmacie']);
        
        Route::post('/enregistrerHoraire/{pharmacie}', [ProprietaireController::class, 'enregistrerHorairesPharmacie']);
        Route::put('/modifierHoraire/{pharmacie}', [ProprietaireController::class, 'modifierHorairesPharmacie']);

        Route::post('/ajouterCategorie/{pharmacie}', [ProprietaireController::class, 'ajouterCategorieParPharmacie']);
        Route::put('/modifierCategorie/{pharmacieId}/categories/{categorieId}', [ProprietaireController::class, 'modifierCategorieParPharmacie']);
        Route::delete('/supprimerCategorie/{pharmacie}/categories/{categorieId}', [ProprietaireController::class, 'supprimerCategorieParPharmacie']);
        Route::get('/listerCategories/{pharmacieId}', [ProprietaireController::class, 'listerCategoriesParPharmacie']);
        
        Route::get('/listerHorairesPharmacie/{pharmacieId}', [ProprietaireController::class, 'listerHorairesPharmacie']);
        Route::post('/definirStatutGardePharmacie/{pharmacieid}', [ProprietaireController::class, 'definirStatutGardePharmacie']);
        Route::get('/obtenirStatutGardePharmacie/{pharmacieId}', [ProprietaireController::class, 'obtenirStatutGardePharmacie']);
        
        Route::post('/ajouterProduit/{pharmacie}', [ProprietaireController::class, 'ajouterProduitParPharmacie']);
        Route::put('/modifierProduit/{pharmacieId}/produits/{produitsId}', [ProprietaireController::class, 'modifierProduitParPharmacie']);
        Route::delete('/supprimerProduit/{pharmacie}/produits/{produitsId}', [ProprietaireController::class, 'supprimerProduitParPharmacie']);
        Route::get('/listerProduits/{pharmacieId}', [ProprietaireController::class, 'listerProduitsParPharmacie']);

    });

    Route::prefix('agentPharmacie')->group(function() {
        Route::post('/ajouterCategorie', [CategorieController::class, 'ajouterCategorie']);
        Route::put('/modifierCategorie/{categorie}', [CategorieController::class, 'modifierCategorie']);
        Route::get('/listerCategories', [CategorieController::class, 'listerCategories']);
        Route::delete('/supprimerCategorie/{categorie}', [CategorieController::class, 'supprimerCategorie']);
        Route::post('/ajouterProduit', [ProduitController::class, 'ajouterProduit']);
        Route::delete('/supprimerProduit/{produit}', [ProduitController::class, 'supprimerProduit']);
        Route::get('/listerProduits', [ProduitController::class, 'listerProduits']);
        Route::put('/modifierProduit/{produit}', [ProduitController::class, 'modifierProduit']);

    });
    // Ajout Produit

    //Retourner l'utilisateur actuelle connecté
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
});


// Route des users
Route::post('/register', [UserController::class, 'register']);
Route::post('/login', [UserController::class, 'login']);
// Lister  les pharmacies
Route::get('/pharmacie', [PharmacieController::class, 'listerPharmacies']);

// Bearer
