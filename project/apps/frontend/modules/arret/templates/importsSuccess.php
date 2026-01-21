<h1 style="text-align: center; margin-top:10px;">Imports sous 30 jours de la base Juricaf</h1>
<div>
  <div class="imports_pays_juridiction">
    <?php setlocale(LC_TIME, 'fr_FR.UTF-8'); $selectedDate = new DateTime($selectedDate); $formattedDateFR = strftime('%A %d %B %Y', $selectedDate->getTimestamp()); ?>
    <p>Imports de décisions de cours suprême des 30 jours précédents le <span style="font-weight:800;"><?php echo $formattedDateFR; ?></span>.</p>

     <table class="table table-striped table-bordered">
       <thead>
         <tr>
           <th scope="col">Pays</th>
           <th scope="col">Nombre d'imports</th>
         </tr>
       </thead>
       <tbody>
         <?php
         function replaceBlank($nomPays) {
           return str_replace (' ', '_', $nomPays);
         }

         function pathToFlag($nomPays) {
           return urlencode(str_replace("'", '_', replaceBlank($nomPays)));
         }
         $previous_dateImport = null;
         foreach ($imports as $i):
         $date_import = $i['key'][1];
         $need_th = ($date_import != $previous_dateImport );
         $previous_dateImport = $date_import;

         $nom_pays = $i['key'][2];
         $nb_import = $i['value'];
         ?>
          <tr>
            <?php if ($need_th): ?> <th class="import-date" style="background-color:#4d7eac !important; color:white;" colspan="2"> <?php echo link_to($date_import, '@imports?selectedDate=' . $date_import); endif; ?></th>
              <?php $no_blank_p = replaceBlank($nom_pays); ?>
                <tr style="font-weight:300;">
                  <td>
                    <?php echo '<li style="list-style-position:inside; list-style-image: url(/images/drapeaux/'. pathToFlag(ucfirst($no_blank_p)).'.png)"><strong>'. link_to($nom_pays,'recherche/search?query=date_import:' . $date_import . '&facets=facet_pays:'.$nom_pays) . '</li>' ?>
                  </td>
                  <td>
                    <?php  echo link_to($nb_import,'recherche/search?query=date_import:' . $date_import . '&facets=facet_pays:'.$nom_pays); ?>
                  </td>
                </tr>
          </tr>
          <?php endforeach; ?>
       </tbody>
     </table>
     <?php $lastDate = end($imports)['key'][1]; ?>
     <span style="display:flex;justify-content:flex-end;">
        <?php echo link_to('<span class="btn btn-primary import-plus" style="width:150px;font-weight:700;">Voir plus</span>', '@imports?selectedDate=' . $lastDate); ?>
     </span>
  </div>
</div>
