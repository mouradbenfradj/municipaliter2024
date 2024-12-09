<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\Field;
use Vich\UploaderBundle\Form\Type\VichFileType;

class ArticleCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Article::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('nom'),
            Field::new('file', 'Fichier (PDF, DOC, DOCX, XLS, XLSX)')
                ->setFormType(VichFileType::class)
                ->setHelp('Veuillez télécharger un fichier valide (PDF, DOC, DOCX, XLS, XLSX).')
                ->setRequired(true)
                ->onlyOnForms(),
            Field::new('fileName')
                ->setLabel('Nom du fichier')
                ->setTemplatePath('admin/file_link.html.twig') // Chemin vers le template
                ->onlyOnIndex(),
        ];
    }
}
