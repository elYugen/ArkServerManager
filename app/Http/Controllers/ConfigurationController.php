<?php

namespace App\Http\Controllers;

use App\Models\ArkServerConfig;
use Illuminate\Http\Request;

class ConfigurationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $config = ArkServerConfig::first();
        return view('dashboard.configuration', compact('config'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'ip' => 'required|ip',
            'port' => 'required|integer|min:1|max:65535',
            'password' => 'nullable|string|max:255',
            'shop_json_path' => 'required|string|max:500'
        ]);

        // Vérifier si une config existe déjà
        $config = ArkServerConfig::first();
        
        if ($config) {
            // Mettre à jour la config existante
            $config->update($validated);
            $message = 'Configuration mise à jour avec succès!';
        } else {
            // Créer une nouvelle config
            ArkServerConfig::create($validated);
            $message = 'Configuration créée avec succès!';
        }

        return redirect()->route('configuration.index')
            ->with('success', $message);
    }

    /**
     * Display the specified resource.
     */
    public function show(ArkServerConfig $arkServerConfig)
    {
        return response()->json($arkServerConfig);
    }

    /**
     * Load and return the shop JSON configuration
     */
    public function loadShopConfig()
    {
        $config = ArkServerConfig::first();
        
        if (!$config || !$config->shop_json_path) {
            return response()->json([
                'error' => 'Aucune configuration trouvée'
            ], 404);
        }

        if (!file_exists($config->shop_json_path)) {
            return response()->json([
                'error' => 'Le fichier de configuration est introuvable'
            ], 404);
        }

        try {
            $jsonContent = file_get_contents($config->shop_json_path);
            $shopConfig = json_decode($jsonContent, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'error' => 'Erreur de lecture du fichier JSON: ' . json_last_error_msg()
                ], 500);
            }
            
            return response()->json($shopConfig);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la lecture du fichier: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the shop JSON configuration
     */
    public function updateShopConfig(Request $request)
    {

        $config = ArkServerConfig::first();
        
        if (!$config || !$config->shop_json_path) {
            return response()->json([
                'error' => 'Aucune configuration trouvée'
            ], 404);
        }

        if (!file_exists($config->shop_json_path)) {
            return response()->json([
                'error' => 'Le fichier de configuration est introuvable'
            ], 404);
        }

        try {
            // Lire le fichier JSON actuel
            $jsonContent = file_get_contents($config->shop_json_path);
            $currentConfig = json_decode($jsonContent, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'error' => 'Erreur de lecture du fichier JSON'
                ], 500);
            }

            // Récupérer les nouvelles données
            $newData = $request->all();

            // Fusionner les données (mettre à jour uniquement les champs envoyés)
            if (isset($newData['Mysql'])) {
                $currentConfig['Mysql'] = array_merge($currentConfig['Mysql'] ?? [], $newData['Mysql']);
            }

            if (isset($newData['General'])) {
                // Gérer TimedPointsReward séparément
                if (isset($newData['General']['TimedPointsReward'])) {
                    if (!isset($currentConfig['General']['TimedPointsReward'])) {
                        $currentConfig['General']['TimedPointsReward'] = [];
                    }
                    $currentConfig['General']['TimedPointsReward'] = array_merge(
                        $currentConfig['General']['TimedPointsReward'],
                        $newData['General']['TimedPointsReward']
                    );
                    unset($newData['General']['TimedPointsReward']);
                }
                
                $currentConfig['General'] = array_merge($currentConfig['General'] ?? [], $newData['General']);
            }

            if (isset($newData['Messages'])) {
                $currentConfig['Messages'] = array_merge($currentConfig['Messages'] ?? [], $newData['Messages']);
            }

            // Sauvegarder le fichier avec une indentation propre
            $jsonOutput = json_encode($currentConfig, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            
            if (file_put_contents($config->shop_json_path, $jsonOutput) === false) {
                return response()->json([
                    'error' => 'Impossible d\'écrire dans le fichier'
                ], 500);
            }

            return response()->json([
                'success' => true,
                'message' => 'Configuration sauvegardée avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Erreur lors de la sauvegarde: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ArkServerConfig $arkServerConfig)
    {
        $validated = $request->validate([
            'ip' => 'required|ip',
            'port' => 'required|integer|min:1|max:65535',
            'password' => 'nullable|string|max:255',
            'shop_json_path' => 'required|string|max:500'
        ]);

        $arkServerConfig->update($validated);

        return redirect()->route('configuration.index')
            ->with('success', 'Configuration mise à jour avec succès!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ArkServerConfig $arkServerConfig)
    {
        $arkServerConfig->delete();

        return redirect()->route('configuration.index')
            ->with('success', 'Configuration supprimée avec succès!');
    }
}