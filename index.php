<?php 
    $titre = "Page D'accueil";
    $page = "style.css";
    require 'header.inc.php';
    session_start();
?>

<body>
    <header>
        <nav class="navbar navbar-expand-lg" id="nav">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php" style="font-weight: bold;">
                    <img src="logo.png" alt="Logo" width="100" height="100" class="d-inline-block align-text-center">HOME
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo01" aria-controls="navbarTogglerDemo01" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarTogglerDemo01">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item"><a class="nav-link" href="Pages/pageAjoutConfig.php">Ajouter Config</a></li>
                        <li class="nav-item"><a class="nav-link" href="Pages/pageModifCSV.php">Modifier Fichiers</a></li>
                        <li class="nav-item"><a class="nav-link" href="Pages/pageAjoutCSV.php">Ajouter Fichiers Etats</a></li>
                        <li class="nav-item"><a class="nav-link" href="Pages/pageSuppCSV.php">Supprimer Fichiers Etats</a></li>
                        <li class="nav-item"><a class="nav-link" href="Pages/pageSuppConfig.php">Supprimer Config</a></li>
                        <li class="nav-item"><a class="nav-link" href="Pages/pageDeploiements.php">Déploiements</a></li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <main>
        <div class="row">
        <div class="col-4">
                <nav id="navbar-example3" class="h-100 flex-column align-items-stretch pe-4 border-end scrollable-nav">
                    <nav class="nav nav-pills flex-column">
                        <a class="nav-link" href="#item-1">Ajouter Config</a>
                        <nav class="nav nav-pills flex-column">
                            <a class="nav-link ms-3 my-1" href="#item-1-1">Pourquoi ?</a>
                            <a class="nav-link ms-3 my-1" href="#item-1-2">comment ?</a>
                        </nav>
                        <a class="nav-link" href="#item-2">Modifier Fichiers</a>
                        <nav class="nav nav-pills flex-column">
                            <a class="nav-link ms-3 my-1" href="#item-2-1">Pourquoi ?</a>
                            <a class="nav-link ms-3 my-1" href="#item-2-2">Comment ?</a>
                        </nav>
                        <a class="nav-link" href="#item-3">Ajouter Fichiers Etats</a>
                        <nav class="nav nav-pills flex-column">
                            <a class="nav-link ms-3 my-1" href="#item-3-1">Pourquoi ?</a>
                            <a class="nav-link ms-3 my-1" href="#item-3-2">Comment ?</a>
                        </nav>
                        <a class="nav-link" href="#item-4">Supprimer Fichiers Etats</a>
                        <nav class="nav nav-pills flex-column">
                            <a class="nav-link ms-3 my-1" href="#item-4-1">Pourquoi ?</a>
                            <a class="nav-link ms-3 my-1" href="#item-4-2">Comment ?</a>
                        </nav>
                        <a class="nav-link" href="#item-5">Supprimer Config</a>
                        <nav class="nav nav-pills flex-column">
                            <a class="nav-link ms-3 my-1" href="#item-5-1">Pourquoi ?</a>
                            <a class="nav-link ms-3 my-1" href="#item-5-2">Comment ?</a>
                        </nav>
                        <a class="nav-link" href="#item-6">Déploiement</a>
                        <nav class="nav nav-pills flex-column">
                            <a class="nav-link ms-3 my-1" href="#item-6-1">Pourquoi ?</a>
                            <a class="nav-link ms-3 my-1" href="#item-6-2">Comment ?</a>
                        </nav>
                    </nav>
                </nav>
            </div>
            <div class="col-8">
                <div data-bs-spy="scroll" data-bs-target="#navbar-example3" data-bs-smooth-scroll="true" class="scrollspy-example-2" tabindex="0">
                    <div id="item-1">
                        <h4>Ajouter Config</h4>
                        <p>
                        </p>
                    </div>
                    <div id="item-1-1">
                        <h5>Pourquoi ?</h5>
                        <p>Une configuration du site contient tous les fichiers CSV de configuration des cartes CAC de cette façon:</p>
                        <img src="Images/creerConfigPourquoi1.png" alt="Image" height="512" width="800"> 
                        <p>Les configurations contiennent aussi le nom de la configuration, le nombre et le nom des cartes CAC.
                        Il est possible de faire autant de configurations que l'on veut avec un nombre maximal de 239 cartes CAC 
                        (bien que pour l'instant ce nombre est limité à 15 cartes en raison du manque de configurations générés 
                        par OpenCONFIGURATOR des dossiers output_xCNs).
                        Lors de la création d'une configuration ces fichiers sont générés automatiquement : 
                        activation.csv, liaisonEGEtat.csv et nbNodes.h en commun pour toutes les cartes et 
                        physicalCONFIG_sensors.csv, physicalCONFIG_valves.csv et nodeId.h pour chacune des cartes comme ci-dessus.
                        Les valeurs non définies dans les fichiers CSV sont indiquées par des '#'.
                        </p>
                    </div>
                    <div id="item-1-2">
                        <h5>Comment ?</h5>
                        <p>Pour créer une configuration, il faut ajouter un nom ainsi que le nombre de carte voulu dans la configuration.</p>
                        <img src="Images/creerConfig1.png" alt="Image" height="177" width="400"> 
                        <p>Appuyez ensuite sur le bouton "Créer Tableau". Ce bouton permet de d'obtenir un tableau contenant les valeurs par défaut de la configuration.</p>
                        <img src="Images/creerConfig2.png" alt="Image" height="464" width="800">
                        <p>Pour changer ces valeurs, il faut selectionner une ligne puis remplacer dans "Nom des Cartes" le "#" par le nom voulu. Appuyez ensuite sur le bouton "Modifier" afin de modifier le nom de la carte.</p>
                        <img src="Images/creerConfig3.png" alt="Image" height="91" width="465">
                        <p>Une fois avoir indiqué le nom de toutes les cartes, cliquez sur le bouton "Ajouter Configuration" afin de créer la configuration et tous ces dossiers associés :</p>
                        <img src="Images/creerConfig4.png" alt="Image" height="411" width="800">
                        <p>Un message de confirmation de la création de la configuration apparait :</p>
                        <img src="Images/creerConfig5.png" alt="Image" height="498" width="800">
                    </div>
                    <div id="item-2">
                        <h4>Modifier Fichiers</h4>
                        <p></p>
                    </div>
                    <div id="item-2-1">
                        <h5>Pourquoi ?</h5>
                        <p>Dans la procédure de configuration des cartes CAC il est nécessaire de configurer 
                        les entrées (capteurs) et les sorties (vannes) de chaque carte.
                        Sur le site ce sont les fichiers "Fichier CSV configuration physique vannes" et 
                        "Fichier CSV configuration physique capteurs" qu'il faut commencer à configurer pour chaque carte CAC.
                        La troisième colonne "Etat initial" de "Fichier CSV configuration physique vannes"
                        correspond aux valeurs lues et appliquées à l'état d'initialisation du programme OpenPOWERLINK de la carte CAC.
                        La quatrième colonne "PORT GPIO" correspond au port logique du contrôleur GPIO de la raspberry pi 4 (ou CM4 de même pinout) connectées au pins ci-dessous :
                        </p>
                        <img src="Images/modifierFichierPourquoi1.png" alt="Image" height="459" width="800"> 
                        <p>Par exemple si la vanne A de la deuxième est connectée au GPIO 5, on écrit 5 dans le champ port GPIO.
                        L'ordre des vannes dans le tableau est aussi important par exemple, si vanne A est entrée sur la première ligne
                        sans compter l'entête, elle correspond à la 15ème ligne sur un CSV d'état général et à la 27ème ligne sur le fichier "activation.csv"
                        Ceci est dû à l'arrangement des paramètres des cartes les uns après les autres de façon cyclique.
                        </p>
                        <p>Ensuite la deuxième colonne "Capteur" de "Fichier CSV configuration physique capteurs" tout comme la deuxième colonne
                        "Vannes" de "Fichier CSV configuration physique vannes" correspond au nom du capteur et sera recopié sur le fichier "activation.csv"
                        et sur les fichiers CSV d'état généraux par la suite pour une meilleure compréhension.
                        la troisième colonne "Type" correspond au type du capteur  : 1 ou MCP3008 pour un capteur branché sur les channels du MCP3008 sur les cartes CAC
                        et 2 ou Modbus pour un capteur branché sur le convertisseur rs485 vers USB serial.
                        Les colonnes suivantes correspondent aux paramètres de Modbus à configurer en fonction du capteur rs485 branché.
                        Pour la version 1.2 du programme OpenPOWERLINK des cartes CAC, seulement un registre peut être lu pour une ligne de "Fichier CSV configuration physique capteurs"
                        en raison des limitations d'OpenPOWERLINK de pouvoir envoyer qu'une seule valeur de vanne ou de capteur pour un maximum de 12 vannes et 12 capteurs
                        par carte CAC (par CN).
                        </p>
                        <p>La prochaine modification possible sont les "Fichiers CSV etats" ou fichiers CSV d'état généraux. Ils sont décrits plus en détails
                        dans la prochaine partie "Ajouter Fichiers Etats". Ici on peut modifier chacuns de ces fichiers CSV pour une configuration de la même façon que pour la création.
                        </p>
                        <p>Enfin le dernier fichier "activation.csv" correspond à la matrice d'activation de tous les capteurs et valves de toutes les cartes CAC.
                        La troisième colonne "Activation" correspond à l'information d'activation ou non d'une vanne ou d'un capteur (1 pour activé, 0 pour désactivé).
                        Concrètement dans le programme principal des CAC, la ligne GPIO d'une vanne désactivée ne sera pas créé et donc jamais actionné donc c'est comme si cette vanne n'existe pas
                        pour le programme. Même chose pour un capteur désactivé, le capteur ne sera pas instancié et aucune valeur ne sera lue.
                        Il est donc important de vérifier que les vannes et capteurs entrés dans "Fichier CSV configuration physique vannes" et 
                        "Fichier CSV configuration physique capteurs" sont bien activés dans "activation.csv" 
                        (rappel : la vanne de la première ligne de "Fichier CSV configuration physique vannes" de la deuxième carte correspond à la 27ème ligne dans "activation.csv").
                        </p>
                    </div>
                    <div id="item-2-2">
                        <h5>Comment ?</h5>
                        <p>Pour modifier un fichier, il faut choisir une configuration créée plus tôt et appuyer sur "Selectionner Config".</p>
                        <p>Pour modifier le fichier CSV de la configuration physique des vannes, il faut choisir le fichier de la vanne voulue puis appuyer sur "Afficher". 
                        il faut ensuite selectionner une ligne puis remplacer dans "Nom des Cartes" le "#" par le nom voulu. </p>
                        <p></p>
                    </div>
                    <div id="item-3">
                        <h4>Ajouter Fichiers Etats</h4>
                        <p></p>
                    </div>
                    <div id="item-3-1">
                        <h5>Pourquoi ?</h5>
                        <p>Les fichiers CSV d'états généraux permettent de changer les valeurs des vannes à partir 
                        d'un ordre EG (état général) envoyé du MN à toutes les cartes CAC (à tous les CNs).
                        Pour créer un CSV d'état général il faut d'abord associer le code EG (état général) au nom du fichier CSV
                        pour que le programme OpenPOWERLINK ce mette à lire le fichier CSV correspondant à l'ordre EG qu'il a reçu.
                        De la même façon que pour le fichier "activation.csv", les paramètres des vannes de chaques carte CAC sont
                        mis les uns après les autres de façon cyclique.
                        Par exemple les vannes d'une deuxième carte B seront 13 lignes après celles d'une première carte A.
                        </p>
                        <p>La troisième colonne "Valeur" correspond à la valeur GPIO de sortie, 1 pour HIGH et 0 pour LOW.
                        Cette valeur peut être contraint d'être activée qu'à partir d'un certain temps et/ou seulement si d'autres vannes
                        de la même carte sont activées.
                        </p>
                        <p>La quatrième colonne "Timer dépendance" correspond au chronomètre (en secondes double précision) qui se lance 
                        lorsque tous les dépendances de la cinquième colonne sont déclanchées (sont passées à leurs valeurs de la troisième colonne).
                        Si aucune dépendance n'est indiquée pour une vanne, le timer de cette vanne démarre directement au passage de ce
                        fichier CSV d'état général.
                        </p>
                        <p>Enfin la cinquième colonne correspond à la liste des dépendances d'une vanne sur d'autres vannes de la même carte.
                        C'est à dire que cette vanne ne se déclanche (ou démarre le timer) uniquement si toutes les vannes dont elle dépend 
                        sont déclanchées (sont passées à leurs valeurs de la troisième colonne). 
                        </p>
                        <img src="Images/creerFichierCSVPourquoi1.png" alt="Image" height="376" width="800"> 
                        <p>Par exemple ici, on définit trois vannes, VCE, VPr0 et VMA sur la carte A. On remarque qu VMA dépend de VCE et VPr0 
                        c'est à dire que réellement, VMA ne démarrera sont timer qu'après que celui de VPr0 soit fini soit 1,2 secondes après, 
                        enfin VMA se déclanchera à la fin de sont timer soit 3,5 secondes après.
                        VCE ici se déclanche instantanement dès le changement de fichier CSV d'état général et donc ne bloque pas 
                        le déclanchement de VMA.
                        </p>
                    </div>
                    <div id="item-3-2">
                        <h5>Comment ?</h5>
                        <p></p>
                    </div>
                    <div id="item-4">
                        <h4>Supprimer Fichiers Etats</h4>
                        <p></p>
                    </div>
                    <div id="item-4-1">
                        <h5>Pourquoi ?</h5>
                        <p></p>
                    </div>
                    <div id="item-4-2">
                        <h5>Comment ?</h5>
                        <p></p>
                    </div>
                    <div id="item-5">
                        <h4>Supprimer Config</h4>
                        <p></p>
                    </div>
                    <div id="item-5-1">
                        <h5>Pourquoi ?</h5>
                        <p></p>
                    </div>
                    <div id="item-5-2">
                        <h5>Comment ?</h5>
                        <p></p>
                    </div>
                    <div id="item-6">
                        <h4>Déploiements</h4>
                        <p></p>
                    </div>
                    <div id="item-6-1">
                        <h5>Pourquoi ?</h5>
                        <p></p>
                    </div>
                    <div id="item-6-2">
                        <h5>Comment ?</h5>
                        <p></p>
                    </div>
                </div>
            </div>
        </div>
    </main>    
    <?php if (PHP_OS_FAMILY === 'Windows') { ?>
    <footer>
        Vous utilisez actuellement la version Windows du site de configuration,
        vous pouvez mettre le site sur la carte Linux du MN pour plus de fonctionnalités. (voir NT du site)
    </footer>
    <?php }?>
    <?php 
        unset($_SESSION['csvData']);
        unset($_SESSION['csvFileName']);
        unset($_SESSION['csvName']);
        unset($_SESSION['dataConfig']);
        unset($_SESSION['nomCOnfig']); 
        session_write_close();
    ?>
</body>
