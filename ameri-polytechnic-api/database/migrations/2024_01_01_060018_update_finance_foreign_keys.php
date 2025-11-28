<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * 
     * Update foreign key references after moving billing_invoices and finance_transactions
     */
    public function up(): void
    {
        // Update invoice_items to reference finance.invoices
        DB::statement('ALTER TABLE finance.invoice_items DROP CONSTRAINT IF EXISTS invoice_items_invoice_id_foreign');
        DB::statement('ALTER TABLE finance.invoice_items ADD CONSTRAINT invoice_items_invoice_id_foreign FOREIGN KEY (invoice_id) REFERENCES finance.invoices(id) ON DELETE CASCADE');
        
        // Update payments to reference finance.invoices
        DB::statement('ALTER TABLE finance.payments DROP CONSTRAINT IF EXISTS payments_invoice_id_foreign');
        DB::statement('ALTER TABLE finance.payments ADD CONSTRAINT payments_invoice_id_foreign FOREIGN KEY (invoice_id) REFERENCES finance.invoices(id) ON DELETE SET NULL');
        
        // Update ledger_entries to reference finance.transactions
        DB::statement('ALTER TABLE finance.ledger_entries DROP CONSTRAINT IF EXISTS ledger_entries_transaction_id_foreign');
        DB::statement('ALTER TABLE finance.ledger_entries ADD CONSTRAINT ledger_entries_transaction_id_foreign FOREIGN KEY (transaction_id) REFERENCES finance.transactions(id) ON DELETE SET NULL');
    }

    public function down(): void
    {
        // Revert if needed
    }
};

