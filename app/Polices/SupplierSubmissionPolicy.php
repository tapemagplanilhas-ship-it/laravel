<?php

namespace App\Policies;

use App\Models\SupplierSubmission;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class SupplierSubmissionPolicy
{
    /**
     * Determina se o usuário pode listar as submissões.
     * Admin e Compras podem ver todas. Vendedores são limitados via Controller/Query.
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(['supplier_submissions.view_all', 'users.manage']);
    }

    /**
     * Determina se o usuário pode ver uma submissão específica.
     * Vendedores só veem as próprias. Compras e Admin veem todas.
     */
    public function view(User $user, SupplierSubmission $submission): bool
    {
        if ($user->hasAnyPermission(['supplier_submissions.view_all', 'users.manage'])) {
            return true;
        }

        return $user->hasPermissionTo('supplier_submissions.view_own') 
            && $user->id === $submission->user_id;
    }

    /**
     * Determina se o usuário pode criar uma submissão.
     */
    public function create(User $user): bool
    {
        return $user->hasAnyPermission(['supplier_submissions.create', 'users.manage']);
    }

    /**
     * Determina se o usuário pode revisar (aprovar/rejeitar) a submissão.
     * Regra: Apenas Compras/Admin e somente se o status for 'pending'.
     */
    public function review(User $user, SupplierSubmission $submission): bool
    {
        return $user->hasAnyPermission(['supplier_submissions.review', 'users.manage']) 
            && $submission->status === 'pending';
    }

    /**
     * Determina se o usuário pode atualizar a submissão.
     * Seguindo a regra de "vendedor só adiciona", apenas Admin teria esse poder
     * para correções técnicas, ou bloqueamos totalmente para manter integridade.
     */
    public function update(User $user, SupplierSubmission $submission): bool
    {
        return $user->hasPermissionTo('users.manage');
    }

    /**
     * Determina se o usuário pode excluir a submissão.
     */
    public function delete(User $user, SupplierSubmission $submission): bool
    {
        return $user->hasPermissionTo('users.manage');
    }
}