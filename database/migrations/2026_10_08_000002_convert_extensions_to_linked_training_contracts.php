<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('training_contracts', 'original_training_contract_id')) {
            Schema::table('training_contracts', function (Blueprint $table) {
                $table->foreignId('original_training_contract_id')->nullable()->after('id');
            });
        }
        if (!Schema::hasColumn('training_contracts', 'previous_training_contract_id')) {
            Schema::table('training_contracts', function (Blueprint $table) {
                $table->foreignId('previous_training_contract_id')->nullable()->after('original_training_contract_id');
            });
        }
        if (!Schema::hasColumn('training_contracts', 'extension_sequence')) {
            Schema::table('training_contracts', function (Blueprint $table) {
                $table->unsignedInteger('extension_sequence')->default(0)->after('previous_training_contract_id');
            });
        }

        Schema::table('training_contracts', function (Blueprint $table) {
            if (!$this->constraintExists('tc_original_contract_fk')) {
                $table->foreign('original_training_contract_id', 'tc_original_contract_fk')
                    ->references('id')->on('training_contracts')->nullOnDelete();
            }
            if (!$this->constraintExists('tc_previous_contract_fk')) {
                $table->foreign('previous_training_contract_id', 'tc_previous_contract_fk')
                    ->references('id')->on('training_contracts')->restrictOnDelete();
            }
            if (!$this->indexExists('tc_previous_contract_unique')) {
                $table->unique('previous_training_contract_id', 'tc_previous_contract_unique');
            }
            if (!$this->indexExists('tc_original_sequence_unique')) {
                $table->unique(['original_training_contract_id', 'extension_sequence'], 'tc_original_sequence_unique');
            }
        });

        DB::transaction(fn () => $this->convertConfirmedLegacyExtensions());
        $this->dropLegacyExtensionStorage();
    }

    public function down(): void
    {
        Schema::table('training_contracts', function (Blueprint $table) {
            $table->dropUnique('tc_original_sequence_unique');
            $table->dropUnique('tc_previous_contract_unique');
            $table->dropForeign('tc_previous_contract_fk');
            $table->dropForeign('tc_original_contract_fk');
            $table->dropColumn([
                'original_training_contract_id',
                'previous_training_contract_id',
                'extension_sequence',
            ]);
        });
    }

    private function convertConfirmedLegacyExtensions(): void
    {
        if (!Schema::hasTable('training_contract_extensions')) {
            return;
        }

        $statusId = DB::table('training_contract_statuses')
            ->whereRaw('UPPER(name) = ?', ['TRAMITACIÓN'])
            ->value('id');

        $groups = DB::table('training_contract_extensions')
            ->where('status', 'confirmed')
            ->orderBy('training_contract_id')
            ->orderBy('sequence')
            ->get()
            ->groupBy('training_contract_id');

        $contractColumns = array_flip(Schema::getColumnListing('training_contracts'));

        foreach ($groups as $contractId => $extensions) {
            $source = DB::table('training_contracts')->where('id', $contractId)->first();
            if (!$source || $extensions->isEmpty()) {
                continue;
            }

            $first = $extensions->first();
            $restore = ['end' => $first->previous_end];
            if ($first->extends_training && $first->previous_training_end) {
                $restore['end_formation'] = $first->previous_training_end;
            }
            DB::table('training_contracts')->where('id', $contractId)->update($restore);

            $previousId = (int) $contractId;
            foreach ($extensions as $extension) {
                $existingId = DB::table('training_contracts')
                    ->where('original_training_contract_id', $contractId)
                    ->where('extension_sequence', $extension->sequence)
                    ->value('id');
                if ($existingId) {
                    $previousId = (int) $existingId;
                    continue;
                }

                $attributes = array_intersect_key((array) $source, $contractColumns);
                unset($attributes['id']);
                $attributes['number_cfa'] = null;
                $attributes['original_training_contract_id'] = (int) $contractId;
                $attributes['previous_training_contract_id'] = $previousId;
                $attributes['extension_sequence'] = (int) $extension->sequence;
                $attributes['beginning'] = $extension->start;
                $attributes['end'] = $extension->new_end;
                $attributes['beginning_formation'] = $extension->start;
                $attributes['end_formation'] = $extension->new_end;
                $attributes['training_contract_status_id'] = $statusId ?: $source->training_contract_status_id;
                $attributes['formation_hours'] = 0;
                if (isset($contractColumns['on_leave_type_id'])) {
                    $attributes['on_leave_type_id'] = null;
                }
                if (isset($contractColumns['on_leave_date'])) {
                    $attributes['on_leave_date'] = null;
                }
                if (isset($contractColumns['created_at'])) {
                    $attributes['created_at'] = now();
                }
                if (isset($contractColumns['updated_at'])) {
                    $attributes['updated_at'] = now();
                }

                $newId = DB::table('training_contracts')->insertGetId($attributes);
                DB::table('training_contracts')->where('id', $newId)->update([
                    'number_cfa' => str_pad((string) $newId, 4, '0', STR_PAD_LEFT),
                ]);

                $this->copyRows('training_contract_festivals', (int) $contractId, $newId);
                $this->copyRows('training_contracts_excluded_days', (int) $contractId, $newId);
                $previousId = $newId;
            }
        }
    }

    private function copyRows(string $table, int $sourceId, int $targetId): void
    {
        if (!Schema::hasTable($table)) {
            return;
        }

        foreach (DB::table($table)->where('training_contract_id', $sourceId)->get() as $row) {
            $attributes = (array) $row;
            unset($attributes['id']);
            $attributes['training_contract_id'] = $targetId;
            DB::table($table)->insert($attributes);
        }
    }

    private function dropLegacyExtensionStorage(): void
    {
        if (Schema::hasColumn('training_contract_elements', 'training_contract_extension_id')) {
            $constraints = DB::table('information_schema.KEY_COLUMN_USAGE')
                ->where('TABLE_SCHEMA', DB::getDatabaseName())
                ->where('TABLE_NAME', 'training_contract_elements')
                ->where('COLUMN_NAME', 'training_contract_extension_id')
                ->whereNotNull('REFERENCED_TABLE_NAME')
                ->pluck('CONSTRAINT_NAME');

            foreach ($constraints as $constraint) {
                DB::statement("ALTER TABLE `training_contract_elements` DROP FOREIGN KEY `{$constraint}`");
            }

            Schema::table('training_contract_elements', function (Blueprint $table) {
                $table->dropColumn('training_contract_extension_id');
            });
        }

        Schema::dropIfExists('training_contract_extensions');
    }

    private function constraintExists(string $name): bool
    {
        return DB::table('information_schema.TABLE_CONSTRAINTS')
            ->where('CONSTRAINT_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', 'training_contracts')
            ->where('CONSTRAINT_NAME', $name)
            ->exists();
    }

    private function indexExists(string $name): bool
    {
        return DB::table('information_schema.STATISTICS')
            ->where('TABLE_SCHEMA', DB::getDatabaseName())
            ->where('TABLE_NAME', 'training_contracts')
            ->where('INDEX_NAME', $name)
            ->exists();
    }
};
