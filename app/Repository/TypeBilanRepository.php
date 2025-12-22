<?php

namespace App\Repository;

use App\Http\Requests\Examen\TypeBilanRequest;
use App\Models\TypeBilan;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class TypeBilanRepository
{

    /**
     * @var TypeBilan
     */
    private $typeBilan;

    public function __construct(TypeBilan $typeBilan)
    {
        $this->typeBilan = $typeBilan;
    }

    public function create_TypeBilan(Request $request)
    {

        $path = "/typebilan/bilan.jpg";
        if($request->icon){
            $image_url = time() . $request->icon->getClientOriginalName();
            $path = $request->icon->move(public_path() . "/typeBilan", $image_url);
            $path = "typeBilan/" . $image_url;
        }


        $typeBilan = TypeBilan::create([

            'label' => $request->label,
            'laboratorie_id' => isset($request->laboratorie_id) ? $request->laboratorie_id : null,
            'icon' => $path,
            'price' => $request->price,
            'description' => $request->description,
        ]);
        return $typeBilan;
    }

    public function update_TypeBilan(Request $request, string $id)
    {

        $typeBilan = TypeBilan::where('id', $id)->first();
        // dd($request);

        // if($request->icon && $request->icon != $typeBilan->icon){
        //     $image_url = time() . $request->icon->getClientOriginalName();
        //     $path = $request->icon->move(public_path() . "/typeBilan", $image_url);
        //     $path = "typeBilan/" . $image_url;
        // }
        // else{
        //     $path = $request->icon;
        // }

        $typeBilan->update([
            'label' => $request->label,
            'laboratorie_id' => $typeBilan->laboratorie_id,
            'price' => $request->price,
            'description' => $request->description,
        ]);

        $typeBilan->save();

        return $typeBilan;
    }

    public function get_TypeBilan()
    {
        return $this->typeBilan->newQuery()
            ->where('isactive', true)
            ->orderBy('created_at', 'DESC')
            ->with("laboratorie")
            ->paginate(15);
    }
}
