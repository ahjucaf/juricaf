<?php
// Token
@session_start();
$token = sha1(mt_rand());
$_SESSION['token'] = $token;
?>
<?php include('header.php')?>
<div class="container">
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="/">Accueil</a></li>
      <li class="breadcrumb-item"><a href="">Mentions légales</a></li>
    </ol>
</div>
          <div class="container text-justify mt-5">
            <h5 class="p-3 mb-2 bg-secondary bg-gradient">Mentions légales</h5>

            <div class="container">
            <h5 class="mt-3 mb-3"><u>Editeur</u></h5>

            <p>Le site www.juricaf.org est placé sous la responsabilité éditoriale de l’AHJUCAF (Association des Hautes JUridictions de CAssation des pays ayant en partage l’usage du Français), association loi 1901 inscrite au registre des déclarations d’association de la Préfecture de police de Paris, publié au Journal officiel du 9 juin 2001.</p>

            <p>L’AHJUCAF est inscrite au répertoire national des entreprises et de leurs établissements sous le numéro SIREN 440 233 880 (APE 913 E).</p>

            <p>AHJUCAF<br/>
            Cour de cassation<br/>
            5 quai de l’Horloge 75001 PARIS<br/>
            Tel. : +33 1 46 34 67 40</p>

            <h5 class="mt-3 mb-3"><u>Directeur de Publication</u></h5>

            <p>M. Jean-Paul JEAN, Secrétaire général de l’AHJUCAF<br/>
            AHJUCAF, 5 quai de l’Horloge, 75001 PARIS</p>

            <h5 class="mt-3 mb-3"><u>Coordinateur technique</u></h5>

        <p>M. Thomas FRINCHABOY, chargé de mission auprès du Secrétaire général de l’AHJUCAF<br/>
        AHJUCAF, 5 quai de l’Horloge, 75001 PARIS</p>

            <h5 class="mt-3 mb-3"><u>Hébergeur</u></h5>
        <p>ONLINE SAS BP 438 PARIS CEDEX 08 </p>

            <h5 class="mt-3 mb-3"><u>Webmestre</u></h5>
        <p>Mme Amélie BIDARD DE LA NOE<br/>
            AHJUCAF, 5 quai de l’Horloge 75055 PARIS Cedex 01</p>

            <h5 class="mt-3 mb-3"><u>Droits de propriété intellectuelle</u></h5>

        <p>Contexte :</p>

        <p>L’Association des Hautes Juridictions de Cassation des pays ayant en partage l’usage du Français (AHJUCAF) a été créée en 2001 à l’initiative de 34 Juridictions Suprêmes francophones et l’Organisation internationale de la Francophonie. Par ses statuts (article 4) l’AHJUCAF a pour mission de :<br/>
            - favoriser l’entraide, la solidarité, la coopération, les échanges d’idées et d’expériences entre les institutions judiciaires membres sur les questions relevant de leur compétence ou intéressant leur organisation et leur fonctionnement ;<br/>
            - promouvoir le rôle des Hautes Juridictions dans la consolidation de l’Etat de droit, le renforcement de la sécurité juridique, la régulation des décisions judiciaires et l’harmonisation du droit au sein des États membres.</p>

        <p>Dans ce cadre, l’AHJUCAF a créé la base de données JURICAF accessible sur internet à l’adresse www.juricaf.org. Il s’agit d’une base de données de décisions de justice en langue française de 46 pays et institutions.</p>

        <p>L’AHJUCAF est titulaire du droit sui generis (titre IV, livre III du Code de la propriété intellectuelle) sur la base de données JURICAF accessible sur Internet à l’adresse www.juricaf.org.</p>

            <h5 class="mt-3"><u>Mise à disposition et réutilisation des données</u></h5>
        <p>Les données de JURICAF sont en accès libre. Sauf pour les décisions des juridictions françaises, ces données sont protégées par le Code de la propriété intellectuelle et diffusées sous la licence ODbL 1.0. Elle est consultable à l'adresse : <a href="/documentation/licence_odbl.php">https://juricaf.org/documentation/licence_odbl.php</a></p>

        <p>La réutilisation est autorisée dans les conditions de la licence ODbL (<a href="http://vvlibri.org/fr/licence/odbl-10/legalcode/unofficial">version française de la licence ODbL</a>, <a href="https://blog.vvlibri.org/public/docs/OpenData/ODbL_fr_VF.pdf">mode d’emploi</a> )</p>

        <p>Si vous ne voulez pas ou ne souhaitez pas réutiliser les décisions dans les conditions de la licence ODbL ou si vous souhaitez disposer de fichiers XML structurés selon vos besoins ou tout autre prestation, vous pouvez contacter le secrétariat de l’AHJUCAF pour toute information utile via le formulaire de contact de ce site. Plus d’informations dans la page politique de confidentialité/données personnelles.</p>


            <h5 class="mt-3"><u>Données personnelles</u></h5>
        <p>Pour tout ce qui concerne les données personnelles, consultez la page <a href="donnees_personnelles.php">politique de confidentialité/données personnelles</a>.</p>


        <br/><br/><br/>
        <small class="text-secondary"><i>Les  mentions légales et la politique de confidentialité de ce site ont été rédigées avec la collaboration de Maître Blandine Cornevin et Maître Irène Kris, réseau ELOKIA AVOCATS</i></small>

          </div>
        </div>
      </div>
    </div>


<?php include("footer.php") ?>
