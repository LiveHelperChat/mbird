<h1><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Present channels'); ?></h1>

<form action="" method="post" ng-non-bindable>

    <?php include(erLhcoreClassDesign::designtpl('lhkernel/csfr_token.tpl.php'));?>

    <?php if (isset($updated) && $updated == 'done') : $msg = erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Settings updated'); ?>
        <?php include(erLhcoreClassDesign::designtpl('lhkernel/alert_success.tpl.php'));?>
    <?php endif; ?>

    <?php if (isset($errors)) : ?>
        <?php include(erLhcoreClassDesign::designtpl('lhkernel/validation_error.tpl.php'));?>
    <?php endif; ?>

    <?php if (isset($channels['results']) && !empty($channels['results'])) : ?>
        <table class="table list-links table-sm">
            <tr>
                <th>id</th>
                <th>name</th>
                <th>platformId</th>
                <th>Identifier</th>
                <th>status</th>
                <th>Webhook setup</th>
            </tr>
            <?php foreach ($channels['results'] as $channels) : ?>
                <tr>
                    <td><?php echo htmlspecialchars($channels['id']);?></td>
                    <td><?php echo htmlspecialchars($channels['name']);?></td>
                    <td><?php echo htmlspecialchars($channels['platformId']);?></td>
                    <td><?php echo htmlspecialchars($channels['identifier']);?></td>
                    <td><?php echo htmlspecialchars($channels['status']);?></td>
                    <td>
                        <?php if ($channels['platformId'] == 'whatsapp') : ?>
                        <form action="" method="post">
                            <input type="hidden" name="channel_id" value="<?php echo htmlspecialchars($channels['id']);?>" />
                            <input type="hidden" name="name" value="<?php echo htmlspecialchars($channels['name']);?>" />
                            <?php $presentSubscription = \LiveHelperChatExtension\mbird\providers\erLhcoreClassModelMBirdChannel::findOne(['filter' => ['channel_id' => $channels['id']]]); ?>
                            <?php include(erLhcoreClassDesign::designtpl('lhkernel/csfr_token.tpl.php'));?>
                            <div class="row">
                                <div class="col-4"><input type="text" title="Department ID" name="dep_id" value="<?php (isset($_POST['channel_id']) && $channels['id'] == $_POST['channel_id']) ? print htmlspecialchars($_POST['dep_id']) : print (is_object($presentSubscription) ? $presentSubscription->dep_id : '');?>" class="form-control form-control-sm" placeholder="Department ID"></div>
                                <div class="col-4"><button type="submit" class="btn btn-sm btn-success w-100" name="UpdateDepartment">Update Department only</button></div>
                                <div class="col-4">
                                    <?php if (\LiveHelperChatExtension\mbird\providers\erLhcoreClassModelMBirdChannel::getCount(['filter' => ['channel_id' => $channels['id']]]) === 0) : ?>
                                        <button type="submit" class="btn btn-sm btn-success w-100" name="Subscribe">Subscribe</button>
                                    <?php else : ?>
                                        <button type="submit" class="btn btn-sm btn-warning w-100" name="UnSubscribe">Un-Subscribe</button>
                                    <?php endif; ?>
                                </div>

                            </div>
                        </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    <?php endif; ?>

    <button name="StoreOptions" class="btn btn-sm btn-secondary" type="submit"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Save'); ?></button>

</form>