<?php
/**
  * @var \App\View\AppView $this
  */
?>
<nav class="small-3 medium-2 large-2 columns" id="actions-sidebar">
    <ul class="menu vertical">
        <li class="menu-text"><?= __('Actions') ?></li>
        <li><?= $this->Html->link(__('New Cake D C Users Phinxlog'), ['action' => 'add']) ?></li>
    </ul>
</nav>
<div class="cakeDCUsersPhinxlog index small-9 medium-10 large-10 columns content">
    <h3><?= __('Cake D C Users Phinxlog') ?></h3>
    <table cellpadding="0" cellspacing="0">
        <thead>
            <tr>
                <th scope="col"><?= $this->Paginator->sort('version') ?></th>
                <th scope="col"><?= $this->Paginator->sort('migration_name') ?></th>
                <th scope="col"><?= $this->Paginator->sort('start_time') ?></th>
                <th scope="col"><?= $this->Paginator->sort('end_time') ?></th>
                <th scope="col"><?= $this->Paginator->sort('breakpoint') ?></th>
                <th scope="col" class="actions"><?= __('Actions') ?></th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($cakeDCUsersPhinxlog as $cakeDCUsersPhinxlog): ?>
            <tr>
                <td><?= $this->Number->format($cakeDCUsersPhinxlog->version) ?></td>
                <td><?= h($cakeDCUsersPhinxlog->migration_name) ?></td>
                <td><?= h($cakeDCUsersPhinxlog->start_time) ?></td>
                <td><?= h($cakeDCUsersPhinxlog->end_time) ?></td>
                <td><?= h($cakeDCUsersPhinxlog->breakpoint) ?></td>
                <td class="actions">
                    <?= $this->Html->link('<i class="fi-eye" title="' . __('View') . '"></i>', ['action' => 'view', $cakeDCUsersPhinxlog->version], ['escape' => false]) ?>
                    <?= $this->Html->link('<i class="fi-pencil" title="' . __('Edit') . '"></i>', ['action' => 'edit', $cakeDCUsersPhinxlog->version], ['escape' => false]) ?>
                    <?= $this->Form->postLink('<i class="fi-x" title="' . __('Delete') . '"></i>', ['action' => 'delete', $cakeDCUsersPhinxlog->version],
                    ['escape' => false, 'confirm' => __('Are you sure you want to delete # {0}?', $cakeDCUsersPhinxlog->version)]) ?>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <div class="paginator">
        <ul class="pagination text-center">
            <?= $this->Paginator->first('<< ' . __('first')) ?>
            <?= $this->Paginator->prev('< ' . __('previous')) ?>
            <?= $this->Paginator->numbers() ?>
            <?= $this->Paginator->next(__('next') . ' >') ?>
            <?= $this->Paginator->last(__('last') . ' >>') ?>
        </ul>
        <p class="text-center"><?= $this->Paginator->counter(['format' => __('Page {{page}} of {{pages}}, showing {{current}} record(s) out of {{count}} total')]) ?></p>
    </div>
</div>
