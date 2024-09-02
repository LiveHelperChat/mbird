<h1><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Present subscriptions'); ?></h1>

<form action="" method="post" ng-non-bindable>

    <?php include(erLhcoreClassDesign::designtpl('lhkernel/csfr_token.tpl.php'));?>

    <?php if (isset($updated) && $updated == 'done') : $msg = erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Settings updated'); ?>
        <?php include(erLhcoreClassDesign::designtpl('lhkernel/alert_success.tpl.php'));?>
    <?php endif; ?>

    <?php if (isset($errors)) : ?>
        <?php include(erLhcoreClassDesign::designtpl('lhkernel/validation_error.tpl.php'));?>
    <?php endif; ?>

    <?php if (isset($subscriptions['results']) && !empty($subscriptions['results'])) : ?>
    <table class="table list-links table-sm">
        <tr>
            <th>id</th>
            <th>organizationId</th>
            <th>workspaceId</th>
            <th>service</th>
            <th>event</th>
            <th>eventFilters</th>
            <th>URL</th>
            <th>status</th>
        </tr>
        <?php foreach ($subscriptions['results'] as $subscription) : ?>
        <tr>
            <td><?php echo htmlspecialchars($subscription['id']);?></td>
            <td><?php echo htmlspecialchars($subscription['organizationId']);?></td>
            <td><?php echo htmlspecialchars($subscription['workspaceId']);?></td>
            <td><?php echo htmlspecialchars($subscription['service']);?></td>
            <td><?php echo htmlspecialchars($subscription['event']);?></td>
            <td><?php echo htmlspecialchars(json_encode($subscription['eventFilters']));?></td>
            <td><?php echo htmlspecialchars($subscription['url']);?></td>
            <td><?php echo htmlspecialchars($subscription['status']);?></td>
        </tr>
        <?php endforeach; ?>
    </table>
    <?php endif; ?>

    <button name="StoreOptions" class="btn btn-sm btn-secondary" type="submit"><?php echo erTranslationClassLhTranslation::getInstance()->getTranslation('messagebird/module','Save'); ?></button>

</form>