<?= $this->Html->script('stock.add.min', ['block' => true]) ?>
<div class="invoices form small-12 columns content">
    <?= $this->Form->create($invoice) ?>
    <fieldset>
        <legend><?= __('Add Invoice') ?></legend>
        <div class="row">
            <div class="column small-6">
                <?= $this->Form->control('storage_id', ['options' => $storages]) ?>
            </div>
            <div class="column small-6">
                <?= $this->Form->control('invoicetype_id', ['options' => $invoicetypes]) ?>
            </div>
        </div>
        <div class="row">
            <div class="column small-6">
                <?= $this->Form->control(
                    'partner_id',
                    ['type' => 'datalistJs', 'options' => $partners]
                    ) ?>
            </div>
            <div class="column small-6">
                <?= $this->Form->control('date', ['type' => 'text', 'value' => date('Y-m-d')]) ?>
            </div>
        </div>
        <div class="row">
            <div class="column small-6">
                <?= $this->Form->control('number') ?>
            </div>
            <div class="column small-3">
                <?= $this->Form->control('currency') ?>
            </div>
            <div class="column small-3">
                <?= $this->Form->control('sale') ?>
            </div>
        </div>
    </fieldset>

    <fieldset>
        <legend><?= __('Items') ?></legend>
        <table cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th class="text-center" scope="col"><?= __('Product') ?></th>
                    <th class="text-center" scope="col"><?= __('Quantity') ?></th>
                    <th class="text-center" scope="col"><?= __('Price') ?></th>
                    <th class="text-center" scope="col"><?= __('Amount') ?></th>
                    <th class="text-center" scope="col"><?= __('VAT') ?></th>
                    <th class="text-center" scope="col"><?= __('VAT') ?></th>
                    <th class="text-center" scope="col"><?= __('Amount') ?></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <?= $this->Form->control(
                            'items[0].product_id',
                            ['label' => false, 'type' => 'datalistJs', 'options' => $products]
                            ) ?>
                    </td>
                    <td><?= $this->Form->control('items[0].quantity', ['label' => false, 'class' => 'quantity']) ?></td>
                    <td><?= $this->Form->control('items[0].price', ['label' => false, 'class' => 'price']) ?></td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                </tr>
                <tr>
                    <td>
                        <?= $this->Form->control(
                            'items[1].product_id',
                            ['label' => false, 'type' => 'datalistJs', 'options' => $products]
                            ) ?>
                    </td>
                    <td><?= $this->Form->control('items[1].quantity', ['label' => false, 'class' => 'quantity']) ?></td>
                    <td><?= $this->Form->control('items[1].price', ['label' => false, 'class' => 'price']) ?></td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                    <td>0</td>
                </tr>
            </tbody>
            <tfoot>
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </fieldset>

    <div class="column small-3">
        <?= $this->Form->button(__('Submit'), ['class' => 'button']) ?>
        <?= $this->Form->end() ?>
    </div>

</div>
