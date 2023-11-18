<?php

namespace App\Http\Controllers\Api;

use App\Models\Pharmacie;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\AjouterPharmacieRequest;
use App\Http\Requests\ModifierPharmacieRequest;
use Exception;

class PharmacieController extends Controller
{
    
    public function ajouterPharmacie(AjouterPharmacieRequest $request)
    {
        try {
            // Vérifiez d'abord si l'utilisateur est connecté
            if (auth()->check()) {
                $user = auth()->user();

                // Vérifiez ensuite si l'utilisateur a le profil "propriétaire"
                if ($user->profile === 'proprietaire') {
                    $pharmacie = new Pharmacie();
                    $pharmacie->nom = $request->nom;
                    if ($request->hasFile('photo')) {
                        $imagePath = $request->file('photo');
                        $extension = $imagePath->getClientOriginalExtension();
                        $filename = time() . '.' . $extension;
                        $imagePath->move('images/', $filename);
                        $pharmacie->photo = $filename;
                    }
                    $pharmacie->adresse = $request->adresse;
                    $pharmacie->telephone = $request->telephone;
                    $pharmacie->fax = $request->fax;
                    $pharmacie->latitude = $request->latitude;
                    $pharmacie->longitude = $request->longitude;
                    $pharmacie->proprietaire_id = $user->id;
                    $pharmacie->quartier_id = $request->quartier_id;
                    $pharmacie->save();
                    return response()->json([
                        'status_code' => 200,
                        'status_message' => 'La pharmacie a été ajoutée avec succès.',
                        'data' => $pharmacie
                    ], 200);
                } else {
                    return response()->json([
                        'status_code' => 403,
                        'status_message' => 'Vous n\'avez pas le profil "propriétaire" pour ajouter une pharmacie.'
                    ], 403);
                }
            } else {
                return response()->json([
                    'status_code' => 401,
                    'status_message' => 'Vous devez être connecté pour ajouter une pharmacie.'
                ], 401);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'status_message' => 'Erreur lors de l\'ajout de la pharmacie.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function ajouterPharmaciejkl(AjouterPharmacieRequest $request)
    {
        try {
            // Vérifiez d'abord si l'utilisateur est connecté
            if (auth()->check()) {
                $user = auth()->user();

                // Vérifiez ensuite si l'utilisateur a le profil "propriétaire"
                if ($user->profile === 'proprietaire') {
                    $pharmacie = new Pharmacie();
                    $pharmacie->nom = $request->nom;
                    dd($request);
                    if ($request->hasFile('photo')) {
                        $imagePath = $request->file('photo');
                        $extension = $imagePath->getClientOriginalExtension();
                        $filename = time() . '.' . $extension;
                        $imagePath->move('images/', $filename);
                        $pharmacie->photo = $filename;
                    }
                    $pharmacie->adresse = $request->adresse;
                    $pharmacie->telephone = $request->telephone;
                    $pharmacie->fax = $request->fax;
                    $pharmacie->latitude = $request->latitude;
                    $pharmacie->longitude = $request->longitude;
                    $pharmacie->proprietaire_id = $user->id;
                    $pharmacie->quartier_id = $request->quartier_id;
                    $pharmacie->save();
                    return response()->json([
                        'status_code' => 200,
                        'status_message' => 'La pharmacie a été ajoutée avec succès.',
                        'data' => $pharmacie
                    ], 200);
                } else {
                    return response()->json([
                        'status_code' => 403,
                        'status_message' => 'Vous n\'avez pas le profil "propriétaire" pour ajouter une pharmacie.'
                    ], 403);
                }
            } else {
                return response()->json([
                    'status_code' => 401,
                    'status_message' => 'Vous devez être connecté pour ajouter une pharmacie.'
                ], 401);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'status_message' => 'Erreur lors de l\'ajout de la pharmacie.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function listerPharmacies(Request $request)
    {
        try {
            
            $query = Pharmacie::query();
            $perPage = 12;
            $page = $request->input('page', 1);
            $search = $request->input('search');

            if ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('nom', 'LIKE', "%$search%")
                        ->orWhere('adresse', 'LIKE', "%$search%");
                });
            }

            $total = $query->count();
            $resultat = $query->offset(($page - 1) * $perPage)->limit($perPage)->get();

            return response()->json([
                'status_code' => 200,
                'status_message' => 'Les pharmacies ont été récupérées',
                'current_page' => $page,
                'last_page' => ceil($total / $perPage),
                'items' => $resultat
            ]);
        } catch (Exception $e) {
            return response()->json($e);
        }
    }


    public function supprimerPharmacie(Pharmacie $pharmacie)
    {
        try {
            // Vérifiez si l'utilisateur connecté est autorisé à supprimer cette pharmacie
            if (auth()->user()->id === $pharmacie->proprietaire_id) {
                $pharmacie->delete();
                return response()->json([
                    'status_code' => 200,
                    'status_message' => 'La pharmacie a été supprimée avec succès.',
                    'data' => $pharmacie
                ]);
            } else {
                return response()->json([
                    'status_code' => 403,
                    'status_message' => 'Vous n\'êtes pas autorisé à supprimer cette pharmacie.'
                ], 403);
            }
        } catch (Exception $e) {
            return response()->json([
                'status_code' => 500,
                'status_message' => 'Erreur lors de la suppression de la pharmacie.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function modifierPharmacie(ModifierPharmacieRequest $request, $id)
    {
        try {
            // Vérifiez d'abord si l'utilisateur est connecté
            if (auth()->check()) {
                $user = auth()->user();

                // Récupérez la pharmacie à modifier
                $pharmacie = Pharmacie::find($id);

                if ($pharmacie) {
                    // Vérifiez si l'utilisateur est le propriétaire de cette pharmacie
                    if ($user->id === $pharmacie->proprietaire_id) {
                        // Mettez à jour les détails de la pharmacie
                        $pharmacie->nom = $request->nom;
                        if ($request->hasFile('photo')) {
                            $imagePath = $request->file('photo');
                            $extension = $imagePath->getClientOriginalExtension();
                            $filename = time() . '.' . $extension;
                            $imagePath->move('images/', $filename);
                            $pharmacie->photo = $filename;
                        }
                        $pharmacie->adresse = $request->adresse;
                        $pharmacie->telephone = $request->telephone;
                        $pharmacie->fax = $request->fax;
                        $pharmacie->latitude = $request->latitude;
                        $pharmacie->longitude = $request->longitude;
                        $pharmacie->quartier_id = $request->quartier_id;
                        $pharmacie->save();

                        return response()->json([
                            'status_code' => 200,
                            'status_message' => 'La pharmacie a été modifiée avec succès.',
                            'data' => $pharmacie
                        ], 200);
                    } else {
                        return response()->json([
                            'status_code' => 403,
                            'status_message' => 'Vous n\'êtes pas autorisé à modifier cette pharmacie.'
                        ], 403);
                    }
                } else {
                    return response()->json([
                        'status_code' => 404,
                        'status_message' => 'La pharmacie que vous essayez de modifier est introuvable.'
                    ], 404);
                }
            } else {
                return response()->json([
                    'status_code' => 401,
                    'status_message' => 'Vous devez être connecté pour modifier une pharmacie.'
                ], 401);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status_code' => 500,
                'status_message' => 'Erreur lors de la modification de la pharmacie.',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function detailsPharmacie($id)
    {
        try {
            $pharmacie = Pharmacie::find($id);
    
            if (!$pharmacie) {
                return response()->json([
                    'status_code' => 404,
                    'status_message' => 'Pharmacie non trouvée',
                ], 404);
            }
    
            return response()->json([
                'status_code' => 200,
                'status_message' => 'Détails de la pharmacie récupérés avec succès',
                'pharmacie' => $pharmacie,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status_code' => 500,
                'status_message' => 'Erreur serveur lors de la récupération des détails de la pharmacie',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
