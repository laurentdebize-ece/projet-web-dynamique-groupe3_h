<?php

require_once 'src/database/DatabaseTable.php';

class Promotion extends DatabaseTable
{
    const TABLE_NAME = 'Promotions';
    const TABLE_TYPE = Promotion::class;

    public function __construct(int $annee, int $idFiliere)
    {
        $this->annee = $annee;
        $this->idFiliere = $idFiliere;
    }

    #[TableOpt(PrimaryKey: true, AutoIncrement: true)]
    private ?int $idPromo = null;

    private int $annee;
    
    #[TableOpt(TableForeignKey: Filiere::class)]
    private int $idFiliere;

    // retourne un tableau de toutes les promotions contenant la filière et l'école
    public static function getAllPromo(DatabaseController $db): ?array
    {
        $promos = array();
        $promotions = Promotion::select($db,null,["ORDER BY idPromo ASC"])->fetchAll();
        foreach ($promotions as $promotion)
        {
            $annee = $promotion['annee'];

            $idFiliere = intval($promotion['idFiliere']);
            $filiere = Filiere::select($db,null,["WHERE","idFiliere = $idFiliere","LIMIT 1"])->fetch();
            $nomFiliere = $filiere['nomFiliere'];

            $idEcole = intval($filiere['idEcole']);
            $ecole = Ecole::select($db,null,["WHERE","idEcole = $idEcole","LIMIT 1"])->fetch();
            $nomEcole = $ecole['nomEcole'];

            $promoInfo = [$annee,$nomFiliere,$nomEcole];
            $idPromo = intval($promotion['idPromo']);
            $promos[$idPromo] = $promoInfo;
        }
        return $promos;
    }

    // retourne un tableau de toutes les promotions d'une filière
    public static function getAllPromoByFiliere(DatabaseController $db, int $idFiliere): ?array
    {
        $promos = array();
        $promotions = Promotion::select($db, null, ["WHERE", "idFiliere = $idFiliere"])->fetchAll();
        foreach ($promotions as $promotion) {
            $annee = $promotion['annee'];
            $idPromo = intval($promotion['idPromo']);
            $promos[$idPromo] = $annee;
        }
        return $promos;
    }
}
