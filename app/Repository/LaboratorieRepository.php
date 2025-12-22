<?php

namespace App\Repository;

use App\Models\Examen;
use App\Models\Laboratorie;
use App\Models\TypeBilan;
use App\Services\UploadService;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class LaboratorieRepository
{
    /**
     * @var Laboratorie
     */
    private $laboratorie;

    /**
     * @var UploadService
     */
    private $uploadService;

    public function __construct(Laboratorie $laboratorie, UploadService $uploadService)
    {
        $this->laboratorie = $laboratorie;
        $this->uploadService = $uploadService;
    }

    public function create_laboratorie(Request $request){
        $path = "defaut_image.jpg";

        if($request->image){
            try {
                $uploadResult = $this->uploadService->uploadImage(
                    $request->image,
                    config('uploads.storage.directories.laboratories'),
                    'laboratoire_' . time()
                );
                $path = $uploadResult['path'];

                // Lancer la compression en arrière-plan si activée
                if (config('uploads.compression.enabled')) {
                    \App\Jobs\ProcessImageCompression::dispatch($path);
                }

            } catch (\Exception $e) {
                // En cas d'erreur, utiliser l'image par défaut
                $path = "defaut_image.jpg";
            }
        }

        $laboratorie = $this->laboratorie->newQuery()->create([
            'name' => $request->name,
            'image' => $path,
            'address' => $request->address,
            'description' => $request->description,
            'pourcentage_commission' => $request->pourcentage_commission ?? 0,
        ]);

        return $laboratorie;
    }

    public function update_laboratorie(Request $request, string $id){

        $laboratorie = Laboratorie::where('id', $id)->first();
        $path = $laboratorie->image;

        if($request->image){
            try {
                $uploadResult = $this->uploadService->uploadImage(
                    $request->image,
                    config('uploads.storage.directories.laboratories'),
                    'laboratoire_' . time()
                );
                $path = $uploadResult['path'];

                // Supprimer l'ancienne image si elle n'est pas l'image par défaut
                if ($laboratorie->image && $laboratorie->image !== "defaut_image.jpg") {
                    $this->uploadService->deleteFile($laboratorie->image);
                }

                // Lancer la compression en arrière-plan si activée
                if (config('uploads.compression.enabled')) {
                    \App\Jobs\ProcessImageCompression::dispatch($path);
                }

            } catch (\Exception $e) {
                // En cas d'erreur, garder l'ancienne image
                $path = $laboratorie->image;
            }
        }

        $laboratorie->update([
            'name' => $request->name,
            'image' => $path,
            'address' => $request->address,
            'description' => $request->description,
            'pourcentage_commission' => $request->pourcentage_commission ?? $laboratorie->pourcentage_commission,
        ]);

        $laboratorie->save();

        return $laboratorie;
    }

    public function get_laboratorie():Builder
    {
        return $this->laboratorie->newQuery()
        ->orderBy('created_at', 'DESC');

    }

    public function get_examens(int $id):Builder
    {
        return Examen::where('laboratorie_id', $id)->orderBy('created_at', 'DESC');
    }

    public function get_bilans(int $id):Builder
    {
        return TypeBilan::where('laboratorie_id', $id)->orderBy('created_at', 'DESC');

    }

}