<div class="m-5">
    <h1 class="py-3">Upload d'un nouvel arret</h1>
    <div class="card col-6">
        <div class="card-body">
            <?php if ($sf_user->hasFlash('error')):?><div class="alert alert-danger" role="alert"><?php echo $sf_user->getFlash('error'); ?></div><?php endif; ?>
            <?php if ($sf_user->hasFlash('notice')):?><div class="alert alert-success" role="alert"><?php echo $sf_user->getFlash('notice'); ?></div><?php endif; ?>
            <?php if ($form->hasGlobalErrors()):?><div class="alert alert-danger" role="alert"><?php echo $form->renderGlobalErrors(); ?></div><?php endif; ?>
            <form action="<?php echo url_for('new_arret') ?>" method="POST" enctype="multipart/form-data">
                <?php echo $form->renderHiddenFields(); ?>
                <div class="row g-3 align-items-center">
                    <div class="col-3">
                        <?php echo $form['pays']->renderLabel(); ?>
                    </div>
                    <div class="col-8">
                        <?php echo $form['pays']->render(['class' => 'form-control']); ?>
                    </div>
                    <?php if ($form['pays']->hasError()): ?>
                        <div class="text-danger col-9 offset-3" role="alert"><?php echo $form['pays']->renderError(); ?></div>
                    <?php endif; ?>
                </div>
                <div class="row g-3 align-items-center">
                    <div class="col-3">
                        <?php echo $form['juridiction']->renderLabel(); ?>
                    </div>
                    <div class="col-8">
                        <?php echo $form['juridiction']->render(['class' => 'form-control']); ?>
                    </div>
                    <?php if ($form['juridiction']->hasError()): ?>
                        <div class="text-danger col-9 offset-3" role="alert"><?php echo $form['juridiction']->renderError(); ?></div>
                    <?php endif; ?>
                </div>
                <div class="row g-3 align-items-center offset-3 col-8 py-3">
                    <input type="submit" class="form-control btn btn-primary" value="Envoyer"/>
                </div>
            </form>
        </div>
    </div>

</div>
