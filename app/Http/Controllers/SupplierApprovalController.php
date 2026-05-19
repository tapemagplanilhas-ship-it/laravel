<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\SupplierSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierApprovalController extends Controller
{
    public function approve($id)
    {
        // Usamos Database Transactions para garantir que ou TUDO funciona, ou NADA é alterado.
        return DB::transaction(function () use ($id) {
            $submission = SupplierSubmission::findOrFail($id);

            // 1. Criar o fornecedor oficial
            $supplier = Supplier::create([
                'name' => $submission->supplier_name,
                'email' => $submission->email,
                'phone' => $submission->phone,
                'cnpj' => $submission->cnpj,
                'address' => $submission->address,
                'approved_by' => auth()->id(), // ID do usuário de Compras logado
            ]);

            // 2. Vincular o fornecedor ao produto original
            $supplier->products()->attach($submission->product_id);

            // 3. Atualizar status da submissão
            $submission->update(['status' => 'approved']);

            return response()->json(['message' => 'Fornecedor aprovado com sucesso!'], 201);
        });
    }
}