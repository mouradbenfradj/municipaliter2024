<?php

namespace App\Controller\Admin;

use App\Entity\Reunion;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\CollectionField;
use IntlDateFormatter;

class ReunionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Reunion::class;
    }

    private function formatDate(\DateTimeInterface $date): string
    {
        $formatter = new IntlDateFormatter(
            'en_US', // Utiliser une locale qui utilise des chiffres occidentaux
            IntlDateFormatter::MEDIUM,
            IntlDateFormatter::SHORT,
            null,
            IntlDateFormatter::GREGORIAN,
            'yyyy-MM-dd HH:mm:ss'
        );
        return $formatter->format($date);
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->setLabel('ID'),
            TextField::new('sujet')->setLabel('Sujet'),
            TextEditorField::new('description')->setLabel('Description'),
            DateTimeField::new('date')
                ->setLabel('تاريخ انطلاق')
                ->setFormTypeOption('input_format', 'yyyy-MM-dd HH:mm:ss')
                ->setFormTypeOption('widget', 'single_text')
                ->setFormTypeOption('html5', true)
                ->formatValue(function ($value) {
                    return $this->formatDate($value); // Appliquer le formatage personnalisé
                }),
            DateTimeField::new('datefin')
                ->setLabel('تاريخ الانتهاء الفعلي')
                ->setFormTypeOption('input_format', 'yyyy-MM-dd HH:mm:ss')
                ->setFormTypeOption('widget', 'single_text')
                ->setFormTypeOption('html5', true)
                ->formatValue(function ($value) {
                    return $this->formatDate($value); // Appliquer le formatage personnalisé
                }),
            CollectionField::new('invites')->setLabel('Invités')
        ];
    }
}
