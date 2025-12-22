<?php

namespace App\Repository;

use App\Models\Examen;
use App\Models\Laboratorie;
use App\Models\TypeBilan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class LaboratorieRepository
{

    /**
     * @var Laboratorie
     */
    private $laboratorie;

    public function __construct(Laboratorie $laboratorie)
    {
        $this->laboratorie = $laboratorie;
    }

    public function create_laboratorie(Request $request){
        $path = "defaut_image.jpg";
        if($request->image){
            $image_url = time() . $request->image->getClientOriginalName();
            $path = $request->image->move(public_path() . "/laboratoire", $image_url);
            $path = "laboratoire/" . $image_url;
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
        if($request->image){
            $image_url = time() . $request->image->getClientOriginalName();
            $path = $request->image->move(public_path() . "/laboratoire", $image_url);
            $path = "laboratoire/" . $image_url;
        }else{
            $path = $laboratorie->image;
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