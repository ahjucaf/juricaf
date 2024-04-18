<?php $sf_response->setTitle("Juricaf - Étendue des collections"); ?>
<div class="container">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="/">Accueil</a></li>
        <li class="breadcrumb-item"><a href="">Étendue des collections</a></li>
    </ol>
</div>
<div class="container mt-5">
    <h5 class="p-3 mb-2 bg-secondary bg-gradient">Statuts et licences des collections</h5>
    <?php include(__DIR__.'/../../../../../../stats/static/base.html'); ?>
    <div>
        <p>Statistiques brutes de <a href="/documentation/stats/base.csv">mis à jour</a> et de <a href="/documentation/stats/champs.csv">champs disponibles</a>.</p>
    </div>
    <div class="text-end"><a id="top" href="#">Haut de page</a></div>
</div>
