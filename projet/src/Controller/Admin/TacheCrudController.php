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
            TextField::new('titre', 'المهمة'),
            AssociationField::new('worker', 'Employé'),
            AssociationField::new('workerGroup', 'Groupe'),
            DateField::new('debut', 'تاريخ انطلاق المهمة'),
            DateField::new('dateFin', 'تاريخ الانتهاء الفعلي'),
            IntegerField::new('estimation', 'مدة إنجاز المهمة')->setHelp('Durée estimée en heures'),
            PercentField::new('pourcentage', 'نسبة الإنجاز')->setNumDecimals(2)->setStoredAsFractional(false),
            ChoiceField::new('etat', 'وضع المهمة الحالي')
            ->setChoices([
                'Nouveau' => 'Nouveau',
                'En cours' => 'En cours',
                'Terminé' => 'Terminé',
            ]),
             ChoiceField::new('eval', 'تقييم جودة العمل')
            ->setChoices([
                'مراجعة' => 'مراجعة',
                'الإنجاز تم بمستوى عالٍ من الجودة' => 'الإنجاز تم بمستوى عالٍ من الجودة',
                'جاهزة للتسليم' => 'جاهزة للتسليم',
            ])
            ->renderExpanded(false) // Menu déroulant
            ->setHelp('Choisissez l\'état actuel de l\'évaluation.'),
        ];
    }

}
