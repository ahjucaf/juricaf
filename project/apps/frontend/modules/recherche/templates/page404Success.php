<?php use_helper('Text'); ?>

<div class="container mt-5 pb-3">
<h5 class="p-3 mb-2 bg-secondary bg-gradient">Cette page n'est pas disponible</h5>
<p>Vous pouvez utiliser le <a href="<?php echo url_for('@recherche_avancee'); ?>">formulaire de recherche avancée</a> afin d'affiner votre recherche.</p>
<p id="suggested"></p>
</div>

<script type="text/javascript">
<!--
url = '<?php echo url_for('@recherche'); ?>';
suggested_search = document.location.pathname+document.location.search;
suggested_search = suggested_search.replace(RegExp('[^a-z0-9]','gi'),' ');

terms = suggested_search.split(' ');
links = '';
$.each(terms, function(key, value) {
  links = links+'<a href="'+url+'/'+value+'">'+value+'</a> ';
});

suggest = 'Ou tenter une recherche simple sur l\'un de ces termes : '+links;
$('#suggested').html(suggest);

var gaJsHost = (("https:" == document.location.protocol) ? " https://ssl." : "http://www.");
document.write(unescape("%3Cscript src='" + gaJsHost + " google-analytics.com/ga.js' type='text/javascript'%3E%3C/script%3E"));
// -->
</script>
<script type="text/javascript">
<!--
try{
var pageTracker = _gat._getTracker("UA-8802834-4");
pageTracker._trackPageview("/404.html?page=" + document.location.pathname + document.location.search + "&from=" + document.referrer);
} catch(err) {}
// -->
</script>