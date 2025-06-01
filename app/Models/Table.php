<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $fillable = ['table_number'];
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
    public function getTableNumberAttribute($value)
    {
        return strtoupper($value);
    }
    public function setTableNumberAttribute($value)
    {
        $this->attributes['table_number'] = strtoupper($value);
    }
    public function getCreatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
    }
    public function getUpdatedAtAttribute($value)
    {
        return \Carbon\Carbon::parse($value)->format('Y-m-d H:i:s');
    }
    public function getRouteKeyName()
    {
        return 'table_number';
    }
    public function scopeSearch($query, $search)
    {
        if ($search) {
            return $query->where('table_number', 'like', '%' . $search . '%');
        }
        return $query;
    }
    public function scopeFilterByStatus($query, $status)
    {
        if ($status) {
            return $query->where('status', $status);
        }
        return $query;
    }
    public function scopeSortByColumn($query, $column, $direction = 'asc')
    {
        if ($column && in_array($column, ['table_number', 'created_at', 'updated_at'])) {
            return $query->orderBy($column, $direction);
        }
        return $query;
    }
    public function scopePaginate($query, $perPage = 10)
    {
        return $query->paginate($perPage);
    }
    public function scopeWithPagination($query, $perPage = 10)
    {
        return $query->paginate($perPage);
    }
    public function scopeWithTrashed($query)
    {
        return $query->withTrashed();
    }
    public function scopeOnlyTrashed($query)
    {
        return $query->onlyTrashed();
    }
    public function scopeRestore($query, $id)
    {
        return $query->where('id', $id)->restore();
    }
    public function scopeForceDelete($query, $id)
    {
        return $query->where('id', $id)->forceDelete();
    }
    public function scopeDelete($query, $id)
    {
        return $query->where('id', $id)->delete();
    }
    public function scopeCreate($query, $attributes)
    {
        return $query->create($attributes);
    }
    public function scopeUpdate($query, $id, $attributes)
    {
        return $query->where('id', $id)->update($attributes);
    }
    public function scopeFind($query, $id)
    {
        return $query->where('id', $id)->first();
    }
    public function scopeFindOrFail($query, $id)
    {
        return $query->where('id', $id)->firstOrFail();
    }
    public function scopeAll($query)
    {
        return $query->get();
    }
    public function scopeCount($query)
    {
        return $query->count();
    }
    public function scopeExists($query, $id)
    {
        return $query->where('id', $id)->exists();
    }
    public function scopeLatest($query)
    {
        return $query->latest();
    }
    public function scopeOldest($query)
    {
        return $query->oldest();
    }
    public function scopeWhere($query, $column, $operator = null, $value = null)
    {
        return $query->where($column, $operator, $value);
    }
    public function scopeOrWhere($query, $column, $operator = null, $value = null)
    {
        return $query->orWhere($column, $operator, $value);
    }   
}
