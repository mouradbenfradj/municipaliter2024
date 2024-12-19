<?php

namespace App\Controller\Admin;

use App\Entity\Reaction;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;

class ReactionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Reaction::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->setLabel('ID'),
            TextEditorField::new('content')->setLabel('Content'),
            DateTimeField::new('createdAt')
                ->setLabel('Date de Création')
                ->setFormat('yyyy-MM-dd HH:mm:ss') // Format avec chiffres occidentaux
                ->setFormTypeOption('input_format', 'Y-m-d H:i:s'),
            TextField::new('topic')->setLabel('Topic'),
            TextField::new('user')->setLabel('User'),
        ];
    }
}
