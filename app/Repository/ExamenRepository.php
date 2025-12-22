<?php

namespace App\Repository;

use App\Http\Requests\Examen\ExamenRequest;
use App\Models\Examen;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ExamenRepository
{

    /**
     * @var Examen
     */
    private $examen;

    public function __construct(Examen $examen)
    {
        $this->examen = $examen;
    }

    public function create_Examen(Request $request){

        $path = "/examen/examen.jpg";
        if($request->icon){
            $image_url = time() . $request->icon->getClientOriginalName();
            $path = $request->icon->move(public_path() . "/examen", $image_url);
            $path = "examen/" . $image_url;
        }

        $examen = $this->examen->newQuery()->create([

            'label' => $request->label,
            'laboratorie_id' => isset($request->laboratorie_id) ? $request->laboratorie_id : null,
            'icon' => $path,
            'price' => $request->price,
            'description' => $request->description,

        ]);

        return $examen;
    }

    public function update_Examen(Request $request, string $id){

        $examen = Examen::where('id', $id)->first();
        if($request->icon && $request->icon != $examen->icon){
            $image_url = time() . $request->icon->getClientOriginalName();
            $path = $request->icon->move(public_path() . "/examen", $image_url);
            $path = "examen/" . $image_url;
        }
        else{
            $path = $request->icon;
        }
        $examen->update([
            'label' => $request->label,
            'laboratorie_id' => isset($request->laboratorie_id) ? $request->laboratorie_id : null,
            'icon' => $path,
            'price' => $request->price,
            'description' => $request->description,
        ]);

        $examen->save();

        return $examen;
    }

    public function get_Examen(){
        return $this->examen->newQuery()
        // ->where('isactive', true)
        ->orderBy('created_at', 'DESC')
        ->with("laboratorie")
        ->paginate(3);
    }

}
