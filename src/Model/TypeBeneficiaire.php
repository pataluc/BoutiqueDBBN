<?php

namespace App\Model;

enum TypeBeneficiaire: string
{
    case EQUIPE = 'Équipe interclub';
    case ADHERENT = 'Adhérent';
}
