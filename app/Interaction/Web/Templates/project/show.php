<?php
/* @var $project \Service\Project
 * @var $slots \Service\Slot\SlotProto[]
 * @var $fetchCommand \Commands\Command\Project\FetchProjectRepos
 * @var $packs \Service\Pack[]
 * @var $this \Admin\DoView
 */

use Service\Breadcrumbs\BreadcrumbsFactory;

$this
    ->addBreadcrumb(BreadcrumbsFactory::makeProjectListBreadcrumb())
    ->addBreadcrumb(BreadcrumbsFactory::makeProjectPageBreadcrumb($project));
?>

<div class="pure-g">
    <div class="pure-u-1">
        <section class="top-page-nav">
            <a href="/web/project" class="pure-button btn-secondary-outline btn-s">
                <i class="fa-solid fa-arrow-left"></i> <?= __('back_to_project_list') ?>
            </a>
        </section>
    </div>

    <div class="pure-u-1">
        <a href="/web/branches/createPack/<?=$id ?>" class="pure-button btn-primary"><?= __('create_pack') ?></a>
        <a href="/web/command/?command=<?=$fetchCommand->getId() ?>&context=<?=$fetchCommand->getContext()->serialize() ?>"
           class="pure-button <?= $fetchCommand->isPrimary() ? 'btn-primary'
               : '' ?>"><?= $fetchCommand->getHumanName() ?>
        </a>

    </div>
</div>

<div class="pure-g">

    <div class="pure-u-1">
        <div class="pure-g">
            <div class="pure-u-md-1-2 pure-u-xl-2-3">
                <h3><?= __('packs') ?></h3>
                <?php foreach ($packs as $pack): ?>
                    <div class="pure-u-1 pack-card">
                        <div>
                            <?php $branches = $pack->getBranches() ?>

                            <a href="/web/pack/<?=$pack->getId() ?>" class="pure-button btn-secondary-outline">
                                <?=$pack->getName() ?>
                            </a>

                            <a href="<?=$pack->prepareCommand(new \Commands\Command\Pack\RemovePackWithData)->getLink() ?>"
                               class="pure-button btn-danger-outline right"
                               onclick="return confirm('Do you really want delete pack?')">
                                <?= __('delete') ?>
                            </a>
                        </div>
                        <ul class="branch-list">
                            <?php if (!empty($branches)): ?>
                            <li><?= @implode('</li><li>', $branches) ?></li>
                            <?php else: ?>
                            <li class="empty"><i>No branches added</i></li>
                            <?php endif; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </div>

            <?php if (env('ENABLE_DEPLOY')): ?>
            <div class="pure-u-md-1-2 pure-u-xl-1-3 vmenu">
                <h3><?= __('servers') ?></h3>
                <a href="/web/slot/create/?pId=<?=$id ?>" class="pure-button pure-button-primary"><?= __('add_release_server') ?></a>
                <a href="/web/project/slots/<?=$id ?>" class="pure-button"><?= __('release_servers_list') ?></a>
                <ul>
                    <?php foreach ($slots as $slot): ?>
                    <li><?=$slot->getName() ?>, <?=$slot->getHost().':'.$slot->getPath() ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
            <?php endif; ?>
            
        </div>
    </div>
</div>