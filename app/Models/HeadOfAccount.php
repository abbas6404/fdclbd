<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use App\Models\AccountTransactionContra;

class HeadOfAccount extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'head_of_accounts';

    protected $fillable = [
        'account_name',
        'account_type',
        'parent_id',
        'account_level',
        'status',
        'is_requisitions',
        'is_boq',
        'is_account',
        'last_used_unit',
        'last_rate',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'account_type' => 'string',
        'status' => 'string',
        'is_requisitions' => 'boolean',
        'is_boq' => 'boolean',
        'is_account' => 'boolean',
    ];

    /**
     * Get the parent account.
     */
    public function parent()
    {
        return $this->belongsTo(HeadOfAccount::class, 'parent_id');
    }

    /**
     * Get the child accounts.
     */
    public function children()
    {
        return $this->hasMany(HeadOfAccount::class, 'parent_id');
    }

    /**
     * Get all descendants recursively.
     */
    public function descendants()
    {
        return $this->children()->with('descendants');
    }

    /**
     * Get all ancestors recursively.
     */
    public function ancestors()
    {
        return $this->parent()->with('ancestors');
    }

    /**
     * Get the user who created the account.
     */
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the account.
     */
    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the requisition items for this account.
     */
    public function requisitionItems()
    {
        return $this->hasMany(RequisitionItem::class, 'chart_of_account_id');
    }

    /**
     * Get the transaction debit items for this account.
     */
    public function transactionDebits()
    {
        return $this->hasMany(AccountTransactionDebit::class, 'chart_of_account_id');
    }

    /**
     * Get the transaction credit items for this account.
     */
    public function transactionCredits()
    {
        return $this->hasMany(AccountTransactionCredit::class, 'chart_of_account_id');
    }

    /**
     * Get the contra entries where this account is the from account.
     */
    public function contraEntriesFrom()
    {
        return $this->hasMany(AccountTransactionContra::class, 'from_account_id');
    }

    /**
     * Get the contra entries where this account is the to account.
     */
    public function contraEntriesTo()
    {
        return $this->hasMany(AccountTransactionContra::class, 'to_account_id');
    }

    /**
     * Scope a query to only include active accounts.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope a query to only include income accounts.
     */
    public function scopeIncome($query)
    {
        return $query->where('account_type', 'income');
    }

    /**
     * Scope a query to only include expense accounts.
     */
    public function scopeExpense($query)
    {
        return $query->where('account_type', 'expense');
    }

    /**
     * Scope a query to only include contra accounts.
     */
    public function scopeContra($query)
    {
        return $query->where('account_type', 'contra');
    }

    /**
     * Scope a query to only include accounts that show in requisition.
     */
    public function scopeShowInRequisition($query)
    {
        return $query->where('is_requisitions', true);
    }

    /**
     * Scope a query to only include parent accounts (no parent_id).
     */
    public function scopeParents($query)
    {
        return $query->whereNull('parent_id');
    }

    /**
     * Get the full account path (parent names + current name).
     */
    public function getFullPathAttribute()
    {
        $path = [$this->account_name];
        $parent = $this->parent;

        while ($parent) {
            array_unshift($path, $parent->account_name);
            $parent = $parent->parent;
        }

        return implode(' > ', $path);
    }

    /**
     * Get the account level (depth in hierarchy).
     */
    public function getLevelAttribute()
    {
        $level = 0;
        $parent = $this->parent;

        while ($parent) {
            $level++;
            $parent = $parent->parent;
        }

        return $level;
    }

    /**
     * Check if account is a parent account.
     */
    public function isParent()
    {
        return is_null($this->parent_id);
    }

    /**
     * Check if account is a child account.
     */
    public function isChild()
    {
        return !is_null($this->parent_id);
    }

    /**
     * Check if account has children.
     */
    public function hasChildren()
    {
        return $this->children()->count() > 0;
    }

    /**
     * Get the account type label.
     */
    public function getAccountTypeLabelAttribute()
    {
        return ucfirst($this->account_type);
    }

    /**
     * Get the status label.
     */
    public function getStatusLabelAttribute()
    {
        return ucfirst($this->status);
    }

    /**
     * Get the status badge class.
     */
    public function getStatusBadgeClassAttribute()
    {
        return $this->status === 'active' ? 'success' : 'secondary';
    }

    /**
     * Get the account type badge class.
     */
    public function getAccountTypeBadgeClassAttribute()
    {
        return $this->account_type === 'income' ? 'success' : 'danger';
    }
}
