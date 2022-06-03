<?php
// Token
@session_start();
$token = sha1(mt_rand());
$_SESSION['token'] = $token;
?>
<div class="container">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/">Accueil</a></li>
      <li class="breadcrumb-item"><a href="/static/mentionslegales">Mentions légales</a></li>
      <li class="breadcrumb-item"><a href="">Licence ODbL</a></li>
    </ol>
</div>

<div class="container text-justify mt-5">

	 <h5 class="p-3 mb-2 bg-secondary bg-gradient">Résumé de la licence ODbL 1.0 fr</h5>


     <p>Ceci est le résumé explicatif de <a href="http://vvlibri.org/fr/licence/odbl-10/legalcode/unofficial">la licence ODbL 1.0</a>. Merci de lire l'avertissement ci-dessous.</p>

<h5><u>Vous êtes libres :</u></h5>

<ul class="list-unstyled">
<li><img src="img/share.png" class="bb-image" /> <span class="fts-italic">De partager :</span> copier, distribuer et utiliser la base de données.</li>
<li><img src="img/create.png" alt="" class="bb-image" /> <span class="fts-italic" >De créer :</span> produire des créations à partir de cette base de données.</li>

<li><img src="img/adapt.png" alt="" class="bb-image" /> <span class="fts-italic">D'adapter :</span> modifier, transformer et construire à partir de cette base de données.</li>
</ul>

<h5><u>Aussi longtemps que :</u></h5>

<ul class="list-unstyled">
<li><img src="img/attribute.png" alt="" class="bb-image" /> <span class="fts-italic">Vous mentionnez la paternité :</span> vous devez mentionnez la source de la base de données pour toute utilisation publique de la base de données, ou pour toute création produite à partir de la base de données, de la manière indiquée dans l'ODbL. Pour toute utilisation ou redistribution de la base de données, ou création produite à partir de cette base de données, vous devez clairement mentionner aux tiers la licence de la base de données et garder intacte toute mention légale sur la base de données originaire.</li>
<li><img src="img/share_alike2.png" alt="" class="bb-image" /> <span class="fts-italic">Vous partagez aux conditions identiques :</span> si vous utilisez publiquement une version adaptée de cette base de données, ou que vous produisiez une création à partir d'une base de données adaptée, vous devez aussi offrir cette base de données adaptée selon les termes de la licence ODbL.</li>

<li><img src="img/keep_open.png" alt="" class="bb-image" /> <span class="fts-italic">Gardez ouvert :</span> si vous redistribuez la base de données, ou une version modifiée de celle-ci, alors vous ne pouvez utiliser de mesure technique restreignant la création que si vous distribuez aussi une version sans ces restrictions.</li></ul>

<h5>Avertissement</u></h5>

<p>Le résumé explicatif n'est pas un contrat, mais simplement une source pratique pour faciliter la compréhension de la version complète de la licence ODbL 1.0 — il exprime en termes courants les principales notions juridiques du contrat. Ce résumé explicatif n'a pas de valeur juridique, son contenu n'apparaît pas sous cette forme dans le contrat. Seul le <a href="licence_odbl_juricaf.pdf">texte complet du contrat de licence</a> fait loi.</p>
</div>
</div>
</div>
