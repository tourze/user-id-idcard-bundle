<?php

declare(strict_types=1);

namespace Tourze\UserIDIdcardBundle\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Attribute\AdminCrud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Filters;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use Tourze\UserIDIdcardBundle\Entity\IdcardIdentity;

/**
 * 身份证管理控制器
 */
#[AdminCrud(routePath: '/user-id-idcard/idcard-identity', routeName: 'user_id_idcard_idcard_identity')]
final class IdcardIdentityCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return IdcardIdentity::class;
    }

    public function configureFields(string $pageName): iterable
    {
        return [
            IdField::new('id', 'ID')
                ->hideOnForm(),

            TextField::new('idcard', '身份证号')
                ->setRequired(true)
                ->setHelp('18位身份证号码，支持最后一位为X的身份证')
                ->setMaxLength(18),

            AssociationField::new('user', '关联用户')
                ->setRequired(false)
                ->setHelp('与此身份证关联的用户账户'),

            DateTimeField::new('createTime', '创建时间')
                ->hideOnForm()
                ->setFormat('yyyy-MM-dd HH:mm:ss'),

            DateTimeField::new('updateTime', '更新时间')
                ->hideOnForm()
                ->setFormat('yyyy-MM-dd HH:mm:ss'),

            TextField::new('createdBy', '创建人')
                ->hideOnForm(),

            TextField::new('updatedBy', '更新人')
                ->hideOnForm(),
        ];
    }

    public function configureCrud(Crud $crud): Crud
    {
        return $crud
            ->setEntityLabelInSingular('身份证')
            ->setEntityLabelInPlural('身份证列表')
            ->setDateFormat('yyyy-MM-dd')
            ->setTimeFormat('HH:mm:ss')
            ->setDateTimeFormat('yyyy-MM-dd HH:mm:ss')
            ->setNumberFormat('%.2f')
            ->setDefaultSort(['id' => 'DESC'])
        ;
    }

    public function configureFilters(Filters $filters): Filters
    {
        return $filters
            ->add('idcard')
            ->add('user')
            ->add('createTime')
            ->add('updateTime')
            ->add('createdBy')
            ->add('updatedBy')
        ;
    }
}
