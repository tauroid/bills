<?php

/**
 * Created by Reliese Model.
 */

namespace App\Models\Base;

use App\Models\Amount;
use App\Models\Entity;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class TransactionFrom
 * 
 * @property int $id
 * @property int $entity_id
 * @property int $amount_id
 * @property int $transaction_id
 * @property string|null $deleted_at
 * 
 * @property Entity $entity
 * @property Amount $amount
 * @property Transaction $transaction
 *
 * @package App\Models\Base
 */
class TransactionFrom extends Model
{
	use SoftDeletes;
	protected $table = 'transaction_from';
	public $timestamps = false;

	protected $casts = [
		'entity_id' => 'int',
		'amount_id' => 'int',
		'transaction_id' => 'int'
	];

	public function entity()
	{
		return $this->belongsTo(Entity::class);
	}

	public function amount()
	{
		return $this->belongsTo(Amount::class);
	}

	public function transaction()
	{
		return $this->belongsTo(Transaction::class);
	}
}
