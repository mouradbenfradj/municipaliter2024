<?php

namespace App\Controller\Admin;

use App\Entity\Employer;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class EmployerCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Employer::class;
    }


    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('matricule', 'Matricule'),
            TextField::new('name', 'Nom'),
            AssociationField::new('groupe', 'Groupe')
                ->setHelp('Sélectionnez le groupe auquel appartient cet employé.'),
            CollectionField::new('taches', 'Tâches')
                ->setTemplatePath('admin/fields/taches.html.twig') // Optionnel pour personnaliser l'affichage
                ->onlyOnDetail(), // Affiche uniquement dans les détails, pas dans les formulaires
        ];
    }

}
