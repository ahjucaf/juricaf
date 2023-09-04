<div class="m-5">
    <h1 class="py-3">Apercu du nouvel arret</h1>
    <div class="card col-12">
        <div class="card-body">
            <?php if ($sf_user->hasFlash('error')):?><div class="alert alert-danger" role="alert"><?php echo $sf_user->getFlash('error'); ?></div><?php endif; ?>
            <?php if ($sf_user->hasFlash('notice')):?><div class="alert alert-success" role="alert"><?php echo $sf_user->getFlash('notice'); ?></div><?php endif; ?>
            <div class="row g-3 align-items-start">
                <h5>Format texte brut</h5>
                <?php foreach ($displayForm->getFlatArray() as $key => $value): ?>
                    <?php if ($key === 'TEXTE_ARRET'): ?>
                        <div class="col-3"><?php echo $key; ?></div>
                        <pre class="col-8"><?php
                                $lines = explode("\n", $value);
                                for ($i = 0; $i < min(50, count($lines)); $i++) { echo $lines[$i] . "\n";}?>
                        </pre>
                    <?php else: ?>
                        <div class="col-3"><?php echo $key; ?></div>
                        <div class="col-8"><?php echo $value; ?></div>
                    <?php endif ?>
                <?php endforeach; ?>
            </div>
            <div class="row g-3 align-items-center">
                <h5>Format XML</h5>
                <pre><?php echo htmlspecialchars($displayForm->getFormatXmlData()); ?></pre>
            </div>
            <div class="row">
                <div class="align-items-center col-6">
                    <a href="<?php echo url_for('@new_arret?arret=' . $displayForm->getFileName()); ?>" type="submit" class="form-control btn btn-default">Modifier</a>
                </div>
                <div class="align-items-center col-6">
                    <a href="<?php echo url_for('@validate_arret?arret=' . $displayForm->getFileName()); ?>" type="submit" class="form-control btn btn-primary">Valider</a>
                </div>
            </div>
        </div>
    </div>

</div>


