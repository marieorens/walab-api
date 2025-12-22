<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DaTaNotification extends Model
{
    // use HasFactory;
    private String $titre;
    private String $body;
    private String $image;
    private String $type;
    private String $id;


    public function __construct(String $titre = "", String $body = "", String $image = "", String $paiement_effectuer = "", String $id = "", String $type = "") {
        $this->titre = $titre;
        $this->body = $body;
        $this->image = $image;
        $this->type = $type;
        $this->id = $id;
    }

    public function gettitre()
    {
        return $this->titre;

    }

    public function getbody()
    {
        return $this->body;

    }

    public function getimage()
    {
        return $this->image;

    }

    public function gettype()
    {
        return $this->type;

    }

    public function getid()
    {
        return $this->id;

    }

    public function settitre(String $titre)
    {
        $this->titre = $titre;

    }

    public function setbody(string $body)
    {
        $this->body = $body;

    }

    public function setimage(String $image)
    {
        $this->image = $image;

    }

    public function settype(String $type)
    {
        $this->type = $type;

    }

    public function setid(String $id)
    {
        $this->id = $id;

    }

    public function getResponse(){
        return [
            'titre' => $this->titre, 
            'body' => $this->body, 
            'image' => $this->image, 
        ];
    }

}
