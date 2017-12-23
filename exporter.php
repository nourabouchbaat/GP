<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="fr">
 
   <head>
    
       <title>Sauvegarder la base de donnees une fiche</title>
       <meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
        <link rel="stylesheet" media="screen" type="text/css" id="css"  href="style.css" />
    </head>
    <body>
    <div id="fondaccueil">
 
        <div id="cadremenu2">
 
            <div id="cadreeffacer">
            Sauvegarder<br><br>
            Connexion &#224 la base de donn&#233es.<br>
             
<?php
    function dump($ignore)
    {
     $server = 'localhost';
     $database = 'gestion_personnel';
     $user = 'root';
     $password = '';
      
     //Connexion à la base
     $db = mysql_connect($server, $user, $password) or die(mysql_error());
     mysql_select_db($database, $db) or die(mysql_error());
      
     //on récupère la liste des tables de la base de données
     //$tables = mysql_list_tables($database, $db) or die(mysql_error());
     $sql = 'SHOW TABLES FROM '.$database;
     $tables = mysql_query($sql) or die(mysql_error());
      
     // si on ne veut pas récupérer les $ignore premières tables
     for ($i=0; $i<$ignore; $i++) ($donnees = mysql_fetch_array($tables));
      
     // aller on boucle sur toutes les tables
     while ($donnees = mysql_fetch_array($tables))
     {
      // on récupère le create table (structure de la table)
      $table = $donnees[0];
      $sql = 'SHOW CREATE TABLE '.$table;
      $res = mysql_query($sql) or die(mysql_error().$sql);
      if ($res)
      {
       $datedossier = date("d_m_Y-H_i_s");
       @mkdir ('backup/' . $datedossier);
       $backup_file = 'backup/' . $datedossier . '/' . $table . '.sql.gz';
       ?><br>Sauvegarde de la table: <?php echo $table;
       $fp = gzopen($backup_file, 'w');
      
       $tableau = mysql_fetch_array($res);
       $tableau[1] .= ";\n";
       $insertions = $tableau[1];
       gzwrite($fp, $insertions);
      
       $req_table = mysql_query('SELECT * FROM '.$table) or die(mysql_error());
       $nbr_champs = mysql_num_fields($req_table);
       while ($ligne = mysql_fetch_array($req_table))
       {
        $insertions = 'INSERT INTO '.$table.' VALUES (';
        for ($i=0; $i<$nbr_champs; $i++)
        {
         $insertions .= '\'' . mysql_real_escape_string($ligne[$i]) . '\', ';
        }
        $insertions = substr($insertions, 0, -2);
        $insertions .= ");\n";
        gzwrite($fp, $insertions);
       }
      } // fin if ($res)
      mysql_free_result($res);
      gzclose($fp);
     }
     return true;
    }
      
    //appel de la fonction
    $dump = dump(0);
    ?>
    <br><br>Proc&#233dure termin&#233e. Base de donn&#233es sauvegard&#233e.</br>
                </div>
            </div>   
        </div>
    </div>
</body>
</html>