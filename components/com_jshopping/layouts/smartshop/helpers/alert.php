<?php 
    $typesClass = [
        'success' => [
            'class' => 'alert-success',
            'title' => JText::_('SUCCESS')
        ],
        'error' => [
            'class' => 'alert-error',
            'title' => JText::_('ERROR')
        ],
        'warning' => [
            'class' => 'alert-warning',
            'title' => JText::_('WARNING')
        ],
    ];

    $alertType = $displayData['type'];
    $messageText = $displayData['message'] ?: '';
    $titleText = $displayData['title'] ?: $typesClass[$alertType]['title'];
    $className = $typesClass[$alertType]['class'];
    $isCloseableAlert = $displayData['isCloseable'] ?? true;
?>

<div class="alert <?php echo $className; ?>">

    <?php if ($isCloseableAlert) : ?>
        <button type="button" class="close" data-dismiss="alert" data-bs-dismiss="alert">×</button>
    <?php endif; ?>

    <h4 class="alert-heading"><?php echo $titleText; ?></h4>

    <div class="alert-message">
        <?php echo $messageText; ?>
    </div>
</div>