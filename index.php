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
                    </div>
                    <div id="item-1-1">
                        <h5>Pourquoi ?</h5>
                        <p>Une configuration du site contient tous les fichiers CSV de configuration des cartes CAC de cette façon:<br>                             
                        </p>
                        <img src="Images/creerConfigPourquoi1.png" alt="Image" class="img-fluid"> 
                        <p>Les configurations contiennent aussi le nom de la configuration, le nombre et le nom des cartes CAC.
                        Il est possible de faire autant de configurations que l'on veut avec un nombre maximal de 239 cartes CAC 
                        (bien que pour l'instant ce nombre est limité à 15 cartes en raison du manque de configurations générés 
                        par OpenCONFIGURATOR des dossiers output_xCNs).
                        Lors de la création d'une configuration ces fichiers sont générés automatiquement : <br>
                        activation.csv, liaisonEGEtat.csv et nbNodes.h en commun pour toutes les cartes et 
                        physicalCONFIG_sensors.csv, physicalCONFIG_valves.csv et nodeId.h pour chacune des cartes comme ci-dessus.
                        Les valeurs non définies dans les fichiers CSV sont indiquées par des '#'.
                        </p>
                    </div>
                    <div id="item-1-2">
                        <h5>Comment ?</h5>
                        <p>Pour créer une configuration, il faut ajouter un nom ainsi que le nombre de carte voulu dans la configuration.<br></p>
                        <img src="Images/creerConfig1.png" alt="Image" height="177" width="400"> 
                        <p>Appuyez ensuite sur le bouton "Créer Tableau". Ce bouton permet de d'obtenir un tableau contenant les valeurs par défaut de la configuration.<br></p>
                        <img src="Images/creerConfig2.png" alt="Image" class="img-fluid">
                        <p>Pour changer ces valeurs, il faut selectionner une ligne puis remplacer dans "Nom des Cartes" le "#" par le nom voulu. Appuyez ensuite sur le bouton "Modifier" afin de modifier le nom de la carte.<br></p>
                        <img src="Images/creerConfig3.png" alt="Image" class="img-fluid">
                        <p>Une fois avoir indiqué le nom de toutes les cartes, cliquez sur le bouton "Ajouter Configuration" afin de créer la configuration et tous ces dossiers associés.<br></p>
                        <img src="Images/creerConfig4.png" alt="Image" class="img-fluid">
                        <p>Un message de confirmation de la création de la configuration apparait.<br></p>
                        <img src="Images/creerConfig5.png" alt="Image" class="img-fluid">
                    </div>
                    <div id="item-2">
                        <h4>Modifier Fichiers</h4>
                    </div>
                    <div id="item-2-1">
                        <h5>Pourquoi ?</h5>
                        <p>Dans la procédure de configuration des cartes CAC il est nécessaire de configurer 
                        les entrées (capteurs) et les sorties (vannes) de chaque carte.
                        Sur le site ce sont les fichiers "Fichier CSV configuration physique vannes" et 
                        "Fichier CSV configuration physique capteurs" qu'il faut commencer à configurer pour chaque carte CAC.
                        La troisième colonne "Etat initial" de "Fichier CSV configuration physique vannes"
                        correspond aux valeurs lues et appliquées à l'état d'initialisation du programme OpenPOWERLINK de la carte CAC.
                        La quatrième colonne "PORT GPIO" correspond au port logique du contrôleur GPIO de la raspberry pi 4 (ou CM4 de même pinout) connectées au pins ci-dessous :<br>
                        </p>
                        <img src="Images/modifierFichierPourquoi1.png" alt="Image" class="img-fluid"> 
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
                        par carte CAC (par CN).<br>
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
                        (rappel : la vanne de la première ligne de "Fichier CSV configuration physique vannes" de la deuxième carte correspond à la 27ème ligne dans "activation.csv").<br>
                        </p>
                    </div>
                    <div id="item-2-2">
                        <h5>Comment ?</h5>
                        <p>Pour modifier un fichier, il faut choisir une configuration créée plus tôt et appuyer sur "Selectionner Config". Plusieurs types de fichiers s'affichent. </p>
                        <img src="Images/modifFichiers1.png" alt="Image" class="img-fluid">
                        <p>Pour modifier le fichier CSV de la <b><u>configuration physique</u></b> des <b><u>vannes</u></b>, il faut : <br></p>
                        <ul>
                            <li>choisir le fichier de la vanne voulue. </li>
                            <li>appuyer sur "Afficher". Un formulaire apparait.</li>
                            <li>cliquer sur une ligne et modifier les valeurs.</li>
                            <img src="Images/modifFichiers2.png" alt="Image" class="img-fluid">
                            <li>appuyer sur le bouton "Modifier Ligne".</li>
                            <img src="Images/modifFichiers3.png" alt="Image" class="img-fluid">
                        </ul>
                        <p>Pour modifier le fichier CSV de la <b><u>configuration physique</u></b> des <b><u>capteurs</u></b>, il faut : <br></p>
                        <ul>
                            <li>choisir le fichier du capteur voulu. </li>
                            <li>appuyer sur "Afficher". Un formulaire apparait.</li>
                            <li>cliquer sur une ligne et modifier les valeurs.</li>
                            <li>appuyer sur le bouton "Modifier Ligne".</li>
                        </ul>
                        <div class="row">
                            <div class="col">
                                <img src="Images/modifFichiers4.png" alt="Image" class="img-fluid"> 
                            </div>
                            <div class="col">
                                <img src="Images/modifFichiers5.png" alt="Image" class="img-fluid"> 
                            </div>
                        </div>
                        <p>Pour modifier le fichier CSV des <b><u>activations</u></b>, il faut : </p>
                        <ul>
                            <li>appuyer sur "Afficher". Un formulaire apparait.</li>
                            <li>cliquer sur une ligne et modifier la valeur de activation(3e colonne).</li>
                            <li>appuyer sur le bouton "Modifier Ligne".</li>
                        </ul>
                        <div class="row">
                            <div class="col">
                                <img src="Images/modifFichiers6.png" alt="Image" class="img-fluid"> 
                            </div>
                            <div class="col">
                                <img src="Images/modifFichiers7.png" alt="Image" class="img-fluid"> 
                            </div>
                        </div>
                        <p>Pour modifier le fichier CSV des <b><u>fichiers Etat Générals</u></b>, il faut : </p>
                        <ul>
                            <li>choisir le fichier du capteur voulu. </li>
                            <li>appuyer sur "Afficher". Un formulaire apparait.</li>
                            <li>cliquer sur une ligne et modifier les valeurs.</li>
                            <img src="Images/modifFichiers8.png" alt="Image" class="img-fluid">
                            <li>appuyer sur le bouton "Modifier Ligne".</li>
                            <img src="Images/modifFichiers9.png" alt="Image" class="img-fluid">
                        </ul>
                        <p>(<i><b><u>La modification du fichier Etat général est possible si le fichier a déjà été créé</u></b></i>)<br></p>
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
                        <b style="color:red"><u>
                        Important : pour ne pas confondre les code EG avec les codes hexadécimaux de statuts et d'erreurs du programme OpenPOWERLINK, 
                        Les codes EG doivent être compris entre 0x1000 et 0x6FFF (ou en décimal entre 4096 et 28671).
                        </u></b>
                        De la même façon que pour le fichier "activation.csv", les paramètres des vannes de chaques carte CAC sont
                        mis les uns après les autres de façon cyclique.
                        Par exemple les vannes d'une deuxième carte B seront 13 lignes après celles d'une première carte A.<br>
                        </p>
                        <p>La troisième colonne "Valeur" correspond à la valeur GPIO de sortie, 1 pour HIGH et 0 pour LOW.
                        Cette valeur peut être contraint d'être activée qu'à partir d'un certain temps et/ou seulement si d'autres vannes
                        de la même carte sont activées.<br>
                        </p>
                        <p>La quatrième colonne "Timer dépendance" correspond au chronomètre (en secondes double précision) qui se lance 
                        lorsque tous les dépendances de la cinquième colonne sont déclanchées (sont passées à leurs valeurs de la troisième colonne).
                        Si aucune dépendance n'est indiquée pour une vanne, le timer de cette vanne démarre directement au passage de ce
                        fichier CSV d'état général.
                        Si vous mettez la 0 en timer, le 0 sera remplacé par un '#' dans le fichier CSV. Le programme OpenPOWERLINK intéprète un '#' en tant que 0.<br>
                        </p>
                        <p>Enfin la cinquième colonne correspond à la liste des dépendances d'une vanne sur d'autres vannes de la même carte.
                        C'est à dire que cette vanne ne se déclanche (ou démarre le timer) uniquement si toutes les vannes dont elle dépend 
                        sont déclanchées (sont passées à leurs valeurs de la troisième colonne). <br>
                        </p>
                        <img src="Images/creerFichierCSVPourquoi1.png" alt="Image" class="img-fluid"> 
                        <p>Par exemple ici, on définit trois vannes, VCE, VPr0 et VMA sur la carte A. On remarque qu VMA dépend de VCE et VPr0 
                        c'est à dire que réellement, VMA ne démarrera sont timer qu'après que celui de VPr0 soit fini soit 1,2 secondes après, 
                        enfin VMA se déclanchera à la fin de son timer soit 3,5 secondes après.
                        VCE ici se déclanche instantanement dès le changement de fichier CSV d'état général et donc ne bloque pas 
                        le déclanchement de VMA.
                        </p>
                    </div>
                    <div id="item-3-2">
                        <h5>Comment ?</h5>
                        <p>Choisissez une configuration, un code Etat Général en décimal et un nom pour le fichier état général.<br></p>
                        <img src="Images/ajoutEtatEG1.png" alt="Image" class="img-fluid"> 
                        <p>Une fois le fichier créé, un formulaire et un tableau apparaissent. Cliquez sur une ligne du tableau et modifiez les valeurs. 
                            (Pour dépendance vanne, la valeur obtenue correspond à l'indice de la ligne de la vanne)<br></p>
                        <div class="row">
                            <div class="col">
                                <img src="Images/ajoutEtatEG2.png" alt="Image" class="img-fluid"> 
                            </div>
                            <div class="col">
                                <img src="Images/ajoutEtatEG4.png" alt="Image" class="img-fluid">
                            </div>
                        </div>
                        <p>Un message de validation des modifications apparait.<br></p>
                        <div class="row">
                            <div class="col">
                                <img src="Images/ajoutEtatEG3.png" alt="Image" class="img-fluid"> 
                            </div>
                            <div class="col">
                                <img src="Images/ajoutEtatEG5.png" alt="Image" class="img-fluid">
                            </div>
                        </div>
                        <p></p>
                    </div>
                    <div id="item-4">
                        <h4>Supprimer Fichiers Etats</h4>
                    </div>
                    <div id="item-4-1">
                        <h5>Pourquoi ?</h5>
                        <p>Les fichiers CSV d'états généraux ne sont pas indispensables au bon fonctionnement du programme CAC,
                        bien que sans ces fichiers la seule utilité du programme sera de transmettre les valeurs des capteurs
                        des cartes CAC au MN.
                        De ce fait il est possible de créer ou de supprimer autant de fichiers CSV d'états généraux que l'on veut
                        à la limite de 24 575 fichiers CSV.
                        En revanche la suppression d'un fichier CSV d'état général est irréversible.
                        </p>
                    </div>
                    <div id="item-4-2">
                        <h5>Comment ?</h5>
                        <p>Pour supprimer un fichier état créé précédemment, il faut : <br></p>
                        <ul>
                            <li>sélectionner la configuration. </li>
                            <li>appuyer sur le bouton "Sélectionner Config".</li>
                        </ul>   
                        <p>Une fois le bouton sélectionné, tous les fichiers État général appartenant à la configuration sont affichés dans 
                        le bouton déroulant. Ici, nous avons juste un fichier état qui est "Etat_1.csv".<br></p>
                        <img src="Images/supprEtat1.png" alt="Image"> 
                        <p>Pour afficher le fichier, il faut appuyer sur le bouton "Afficher le fichier".<br></p>
                        <img src="Images/supprEtat2.png" alt="Image" class="img-fluid"> 
                        <p>Pour supprimer un fichier état, il suffit d'appuyer sur le bouton "Supprimer le fichier". Un message de validation 
                            apparaît. Cliquez sur "OK".<br></p>
                        <img src="Images/supprEtat3.png" alt="Image"> 
                        <p>Un message de confirmation apparaît confirmant la suppression du fichier.<br></p>
                        <img src="Images/supprEtat4.png" alt="Image" height="271" width="500">

                    </div>
                    <div id="item-5">
                        <h4>Supprimer Config</h4>
                    </div>
                    <div id="item-5-1">
                        <h5>Pourquoi ?</h5>
                        <p>Les configurations de ce site web de configuration du programme OpenPOWERLINK n'ont pas de lien avec le programme
                        et sont présents uniquement pour regrouper les fichiers CSV à déployer.
                        Les configurations peuvent donc être supprimées ou créées sans limite. En revanche la suppression d'une configuration est
                        irréversible.<br>
                        </p>
                    </div>
                    <div id="item-5-2">
                        <h5>Comment ?</h5>
                        <p>Pour supprimer une configuration il faut : <br>
                        <ul>
                            <li>choisir la configuration qu'on veut supprimer. </li>
                            <li>puis appuyer sur le bouton "Supprimer la config".</li>
                            <li>appuyez sur le bouton "OK" du message de validation de la suppression pour supprimer la configuration.</li>
                        </ul>
                        <img src="Images/supprConfig1.png" alt="Image">
                        <p>Un message confirmant la suppression de la configuration apparait.<br></p>
                        <img src="Images/supprConfig2.png" alt="Image" class="img-fluid">
                    </div>
                    <div id="item-6">
                        <h4>Déploiements</h4>
                    </div>
                    <div id="item-6-1">
                        <h5>Pourquoi ?</h5>
                        <p>Lorsque tous les fichiers CSV d'une configuration ont été configurés, il faut pouvoir les envoyer sur les cartes CAC et au MN.
                        Cette page sert donc de liaison entre ce site web de configuration et le programme OpenPOWERLINK des cartes CAC et du MN.
                        Sur la page déploiement il faut d'abord renseigner les information de connexion aux cartes CAC et au MN.<br>
                        </p>
                        <p>
                        Cette page est differente si le site web est hebergé sur la Raspberry Pi CM4 du MN ou sur un PC sous Windows connecté sur le réseau local
                        des cartes CAC.
                        Sur la Raspberry Pi CM4 du MN, on peut analyser le réseau local à l'aide de arp-scan et de avahi resolve, deux commandes uniquement
                        disponible sur un OS Linux. Toutes les Raspberry Pi CM4 intègre par défaut un daemon avahi qui, quand on l'intérroge depuis le réseau local
                        nous renvoie le nom d'hôte de la Raspberry Pi CM4 et donc nous permet de confirmer que l'addresse IP correspond bien à telle ou telle carte CAC.
                        De plus le site web étant hebergé sur le MN, pour le déploiement des fichiers sur le MN, il suffit de copier directement les fichiers du site
                        vers la localisation du logiciel OpenPOWERLINK au lieu de passer par une connexion SSH.
                        En revanche il faut bien penser à fixer des hostnames compréhensibles au préalable sur les cartes CAC (voir tutoriel 
                        "Procédure de préparation des RPi CM4 pour CAC et OBC" de la NT : NT_Ryan_Maël_CAC_2023_2024).<br>
                        </p>
                        <p>Sur un PC sous Windows, tout doit être entré manuellement que ce soit l'IP des cartes CAC et du MN. Il est donc nécessaire de bien
                        connaître les relations entre toutes les IPs et les cartes CAC avant de déployer.<br>
                        </p>
                        <ul>Pour la partie déploiement, après avoir entré toutes les renseignements de connexion SSH des cartes, il y a plusieurs choix de déploiement :
                        <li>
                        Cochez tout si c'est le premier déploiement que vous voulez faire ou si vous voulez changer de version de l'application OpenPOWERLINK.
                        </li>
                        <li>
                        Cochez "paramètres d'application et compilation" <b>pour le MN et le CN</b> si vous avez changé le nombre de cartes entre deux téléversements.
                        </li>
                        <li>
                        Cochez "fichiers CSV physiques" si vous voulez changer les fichiers CSV de configuration physique des capteurs et des vannes de toutes les cartes CAC.
                        </li>
                        <li>
                        Cochez "fichiers CSV communs" <b>pour le MN et le CN</b> si vous avez changé "activation.csv" ou si vous avez créé ou modifié un ou des fichiers CSV d'états généraux.
                        </li>
                        </ul>
                        <p>Attention ne cochez pas OpenPOWERLINK stack et application sans cocher les autres car tout les précédents fichiers CSV sur les cartes CAC seront supprimés.
                        Le programme OpenPOWERLINK ne peut pas fonctionner sans aucun fichiers CSV.
                        S'il y a eu une erreur lors du déploiement, une erreur s'affichera en rouge sur la page Déploiements.<br>
                        </p>
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
    <?php } else { ?>
    <footer>
        Vous utilisez actuellement la version Linux du site de configuration.
    </footer> 
    <?php }
        unset($_SESSION['csvData']);
        unset($_SESSION['csvFileName']);
        unset($_SESSION['csvName']);
        unset($_SESSION['dataConfig']);
        unset($_SESSION['nomCOnfig']); 
        session_write_close();
    ?>
</body>
