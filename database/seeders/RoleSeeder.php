<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Limpa o cache de permissões para evitar erros de duplicidade
        app(PermissionRegistrar::class)->forgetCachedPermissions();

        // 1. Definição de todas as permissões do sistema
        $permissions = [
            // Categorias
            'categories.view', 'categories.create', 'categories.update', 'categories.delete',
            // Produtos
            'products.view', 'products.create', 'products.update', 'products.delete',
            // Fornecedores (Base Oficial)
            'suppliers.view', 'suppliers.create', 'suppliers.update', 'suppliers.delete',
            // Submissões (Fluxo do Vendedor)
            'supplier_submissions.create', 'supplier_submissions.view_own', 
            'supplier_submissions.view_all', 'supplier_submissions.review',
            // Gestão de Usuários
            'users.manage',
        ];

        // Cria cada permissão no banco
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        // 2. Criação dos Papéis (Roles) e atribuição de permissões

        // ADMIN: Controle total
        $admin = Role::create(['name' => 'admin']);
        $admin->givePermissionTo(Permission::all());

        // VENDEDOR: Só cadastra submissões e consulta o catálogo
        $vendedor = Role::create(['name' => 'vendedor']);
        $vendedor->givePermissionTo([
            'categories.view',
            'products.view',
            'supplier_submissions.create',
            'supplier_submissions.view_own',
        ]);

        // COMPRAS: Valida submissões e gerencia a base oficial de fornecedores
        $compras = Role::create(['name' => 'compras']);
        $compras->givePermissionTo([
            'categories.view',
            'products.view',
            'supplier_submissions.view_all',
            'supplier_submissions.review',
            'suppliers.view',
            'suppliers.update',
        ]);
    }
}