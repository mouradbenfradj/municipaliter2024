<?php

namespace App\Controller\Admin;

use App\Entity\Tache;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;
use EasyCorp\Bundle\EasyAdminBundle\Field\PercentField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class TacheCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Tache::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->onlyOnIndex(),
            TextField::new('referance', 'Référence'),
            TextField::new('titre', 'Titre'),
            AssociationField::new('worker', 'Employé'),
            AssociationField::new('workerGroup', 'Groupe'),
            DateField::new('debut', 'Date de début'),
            DateField::new('dateFin', 'Date de fin'),
            IntegerField::new('estimation', 'Estimation (heures)')->setHelp('Durée estimée en heures'),
            PercentField::new('pourcentage', 'Pourcentage')->setNumDecimals(2)->setStoredAsFractional(false),
            ChoiceField::new('etat', 'État')
            ->setChoices([
                'Nouveau' => 'Nouveau',
                'En cours' => 'En cours',
                'Terminé' => 'Terminé',
            ]),
             ChoiceField::new('eval', 'Évaluation')
            ->setChoices([
                'مراجعة' => 'مراجعة',
                'متأخرة' => 'متأخرة',
                'جاهزة للتسليم' => 'جاهزة للتسليم',
            ])
            ->renderExpanded(false) // Menu déroulant
            ->setHelp('Choisissez l\'état actuel de l\'évaluation.'),
        ];
    }

}
