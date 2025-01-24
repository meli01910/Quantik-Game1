<?php

namespace quantiketape1;


abstract class AbstractUIGenerator
{
    protected static function getDebutHTML(string $title): string
    {

        $debut_html = '<!doctype html>
<html lang="fr">
    <head>
        <meta charset="UTF-8" >
        <title>' . $title . '</title>
        <link rel="stylesheet" type="text/css" href="../Ressources/quantik.css" />
       <!-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.3/css/bulma.min.css">-->

    </head>
    <body>';
     return $debut_html;}

    protected static function getFinHTML(): string
    {
        $fin_html = '<footer class="footer">
 @M.melissa & R.Dicard
    </footer>
    </body>
</html>';

        return $fin_html;
    }

    public static function getPageErreur(string $message, string $urlLien)
    {
        $debut_html = self::getDebutHTML("Page d'erreur");
        $fin_html = self::getFinHTML();

        $page_erreur = $debut_html . '
    <div id="erreur">
        <h1>Erreur</h1>
        <p>$message</p>
        <p><a href="$urlLien"></a></p>
    </div>
' . $fin_html;

        return $page_erreur;
    }

}