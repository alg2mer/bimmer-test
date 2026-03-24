<?php

namespace App\Controller\Admin;

use App\Entity\SoftwareVersion;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;

class SoftwareVersionCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return SoftwareVersion::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id')->hideOnForm(),
            TextField::new('name'),
            TextField::new('systemVersion'),
            TextField::new('systemVersionAlt'),
            TextField::new('link'),
            TextField::new('stLink'),
            TextField::new('gdLink'),
            BooleanField::new('latest'),
            BooleanField::new('isLci'),
            TextField::new('lciType'),
        ];
    }
}