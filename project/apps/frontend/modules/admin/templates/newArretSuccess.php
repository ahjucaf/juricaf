<div class="m-5">
    <h1 class="py-3">Création d'un nouvel arret</h1>
    <div class="row">
        <div class="card col-6">
            <div class="card-body">
                <?php if ($sf_user->hasFlash('error')):?><div class="alert alert-danger" role="alert"><?php echo $sf_user->getFlash('error'); ?></div><?php endif; ?>
                <?php if ($sf_user->hasFlash('notice')):?><div class="alert alert-success" role="alert"><?php echo $sf_user->getFlash('notice'); ?></div><?php endif; ?>
                <?php if ($form->hasGlobalErrors()):?><div class="alert alert-danger" role="alert"><?php echo $form->renderGlobalErrors(); ?></div><?php endif; ?>
                <form action="<?php echo url_for('@new_arret'); if ($form->getFileName()) { echo '?arret=' . $form->getFileName(); } ?>" method="POST" enctype="multipart/form-data">
                    <?php echo $form->renderHiddenFields(); ?>
                    <div class="g-3 align-items-center">
                        <div class="col-3">
                            <?php echo $form['PAYS']->renderLabel(); ?><span class="text-danger"> *</span>
                        </div>
                        <div class="col-8">
                            <?php
                            echo $form['PAYS']->render(['class' => 'form-control', 'placeholder' => 'Ex. : Madagascar', 'tabindex' => '1']);
                            ?>
                        </div>
                        <?php if ($form['PAYS']->hasError()): ?>
                            <div class="text-danger col-9 offset-3" role="alert"><?php echo $form['PAYS']->renderError(); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class="g-3 align-items-center mt-2">
                        <div class="col-3">
                            <?php echo $form['JURIDICTION']->renderLabel(); ?><span class="text-danger"> *</span>
                        </div>
                        <div class="col-8">
                            <?php
                            echo $form['JURIDICTION']->render(['class' => 'form-control', 'placeholder' => 'Ex. : Cour de cassation', 'tabindex' => '2']);
                            ?>
                        </div>
                        <?php if ($form['JURIDICTION']->hasError()): ?>
                            <div class="text-danger col-9 offset-3" role="alert"><?php echo $form['JURIDICTION']->renderError(); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class=" g-3  align-items-center mt-2">
                        <div class="col-3">
                            <?php echo $form['DATE_ARRET']->renderLabel(); ?><span class="text-danger"> *</span>
                        </div>
                        <div class="col-8">
                            <?php
                            echo $form['DATE_ARRET']->render(['type' => 'date', 'class' => 'form-control', 'placeholder' => 'Ex. : 11/03/1999', 'tabindex' => '3']);
                            ?>
                        </div>
                        <?php if ($form['DATE_ARRET']->hasError()): ?>
                            <div class="text-danger col-9 offset-3" role="alert"><?php echo $form['DATE_ARRET']->renderError(); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class=" g-3  align-items-center mt-2">
                        <div class="col-3">
                            <?php echo $form['NUM_ARRET']->renderLabel(); ?><span class="text-danger"> *</span>
                        </div>
                        <div class="col-8">
                            <?php
                            echo $form['NUM_ARRET']->render(['class' => 'form-control', 'placeholder' => 'Ex. : 17-HCC/D3', 'tabindex' => '4']);
                            ?>
                        </div>
                        <?php if ($form['NUM_ARRET']->hasError()): ?>
                            <div class="text-danger col-9 offset-3" role="alert"><?php echo $form['NUM_ARRET']->renderError(); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class=" g-3  align-items-center mt-2">
                        <div class="col-4">
                            <?php echo $form['FONDS_DOCUMENTAIRE']->renderLabel(); ?>
                        </div>
                        <div class="col-8">
                            <?php
                            echo $form['FONDS_DOCUMENTAIRE']->render(['class' => 'form-control', 'placeholder' => 'Ex. : HuDoc', 'tabindex' => '6']);
                            ?>
                        </div>
                        <?php if ($form['FONDS_DOCUMENTAIRE']->hasError()): ?>
                            <div class="text-danger col-9 offset-3" role="alert"><?php echo $form['FONDS_DOCUMENTAIRE']->renderError(); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class=" g-3  align-items-center mt-2">
                        <div class="col-3">
                            <?php echo $form['TYPE_AFFAIRE']->renderLabel(); ?>
                        </div>
                        <div class="col-8">
                            <?php
                            echo $form['TYPE_AFFAIRE']->render(['class' => 'form-control', 'placeholder' => 'Ex. : Décision (finale)', 'tabindex' => '7']);
                            ?>
                        </div>
                        <?php if ($form['TYPE_AFFAIRE']->hasError()): ?>
                            <div class="text-danger col-9 offset-3" role="alert"><?php echo $form['TYPE_AFFAIRE']->renderError(); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class=" g-3  align-items-center mt-2">
                        <div class="col-3">
                            <?php echo $form['FORMATION']->renderLabel(); ?>
                        </div>
                        <div class="col-8">
                            <?php
                            echo $form['FORMATION']->render(['class' => 'form-control', 'placeholder' => 'Ex. : Première chambre', 'tabindex' => '8']);
                            ?>
                        </div>
                        <?php if ($form['FORMATION']->hasError()): ?>
                            <div class="text-danger col-9 offset-3" role="alert"><?php echo $form['FORMATION']->renderError(); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class=" g-3  align-items-center mt-2">
                        <div class="col-3">
                            <?php echo $form['IMPORTANCE']->renderLabel(); ?>
                        </div>
                        <div class="col-8">
                            <?php
                            echo $form['IMPORTANCE']->render(['class' => 'form-control', 'placeholder' => 'Ex. : 1', 'tabindex' => '9']);
                            ?>
                        </div>
                        <?php if ($form['IMPORTANCE']->hasError()): ?>
                            <div class="text-danger col-9 offset-3" role="alert"><?php echo $form['IMPORTANCE']->renderError(); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class=" g-3  align-items-center mt-2">
                        <div class="col-3">
                            <?php echo $form['TYPE_RECOURS']->renderLabel(); ?>
                        </div>
                        <div class="col-8">
                            <?php
                            echo $form['TYPE_RECOURS']->render(['class' => 'form-control', 'placeholder' => 'Ex. : Partiellement irrecevable ; Sursoit à statuer sur la recevabilité du grief mentionné au par. 29 ci-dessus, en attendant les observations écrites du gouvernement défendeur', 'tabindex' => '10']);
                            ?>
                        </div>
                        <?php if ($form['TYPE_RECOURS']->hasError()): ?>
                            <div class="text-danger col-9 offset-3" role="alert"><?php echo $form['TYPE_RECOURS']->renderError(); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class=" g-3  align-items-center mt-2">
                        <div class="col-3">
                            <?php echo $form['CITATION_ARTICLE']->renderLabel(); ?>
                        </div>
                        <div class="col-8">
                            <?php
                            echo $form['CITATION_ARTICLE']->render(['class' => 'form-control', 'placeholder' => 'Ex. : 21; R17', 'tabindex' => '11']);
                            ?>
                        </div>
                        <?php if ($form['CITATION_ARTICLE']->hasError()): ?>
                            <div class="text-danger col-9 offset-3" role="alert"><?php echo $form['CITATION_ARTICLE']->renderError(); ?></div>
                        <?php endif; ?>
                    </div>
                    <div class=" g-3  align-items-center mt-2">
                        <div class="col-3">
                            <?php echo $form['SOURCE']->renderLabel(); ?>
                        </div>
                        <div class="col-8">
                            <?php
                            echo $form['SOURCE']->render(['class' => 'form-control', 'placeholder' => 'Ex. : http://cmiskp.echr.coe.int/tkp19/view.asp?action=html&documentId=676187', 'tabindex' => '13']);
                            ?>
                        </div>
                        <?php if ($form['SOURCE']->hasError()): ?>
                            <div class="text-danger col-9 offset-3" role="alert"><?php echo $form['SOURCE']->renderError(); ?></div>
                        <?php endif; ?>
                    </div>


                    <div class="accordion mt-2" id="partiesAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="partiesHeading">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#partiesCollapse" aria-expanded="true" aria-controls="partiesCollapse">
                                    PARTIES
                                </button>
                            </h2>
                            <div id="partiesCollapse" class="accordion-collapse collapse" aria-labelledby="partiesHeading">
                                <div class="accordion-body">
                                    <div class="accordion" id="demandeursAccordion">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="demandeursHeading">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#demandeursCollapse" aria-expanded="true" aria-controls="demandeursCollapse">
                                                    DEMANDEURS
                                                </button>
                                            </h2>
                                            <div id="demandeursCollapse" class="accordion-collapse collapse" aria-labelledby="demandeursHeading">
                                                <div class="accordion-body">
                                                    <div class=" g-3  align-items-center mt-2">
                                                    <?php echo $form['DEMANDEUR']->renderLabel(); ?>
                                                    <?php echo $form['DEMANDEUR']->render(['class' => 'form-control', 'placeholder' => 'Ex. : Stéphane Roux', 'tabindex' => '14']); ?>
                                                    <?php if ($form['DEMANDEUR']->hasError()): ?>
                                                        <div class="text-danger" role="alert"><?php echo $form['DEMANDEUR']->renderError(); ?></div>
                                                    <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="accordion mt-2" id="defendeursAccordion">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="defendeursHeading">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#defendeursCollapse" aria-expanded="true" aria-controls="defendeursCollapse">
                                                    DEFENDEURS
                                                </button>
                                            </h2>
                                            <div id="defendeursCollapse" class="accordion-collapse collapse" aria-labelledby="defendeursHeading">
                                                <div class="accordion-body">
                                                    <div class=" g-3  align-items-center mt-2">
                                                    <?php echo $form['DEFENDEUR']->renderLabel(); ?>
                                                    <?php echo $form['DEFENDEUR']->render(['class' => 'form-control', 'placeholder' => 'Ex. : Autorité pour la Valorisation des Actives de l\'Etat', 'tabindex' => '15']); ?>
                                                    <?php if ($form['DEFENDEUR']->hasError()): ?>
                                                        <div class="text-danger" role="alert"><?php echo $form['DEFENDEUR']->renderError(); ?></div>
                                                    <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion mt-2" id="analysesAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="analysesHeading">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#analysesCollapse" aria-expanded="true" aria-controls="analysesCollapse">
                                    ANALYSES
                                </button>
                            </h2>
                            <div id="analysesCollapse" class="accordion-collapse collapse" aria-labelledby="analysesHeading">
                                <div class="accordion-body">
                                    <div class="accordion" id="analyseAccordion">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="analyseHeading">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#analyseCollapse" aria-expanded="true" aria-controls="analyseCollapse">
                                                    ANALYSE
                                                </button>
                                            </h2>
                                            <div id="analyseCollapse" class="accordion-collapse collapse show" aria-labelledby="analyseHeading">
                                                <div class="accordion-body">
                                                    <div class=" g-3  align-items-center mt-2">
                                                    <?php echo $form['TITRE_PRINCIPAL']->renderLabel(); ?>
                                                    <?php echo $form['TITRE_PRINCIPAL']->render(['class' => 'form-control', 'placeholder' => 'Ex. : Révocation - Annulation de la décision - Impossibilité de substituer la décision annulée - Refus de réintégration - Execution sous astreinte impossible - Action en indemnité', 'tabindex' => '16']); ?>
                                                    <?php if ($form['TITRE_PRINCIPAL']->hasError()): ?>
                                                        <div class="text-danger" role="alert"><?php echo $form['TITRE_PRINCIPAL']->renderError(); ?></div>
                                                    <?php endif; ?>
                                                    </div>
                                                    <div class=" g-3  align-items-center mt-2">
                                                    <?php echo $form['SOMMAIRE']->renderLabel(); ?>
                                                    <?php echo $form['SOMMAIRE']->render(['class' => 'form-control', 'placeholder' => 'Ex. : Les jugements rendus dans le cadre de recours en annulation doivent se limiter à annuler les décisions entachées d\'illégalité sans possibilité de remplacer la décision annulée.', 'tabindex' => '17']); ?>
                                                    <?php if ($form['SOMMAIRE']->hasError()): ?>
                                                        <div class="text-danger" role="alert"><?php echo $form['SOMMAIRE']->renderError(); ?></div>
                                                    <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="accordion mt-2" id="referencesAccordion">
                        <div class="accordion-item">
                            <h2 class="accordion-header" id="referencesHeading">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#referencesCollapse" aria-expanded="true" aria-controls="referencesCollapse">
                                    REFERENCES
                                </button>
                            </h2>
                            <div id="referencesCollapse" class="accordion-collapse collapse" aria-labelledby="referencesHeading">
                                <div class="accordion-body">
                                    <div class="accordion" id="referenceAccordion">
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="referenceHeading">
                                                <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#referenceCollapse" aria-expanded="true" aria-controls="referenceCollapse">
                                                    REFERENCE
                                                </button>
                                            </h2>
                                            <div id="referenceCollapse" class="accordion-collapse collapse show" aria-labelledby="referenceHeading">
                                                <div class="accordion-body">
                                                    <div class=" g-3  align-items-center mt-2">
                                                        <?php echo $form['TYPE']->renderLabel(); ?>
                                                        <?php echo $form['TYPE']->render(['class' => 'form-control', 'placeholder' => 'Ex. : Source', 'tabindex' => '18']); ?>
                                                        <?php if ($form['TYPE']->hasError()): ?>
                                                            <div class="text-danger" role="alert"><?php echo $form['TYPE']->renderError(); ?></div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class=" g-3  align-items-center mt-2">
                                                        <?php echo $form['TITRE']->renderLabel(); ?>
                                                        <?php echo $form['TITRE']->render(['class' => 'form-control', 'placeholder' => 'Ex. : Fichier PDF', 'tabindex' => '19']); ?>
                                                        <?php if ($form['TITRE']->hasError()): ?>
                                                            <div class="text-danger" role="alert"><?php echo $form['TITRE']->renderError(); ?></div>
                                                        <?php endif; ?>
                                                    </div>
                                                    <div class=" g-3  align-items-center mt-2">
                                                        <?php echo $form['URL']->renderLabel(); ?>
                                                        <?php echo $form['URL']->render(['class' => 'form-control', 'placeholder' => 'Ex. : http://www.juriburkina.org/juriburkina/displayDocument.do?id=3941', 'tabindex' => '20']); ?>
                                                        <?php if ($form['URL']->hasError()): ?>
                                                            <div class="text-danger" role="alert"><?php echo $form['URL']->renderError(); ?></div>
                                                        <?php endif; ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="g-3  align-items-center mt-2">
                        <div class="col-3">
                            <?php echo $form['PUBLICATION']->renderLabel(); ?>
                        </div>
                        <div class="col-8">
                            <?php
                            echo $form['PUBLICATION']->render(['class' => 'form-control', 'placeholder' => 'Ex. : Ouvrage : Arrêts de la Chambre Administrative - 50 ans, Cour suprême - Centre de publication et de Documentation Judiciaire - , p.314, (2007)', 'tabindex' => '21']);
                            ?>
                        </div>
                        <?php if ($form['PUBLICATION']->hasError()): ?>
                            <div class="text-danger col-9 offset-3" role="alert"><?php echo $form['PUBLICATION']->renderError(); ?></div>
                        <?php endif; ?>
                    </div>
            </div>
        </div>
        <div class="card col-6">
            <div class="card-body">
                <div class="align-items-center">
                    <div class="col-3">
                        <?php echo $form['TEXTE_ARRET']->renderLabel(); ?><span class="text-danger"> *</span>
                    </div>
                    <div class="col-12">
                        <?php
                        echo $form['TEXTE_ARRET']->render(['type' => 'textarea', 'class' => 'form-control', 'tabindex' => '5', 'rows' => '41']);
                        ?>
                    </div>
                    <?php if ($form['TEXTE_ARRET']->hasError()): ?>
                        <div class="text-danger col-9 offset-3" role="alert"><?php echo $form['TEXTE_ARRET']->renderError(); ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <div class="col-2 offset-5 mt-3">
            <input type="submit" class="form-control btn btn-primary" tabindex="22" value="Envoyer"/>
        </div>
        </form>
    </div>

</div>
