<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use DateTimeImmutable;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\EmailField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Filter\EntityFilter;

class CommentCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Comment::class;
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('Comment')
            ->setEntityLabelInPlural('Comments')
            ->setSearchFields(['author', 'email', 'text'])
            ->setDefaultSort(['createdAt' => 'DESC']);
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add(EntityFilter::new('conference'));
    }

    public function configureFields(string $pageName): iterable
    {
        yield TextField::new('author');
        yield EmailField::new('email');
        yield TextField::new('text');
        yield AssociationField::new('conference');
        yield ImageField::new('photoFilename')
            ->setBasePath('/uploads/photos')
            ->setUploadDir('/public/uploads/photos/')
            ->setLabel('Photo')
            ->onlyOnIndex();
        $createdAt = DateTimeField::new('createdAt')->setFormTypeOptions([
            'html5' => true,
            'years' => range((int) date('Y'), (int) date('Y') + 5),
            'widget' => 'single_text',
        ]);
        switch ($pageName) {
            case Crud::PAGE_NEW :
                yield $createdAt->setFormTypeOption('data', (new DateTimeImmutable()));
                break;
            case Crud::PAGE_EDIT :
                yield $createdAt->setFormTypeOption('disabled', true);
                break;
            default:
                yield $createdAt;
        }
    }
}
