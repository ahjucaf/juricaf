<?xml version="1.0" encoding="UTF-8"?>
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
<?php foreach($rows as $row): ?>
    <url>
        <loc>https://juricaf.org/arret/<?php echo $row['id'] ?></loc>
        <lastmod><?php echo $row['key'][3] ?></lastmod>
    </url>
<?php endforeach; ?>
</urlset>
