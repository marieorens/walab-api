<?php

namespace App\Helpers;

class ProfessionHelper
{
    /**
     * Retourne le nom français de la profession
     */
    public static function getDisplayName(string $profession): string
    {
        $professions = [
            'general_practitioner' => 'Médecin Généraliste',
            'specialist_doctor' => 'Médecin Spécialiste',
            'midwife' => 'Sage-femme',
            'nurse' => 'Infirmier(ère)',
            'nursing_assistant' => 'Aide-soignant(e)',
            'physiotherapist' => 'Kinésithérapeute',
            'psychologist' => 'Psychologue',
            'nutritionist' => 'Nutritionniste',
        ];

        // Si c'est une profession prédéfinie, retourner la traduction
        if (isset($professions[$profession])) {
            return $professions[$profession];
        }

        // Sinon, retourner la profession telle quelle (profession personnalisée)
        return ucfirst($profession);
    }
}
