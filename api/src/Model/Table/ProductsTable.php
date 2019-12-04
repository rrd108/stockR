<?php
namespace App\Model\Table;

use ArrayObject;
use Cake\Core\Configure;
use Cake\Event\Event;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Products Model
 *
 * @property \App\Model\Table\CompaniesTable&\Cake\ORM\Association\BelongsTo $Companies
 * @property \App\Model\Table\ItemsTable&\Cake\ORM\Association\HasMany $Items
 * @property &\Cake\ORM\Association\BelongsToMany $Groups
 *
 * @method \App\Model\Entity\Product get($primaryKey, $options = [])
 * @method \App\Model\Entity\Product newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Product[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Product|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Product saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Product patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Product[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Product findOrCreate($search, callable $callback = null, $options = [])
 */
class ProductsTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('products');
        $this->setDisplayField('name');
        $this->setPrimaryKey('id');

        $this->belongsTo('Companies', [
            'foreignKey' => 'company_id',
            'joinType' => 'INNER'
        ]);
        $this->hasMany('Items', [
            'foreignKey' => 'product_id'
        ]);
        $this->hasMany('GroupsProducts', ['foreignKey' => 'product_id']);
        $this->belongsToMany('Groups', [
            'foreignKey' => 'product_id',
            'targetForeignKey' => 'group_id',
            'joinTable' => 'groups_products'
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->nonNegativeInteger('id')
            ->allowEmptyString('id', null, 'create');

        $validator
            ->scalar('name')
            ->maxLength('name', 255)
            ->allowEmptyString('name');

        $validator
            ->scalar('size')
            ->maxLength('size', 45)
            ->allowEmptyString('size');

        $validator
            ->decimal('vat')
            ->allowEmptyString('vat');

        return $validator;
    }

    /**
     * Returns a rules checker object that will be used for validating
     * application integrity.
     *
     * @param \Cake\ORM\RulesChecker $rules The rules object to be modified.
     * @return \Cake\ORM\RulesChecker
     */
    public function buildRules(RulesChecker $rules)
    {
        $rules->add($rules->existsIn(['company_id'], 'Companies'));

        return $rules;
    }

    public function beforeFind(Event $event, Query $query, ArrayObject $options, $primary)
    {
        $query->where(['Products.company_id' => Configure::read('company_id')]);
    }

    public function findStock(Query $query, array $options)
    {
        return $query
            ->select(['stock' => 'SUM(IF(Invoices.sale, -1 * Items.quantity, Items.quantity))'])
            ->enableAutoFields(true)
            ->group('Products.id')
            ->innerJoinWith('Items.Invoices');
    }

    public function findPurchasePrice(Query $query, array $options)
    {
        // cleanCopy() should be called for the first subquery to avoid query overwrite
        $avaragePurchasePrice = $query->cleanCopy()->find('avaragePurchasePrice', $options);
        $lastPurchasePrice = $query->find('lastPurchasePrice', $options);

        return $this->find()
            ->select([
                'Products.id',
                'Products.name',
                'Products.size',
                'Products.vat',
                'avaragePurchasePrice' => 'Avarage.avaragePurchasePrice',
                'lastPurchasePrice' => 'LastP.lastPurchasePrice'
                ])
            ->join([
                    'table' => $avaragePurchasePrice,
                    'type' => 'left',
                    'alias' => 'Avarage',
                    'conditions' => 'Avarage.Products__id = Products.id'
                ])
            ->join([
                    'table' => $lastPurchasePrice,
                    'type' => 'left',
                    'alias' => 'LastP',
                    'conditions' => 'LastP.Products__id = Products.id'
                ]);
    }

    public function findLastPurchasePrice(Query $query, array $options)
    {
        //select invoices where sale = 0, get the price from their items
        $invoiceIds = $this->getAssociation('Items')->getAssociation('Invoices')->find()
            ->where(['Invoices.sale' => false])
            ->extract('id')
            ->toArray();

        $latestItems = $this->getAssociation('Items')->find()
            ->where(['Items.invoice_id IN' => $invoiceIds]);
        $latestItems = $latestItems->select(['maxId' => $latestItems->func()->max('Items.id')])
            ->group('Items.product_id');

        return $query->select(
            [
                'currency' => 'Invoices.currency',
                'lastPurchasePrice' => 'Items.price',
            ]
        )
            ->enableAutoFields(true)
            ->matching(
                'Items.Invoices',
                function ($q) use ($options) {
                    return $q->where(
                    [
                        'Invoices.currency' => $options['currency'],
                        'Invoices.sale' => false,
                    ]
                );
                }
            )->where(['Items.id IN' => $latestItems]);
    }

    public function findAvaragePurchasePrice(Query $query, array $options)
    {
        return $query->select(['avaragePurchasePrice' => $query->func()->avg('Items.price')])
            ->enableAutoFields(true)
            ->matching(
                'Items.Invoices',
                function ($q) use ($options) {
                    return $q->where(
                    [
                        'Invoices.currency' => $options['currency'],
                        'Invoices.sale' => false,
                    ]
                );
                }
            )->group('Items.product_id');
    }
}