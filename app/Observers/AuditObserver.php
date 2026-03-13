<?php

namespace App\Observers;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class AuditObserver
{
    /**
     * Campos que nunca se incluyen en el diff por seguridad o ruido.
     */
    protected array $camposIgnorados = [
        'password',
        'remember_token',
        'updated_at',
    ];

    public function created(Model $model): void
    {
        $this->registrar('created', $model, null, $model->getAttributes());
    }

    public function updated(Model $model): void
    {
        $anteriores = $model->getOriginal();
        $nuevos     = $model->getChanges();

        // Eliminar campos ignorados del diff
        $anteriores = $this->filtrarCampos(
            array_intersect_key($anteriores, $nuevos)
        );
        $nuevos = $this->filtrarCampos($nuevos);

        // Si no quedó nada relevante en el diff, no se registra
        if (empty($nuevos)) return;

        $this->registrar('updated', $model, $anteriores, $nuevos);
    }

    public function deleted(Model $model): void
    {
        $this->registrar('deleted', $model, $model->getOriginal(), null);
    }

    /**
     * Construye y guarda el registro en activity_logs.
     */
    protected function registrar(
        string $evento,
        Model $model,
        ?array $anteriores,
        ?array $nuevos
    ): void {
        $usuario = Auth::user();

        ActivityLog::create([
            'id_usuario'         => $usuario?->id,
            'usuario_nombre'     => $usuario?->nombre_completo,
            'evento'             => $evento,
            'modelo'             => class_basename($model),
            'tabla'              => $model->getTable(),
            'registro_id'        => $model->getKey(),
            'valores_anteriores' => $anteriores,
            'valores_nuevos'     => $nuevos,
            'ip'                 => Request::ip(),
            'user_agent'         => Request::userAgent(),
        ]);
    }

    protected function filtrarCampos(array $campos): array
    {
        return array_diff_key($campos, array_flip($this->camposIgnorados));
    }
}
