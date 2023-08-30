<div class="m-5">
    <h1 class="py-3">Création d'un nouvel arret</h1>
    <div class="card col-6">
        <div class="card-body">
            <?php if ($sf_user->hasFlash('error')):?><div class="alert alert-danger" role="alert"><?php echo $sf_user->getFlash('error'); ?></div><?php endif; ?>
            <?php if ($sf_user->hasFlash('notice')):?><div class="alert alert-success" role="alert"><?php echo $sf_user->getFlash('notice'); ?></div><?php endif; ?>
            <?php if ($form->hasGlobalErrors()):?><div class="alert alert-danger" role="alert"><?php echo $form->renderGlobalErrors(); ?></div><?php endif; ?>
            <form action="<?php echo url_for('@new_arret'); if ($form->getFileName()) { echo '?arret=' . $form->getFileName(); } ?>" method="POST" enctype="multipart/form-data">
                <?php echo $form->renderHiddenFields(); ?>
                <div class="row g-3 align-items-center">
                    <div class="col-3">
                        <?php echo $form['PAYS']->renderLabel(); ?><span class="text-danger"> *</span>
                    </div>
                    <div class="col-8">
                        <?php
                            echo $form['PAYS']->render(['class' => 'form-control', 'placeholder' => 'Ex. : Madagascar', 'value' => $form->getPaysValue()]);
                        ?>
                    </div>
                    <?php if ($form['PAYS']->hasError()): ?>
                        <div class="text-danger col-9 offset-3" role="alert"><?php echo $form['PAYS']->renderError(); ?></div>
                    <?php endif; ?>
                </div>
                <div class="row g-3 align-items-center">
                    <div class="col-3">
                        <?php echo $form['JURIDICTION']->renderLabel(); ?><span class="text-danger"> *</span>
                    </div>
                    <div class="col-8">
                        <?php
                            echo $form['JURIDICTION']->render(['class' => 'form-control', 'placeholder' => 'Ex. : Cour de cassation', 'value' => $form->getJuriValue()]);
                        ?>
                    </div>
                    <?php if ($form['JURIDICTION']->hasError()): ?>
                        <div class="text-danger col-9 offset-3" role="alert"><?php echo $form['JURIDICTION']->renderError(); ?></div>
                    <?php endif; ?>
                </div>
                <div class="row g-3 align-items-center">
                    <div class="col-3">
                        <?php echo $form['DATE_ARRET']->renderLabel(); ?><span class="text-danger"> *</span>
                    </div>
                    <div class="col-8">
                        <?php
                        echo $form['DATE_ARRET']->render(['type' => 'date', 'class' => 'form-control', 'placeholder' => 'Ex. : 11/03/1999', 'value' => $form->getDateArretValue()]);
                        ?>
                    </div>
                    <?php if ($form['DATE_ARRET']->hasError()): ?>
                        <div class="text-danger col-9 offset-3" role="alert"><?php echo $form['DATE_ARRET']->renderError(); ?></div>
                    <?php endif; ?>
                </div>
                <div class="row g-3 align-items-center">
                    <div class="col-3">
                        <?php echo $form['NUM_ARRET']->renderLabel(); ?><span class="text-danger"> *</span>
                    </div>
                    <div class="col-8">
                        <?php
                        echo $form['NUM_ARRET']->render(['class' => 'form-control', 'placeholder' => 'Ex. : 17-HCC/D3', 'value' => $form->getNumArretValue()]);
                        ?>
                    </div>
                    <?php if ($form['NUM_ARRET']->hasError()): ?>
                        <div class="text-danger col-9 offset-3" role="alert"><?php echo $form['NUM_ARRET']->renderError(); ?></div>
                    <?php endif; ?>
                </div>
                <div class="row g-3 align-items-center offset-3 col-8 py-3">
                    <input type="submit" class="form-control btn btn-primary" value="Envoyer"/>
                </div>
            </form>
        </div>
    </div>

</div>
