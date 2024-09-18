<?php

namespace App\Model;

enum TypeVente: string
{
    case CHEQUEANCV = 'Chèques / ANCV';
    case HELLOASSO = 'HelloAsso';
    case VIREMENT = 'Virement';
    case SUMUP = 'Carte bancaire SumUp';
}
