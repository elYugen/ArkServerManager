<?php

namespace App\Http\Controllers;

use App\Models\ArkServerConfig;
use App\Models\Logs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ShopController extends Controller
{
    /**
     * Display the shop management page
     */
    public function index()
    {
        $config = ArkServerConfig::first();
        
        if (!$config) {
            return redirect()->route('configuration.index')
                ->with('error', 'Veuillez d\'abord configurer votre serveur');
        }

        return view('dashboard.shop', compact('config'));
    }

    /**
     * Save the entire shop configuration (Kits and ShopItems)
     */
    public function save(Request $request)
    {
        $config = ArkServerConfig::first();
        
        if (!$config || !$config->shop_json_path) {
            return response()->json(['error' => 'Configuration introuvable'], 404);
        }

        if (!file_exists($config->shop_json_path)) {
            return response()->json(['error' => 'Fichier JSON introuvable'], 404);
        }

        try {
            $jsonContent = file_get_contents($config->shop_json_path);
            $currentConfig = json_decode($jsonContent, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json(['error' => 'Erreur de lecture JSON'], 500);
            }

            $newData = $request->all();

            if (isset($newData['Kits'])) {
                $currentConfig['Kits'] = $newData['Kits'];
            }

            if (isset($newData['ShopItems'])) {
                $currentConfig['ShopItems'] = $newData['ShopItems'];
            }

            $jsonOutput = json_encode($currentConfig, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            
            if (file_put_contents($config->shop_json_path, $jsonOutput) === false) {
                return response()->json(['error' => 'Impossible d\'écrire dans le fichier'], 500);
            }

            Logs::create([
                'user_id' => Auth::user()->id,
                'on_page' => 'Boutique',
                'logs'    => 'A sauvegardé la configuration du shop'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Configuration sauvegardée avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Erreur: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Get all kits from the shop configuration
     */
    public function getKits()
    {
        $config = ArkServerConfig::first();
        
        if (!$config || !$config->shop_json_path) {
            return response()->json(['error' => 'Configuration introuvable'], 404);
        }

        if (!file_exists($config->shop_json_path)) {
            return response()->json(['error' => 'Fichier JSON introuvable'], 404);
        }

        try {
            $jsonContent = file_get_contents($config->shop_json_path);
            $shopConfig = json_decode($jsonContent, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json(['error' => 'Erreur de lecture JSON'], 500);
            }
            
            return response()->json($shopConfig['Kits'] ?? []);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Get all shop items from the shop configuration
     */
    public function getItems()
    {
        $config = ArkServerConfig::first();
        
        if (!$config || !$config->shop_json_path) {
            return response()->json(['error' => 'Configuration introuvable'], 404);
        }

        if (!file_exists($config->shop_json_path)) {
            return response()->json(['error' => 'Fichier JSON introuvable'], 404);
        }

        try {
            $jsonContent = file_get_contents($config->shop_json_path);
            $shopConfig = json_decode($jsonContent, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json(['error' => 'Erreur de lecture JSON'], 500);
            }
            
            return response()->json($shopConfig['ShopItems'] ?? []);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Add a new kit to the shop configuration
     */
    public function addKit(Request $request)
    {
        $config = ArkServerConfig::first();
        
        if (!$config || !$config->shop_json_path) {
            return response()->json(['error' => 'Configuration introuvable'], 404);
        }

        if (!file_exists($config->shop_json_path)) {
            return response()->json(['error' => 'Fichier JSON introuvable'], 404);
        }

        try {
            $jsonContent = file_get_contents($config->shop_json_path);
            $shopConfig = json_decode($jsonContent, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json(['error' => 'Erreur de lecture JSON'], 500);
            }

            $kitName = $request->input('kit_name');
            $kitData = $request->input('kit_data');

            // Vérifier si le kit existe déjà
            if (isset($shopConfig['Kits'][$kitName])) {
                return response()->json([
                    'error' => 'Un kit avec ce nom existe déjà'
                ], 400);
            }

            // Ajouter le nouveau kit
            if (!isset($shopConfig['Kits'])) {
                $shopConfig['Kits'] = [];
            }
            
            $shopConfig['Kits'][$kitName] = $kitData;

            // Sauvegarder
            $jsonOutput = json_encode($shopConfig, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            
            if (file_put_contents($config->shop_json_path, $jsonOutput) === false) {
                return response()->json(['error' => 'Impossible d\'écrire dans le fichier'], 500);
            }

            Logs::create([
                'user_id' => Auth::user()->id,
                'on_page' => 'Boutique',
                'logs'    => 'A ajouté un kit au shop'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kit ajouté avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Add a new item to the shop configuration
     */
    public function addItem(Request $request)
    {
        $config = ArkServerConfig::first();
        
        if (!$config || !$config->shop_json_path) {
            return response()->json(['error' => 'Configuration introuvable'], 404);
        }

        if (!file_exists($config->shop_json_path)) {
            return response()->json(['error' => 'Fichier JSON introuvable'], 404);
        }

        try {
            $jsonContent = file_get_contents($config->shop_json_path);
            $shopConfig = json_decode($jsonContent, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json(['error' => 'Erreur de lecture JSON'], 500);
            }

            $itemId = $request->input('item_id');
            $itemData = $request->input('item_data');

            // Vérifier si l'item existe déjà
            if (isset($shopConfig['ShopItems'][$itemId])) {
                return response()->json([
                    'error' => 'Un article avec cet ID existe déjà'
                ], 400);
            }

            // Ajouter le nouvel item
            if (!isset($shopConfig['ShopItems'])) {
                $shopConfig['ShopItems'] = [];
            }
            
            $shopConfig['ShopItems'][$itemId] = $itemData;

            // Sauvegarder
            $jsonOutput = json_encode($shopConfig, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            
            if (file_put_contents($config->shop_json_path, $jsonOutput) === false) {
                return response()->json(['error' => 'Impossible d\'écrire dans le fichier'], 500);
            }

            Logs::create([
                'user_id' => Auth::user()->id,
                'on_page' => 'Boutique',
                'logs'    => 'A ajouté un item au shop'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Article ajouté avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete a kit from the shop configuration
     */
    public function deleteKit(Request $request, $kitName)
    {
        $config = ArkServerConfig::first();
        
        if (!$config || !$config->shop_json_path) {
            return response()->json(['error' => 'Configuration introuvable'], 404);
        }

        if (!file_exists($config->shop_json_path)) {
            return response()->json(['error' => 'Fichier JSON introuvable'], 404);
        }

        try {
            $jsonContent = file_get_contents($config->shop_json_path);
            $shopConfig = json_decode($jsonContent, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json(['error' => 'Erreur de lecture JSON'], 500);
            }

            // Vérifier si le kit existe
            if (!isset($shopConfig['Kits'][$kitName])) {
                return response()->json(['error' => 'Kit introuvable'], 404);
            }

            // Supprimer le kit
            unset($shopConfig['Kits'][$kitName]);

            // Sauvegarder
            $jsonOutput = json_encode($shopConfig, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            
            if (file_put_contents($config->shop_json_path, $jsonOutput) === false) {
                return response()->json(['error' => 'Impossible d\'écrire dans le fichier'], 500);
            }

            Logs::create([
                'user_id' => Auth::user()->id,
                'on_page' => 'Boutique',
                'logs'    => 'A supprimé un kit du shop'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kit supprimé avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Delete an item from the shop configuration
     */
    public function deleteItem(Request $request, $itemId)
    {
        $config = ArkServerConfig::first();
        
        if (!$config || !$config->shop_json_path) {
            return response()->json(['error' => 'Configuration introuvable'], 404);
        }

        if (!file_exists($config->shop_json_path)) {
            return response()->json(['error' => 'Fichier JSON introuvable'], 404);
        }

        try {
            $jsonContent = file_get_contents($config->shop_json_path);
            $shopConfig = json_decode($jsonContent, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json(['error' => 'Erreur de lecture JSON'], 500);
            }

            // Vérifier si l'item existe
            if (!isset($shopConfig['ShopItems'][$itemId])) {
                return response()->json(['error' => 'Article introuvable'], 404);
            }

            // Supprimer l'item
            unset($shopConfig['ShopItems'][$itemId]);

            // Sauvegarder
            $jsonOutput = json_encode($shopConfig, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            
            if (file_put_contents($config->shop_json_path, $jsonOutput) === false) {
                return response()->json(['error' => 'Impossible d\'écrire dans le fichier'], 500);
            }

            Logs::create([
                'user_id' => Auth::user()->id,
                'on_page' => 'Boutique',
                'logs'    => 'A supprimé un item du shop'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Article supprimé avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update a kit in the shop configuration
     */
    public function updateKit(Request $request, $kitName)
    {
        $config = ArkServerConfig::first();
        
        if (!$config || !$config->shop_json_path) {
            return response()->json(['error' => 'Configuration introuvable'], 404);
        }

        if (!file_exists($config->shop_json_path)) {
            return response()->json(['error' => 'Fichier JSON introuvable'], 404);
        }

        try {
            $jsonContent = file_get_contents($config->shop_json_path);
            $shopConfig = json_decode($jsonContent, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json(['error' => 'Erreur de lecture JSON'], 500);
            }

            // Vérifier si le kit existe
            if (!isset($shopConfig['Kits'][$kitName])) {
                return response()->json(['error' => 'Kit introuvable'], 404);
            }

            $kitData = $request->input('kit_data');
            
            // Mettre à jour le kit
            $shopConfig['Kits'][$kitName] = $kitData;

            // Sauvegarder
            $jsonOutput = json_encode($shopConfig, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            
            if (file_put_contents($config->shop_json_path, $jsonOutput) === false) {
                return response()->json(['error' => 'Impossible d\'écrire dans le fichier'], 500);
            }

            Logs::create([
                'user_id' => Auth::user()->id,
                'on_page' => 'Boutique',
                'logs'    => 'A mis à jour un kit du shop'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Kit mis à jour avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Update an item in the shop configuration
     */
    public function updateItem(Request $request, $itemId)
    {
        $config = ArkServerConfig::first();
        
        if (!$config || !$config->shop_json_path) {
            return response()->json(['error' => 'Configuration introuvable'], 404);
        }

        if (!file_exists($config->shop_json_path)) {
            return response()->json(['error' => 'Fichier JSON introuvable'], 404);
        }

        try {
            $jsonContent = file_get_contents($config->shop_json_path);
            $shopConfig = json_decode($jsonContent, true);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json(['error' => 'Erreur de lecture JSON'], 500);
            }

            // Vérifier si l'item existe
            if (!isset($shopConfig['ShopItems'][$itemId])) {
                return response()->json(['error' => 'Article introuvable'], 404);
            }

            $itemData = $request->input('item_data');
            
            // Mettre à jour l'item
            $shopConfig['ShopItems'][$itemId] = $itemData;

            // Sauvegarder
            $jsonOutput = json_encode($shopConfig, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            
            if (file_put_contents($config->shop_json_path, $jsonOutput) === false) {
                return response()->json(['error' => 'Impossible d\'écrire dans le fichier'], 500);
            }

            Logs::create([
                'user_id' => Auth::user()->id,
                'on_page' => 'Boutique',
                'logs'    => 'A mis à jour un item du shop'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Article mis à jour avec succès'
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}