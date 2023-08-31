<div class="m-5">
    <h1 class="py-3">Apercu du nouvel arret</h1>
    <div class="card col-6">
        <div class="card-body">
            <?php if ($sf_user->hasFlash('error')):?><div class="alert alert-danger" role="alert"><?php echo $sf_user->getFlash('error'); ?></div><?php endif; ?>
            <?php if ($sf_user->hasFlash('notice')):?><div class="alert alert-success" role="alert"><?php echo $sf_user->getFlash('notice'); ?></div><?php endif; ?>
            <div class="row g-3 align-items-center">
                <h5>Format texte brut</h5>
                <?php foreach ($displayForm->getFlatArray() as $key => $value): ?>
                    <div class="col-3"><?php echo $key; ?></div>
                    <div class="col-8"><?php echo $value; ?></div>
                <?php endforeach; ?>
            </div>
            <div class="row g-3 align-items-center">
                <h5>Format XML</h5>
                <pre><?php echo htmlspecialchars($displayForm->getFormatXmlData()); ?></pre>
            </div>
            <div class="row">
                <form method="POST" action="<?php echo $redirect_url_new; ?>" enctype="multipart/form-data">
                    <?php echo $displayForm['_csrf_token']->render(); ?>
                    <div class="align-items-center col-6">
                        <input type="submit" class="form-control btn btn-default" value="Modifier"/>
                    </div>
                </form>
                <div class="align-items-center col-6">
                    <input type="submit" class="form-control btn btn-primary" value="Valider"/>
                </div>
            </div>
        </div>
    </div>

</div>


