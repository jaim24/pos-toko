<?php

namespace App\Traits;

use App\Models\ActivityLog;

trait LogsActivity
{
    public static function bootLogsActivity(): void
    {
        static::created(function ($model) {
            $model->logActivity('created');
        });

        static::updated(function ($model) {
            // Only log if something actually changed
            $dirty = $model->getDirty();
            if (empty($dirty)) return;

            // Ignore timestamp-only updates
            $ignored = ['updated_at', 'created_at'];
            $relevant = array_diff_key($dirty, array_flip($ignored));
            if (empty($relevant)) return;

            $old = [];
            foreach ($relevant as $key => $newVal) {
                $old[$key] = $model->getOriginal($key);
            }

            $model->logActivity('updated', [
                'old' => $old,
                'new' => $relevant,
            ]);
        });

        static::deleted(function ($model) {
            $model->logActivity('deleted');
        });
    }

    public function logActivity(string $action, ?array $changes = null): void
    {
        ActivityLog::create([
            'user_id'    => auth()->id(),
            'action'     => $action,
            'model_type' => class_basename($this),
            'model_id'   => $this->getKey(),
            'label'      => $this->resolveActivityLabel(),
            'changes'    => $changes,
        ]);
    }

    protected function resolveActivityLabel(): string
    {
        return match (class_basename($this)) {
            'Product'       => $this->name ?? 'Product #'.$this->getKey(),
            'Transaction'   => $this->invoice_number ?? 'Transaction #'.$this->getKey(),
            'User'          => $this->name ?? 'User #'.$this->getKey(),
            default         => class_basename($this) . ' #'.$this->getKey(),
        };
    }
}
