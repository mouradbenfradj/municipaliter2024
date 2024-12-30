<?php

namespace App\Controller\Admin;

use App\Entity\EmployerVote;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IntegerField;

class EmployerVoteCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return EmployerVote::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(), // Masquer l'ID lors de la création
            AssociationField::new('employer'), // Champ pour l'employeur
            AssociationField::new('user'), // Champ pour l'utilisateur
            IntegerField::new('rating'), // Champ pour la note
            TextEditorField::new('comment'), // Champ pour le commentaire
        ];
    }
}
