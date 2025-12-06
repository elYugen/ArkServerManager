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

        // Vérifier si le fichier existe
        if (!file_exists($config->shop_json_path)) {
            return response()->json([
                'error' => 'Le fichier de configuration est introuvable à l\'emplacement spécifié'
            ], 404);
        }

        // Lire le contenu du fichier JSON
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